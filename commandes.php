<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
        $stmt->execute([$id]);
        $commande = $stmt->fetch();
        if ($commande) {
            $stmt2 = $pdo->prepare("SELECT * FROM commande_items WHERE commande_id = ?");
            $stmt2->execute([$id]);
            $commande['items'] = $stmt2->fetchAll();
        }
        echo json_encode($commande, JSON_UNESCAPED_UNICODE);
    } else {
        $stmt = $pdo->query("SELECT * FROM commandes ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    }

} elseif ($method === 'POST') {
    // OPTION B : stock ne change PAS à la commande
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['prenom']) || empty($data['nom']) || empty($data['telephone']) || empty($data['adresse']) || empty($data['items'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $total = 0;
        foreach ($data['items'] as $item) {
            $stmt = $pdo->prepare("SELECT prix, nom FROM produits WHERE id = ? AND actif = 1");
            $stmt->execute([$item['produit_id']]);
            $produit = $stmt->fetch();
            if (!$produit) throw new Exception("Produit introuvable");
            $total += $produit['prix'] * $item['quantite'];
        }

        $stmt = $pdo->prepare("INSERT INTO commandes (prenom, nom, telephone, adresse, total, statut) VALUES (?,?,?,?,?,'en_attente')");
        $stmt->execute([
            htmlspecialchars($data['prenom']),
            htmlspecialchars($data['nom']),
            htmlspecialchars($data['telephone']),
            htmlspecialchars($data['adresse']),
            $total
        ]);
        $commandeId = $pdo->lastInsertId();

        foreach ($data['items'] as $item) {
            $stmt = $pdo->prepare("SELECT prix, nom FROM produits WHERE id = ?");
            $stmt->execute([$item['produit_id']]);
            $produit = $stmt->fetch();
            $stmt2 = $pdo->prepare("INSERT INTO commande_items (commande_id, produit_id, nom_produit, prix_unit, quantite) VALUES (?,?,?,?,?)");
            $stmt2->execute([$commandeId, $item['produit_id'], $produit['nom'], $produit['prix'], $item['quantite']]);
            // PAS de décrément stock ici
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'commande_id' => $commandeId, 'total' => $total], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['vu'])) {
        $stmt = $pdo->prepare("UPDATE commandes SET vu = 1 WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['success' => true]);

    } elseif (isset($data['statut'])) {
        $statuts = ['en_attente','confirmee','livree','annulee'];
        if (!in_array($data['statut'], $statuts)) {
            http_response_code(400);
            echo json_encode(['error' => 'Statut invalide']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Récupérer ancien statut
            $stmt = $pdo->prepare("SELECT statut FROM commandes WHERE id = ?");
            $stmt->execute([$data['id']]);
            $ancienStatut = $stmt->fetch()['statut'];
            $nouveauStatut = $data['statut'];

            // Récupérer items
            $stmt2 = $pdo->prepare("SELECT produit_id, quantite FROM commande_items WHERE commande_id = ?");
            $stmt2->execute([$data['id']]);
            $items = $stmt2->fetchAll();

            // ── Logique stock OPTION B ──
            // Confirmée → stock décrémente (seulement si pas déjà confirmée ou livrée)
            if ($nouveauStatut === 'confirmee' && !in_array($ancienStatut, ['confirmee','livree'])) {
                foreach ($items as $item) {
                    $stmt3 = $pdo->prepare("UPDATE produits SET stock = stock - ? WHERE id = ?");
                    $stmt3->execute([$item['quantite'], $item['produit_id']]);
                }
            }

            // Annulée depuis confirmée ou livrée → stock réincrémente
            if ($nouveauStatut === 'annulee' && in_array($ancienStatut, ['confirmee','livree'])) {
                foreach ($items as $item) {
                    $stmt3 = $pdo->prepare("UPDATE produits SET stock = stock + ? WHERE id = ?");
                    $stmt3->execute([$item['quantite'], $item['produit_id']]);
                }
            }

            // Mettre à jour statut
            $stmt4 = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
            $stmt4->execute([$nouveauStatut, $data['id']]);

            $pdo->commit();
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
