<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $type = $_GET['type'] ?? null;
    $id   = $_GET['id']   ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ? AND actif = 1");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
    } elseif ($type) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE type = ? AND actif = 1 ORDER BY stock DESC, id DESC");
        $stmt->execute([$type]);
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    } else {
        $stmt = $pdo->query("SELECT * FROM produits WHERE actif = 1 ORDER BY type, id DESC");
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    }

} elseif ($method === 'POST') {
    // Ajouter produit (admin)
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['marque']) || empty($data['nom']) || empty($data['prix'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Champs obligatoires manquants']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO produits (marque,nom,specs,prix,prix_ancien,badge,emoji,type,etat,batterie,garantie,stock,image)
                               VALUES (:marque,:nom,:specs,:prix,:prix_ancien,:badge,:emoji,:type,:etat,:batterie,:garantie,:stock,:image)");
        $stmt->execute([
            ':marque'      => htmlspecialchars($data['marque']),
            ':nom'         => htmlspecialchars($data['nom']),
            ':specs'       => htmlspecialchars($data['specs'] ?? ''),
            ':prix'        => floatval($data['prix']),
            ':prix_ancien' => !empty($data['prix_ancien']) ? floatval($data['prix_ancien']) : null,
            ':badge'       => !empty($data['badge']) ? $data['badge'] : null,
            ':emoji'       => !empty($data['emoji']) ? $data['emoji'] : '📱',
            ':type'        => in_array($data['type'] ?? '', ['neuf','occasion','accessoire']) ? $data['type'] : 'neuf',
            ':etat'        => !empty($data['etat']) ? $data['etat'] : null,
            ':batterie'    => !empty($data['batterie']) ? intval($data['batterie']) : null,
            ':garantie'    => !empty($data['garantie']) ? $data['garantie'] : null,
            ':stock'       => intval($data['stock'] ?? 0),
            ':image'       => !empty($data['image']) ? $data['image'] : null,
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} elseif ($method === 'PUT') {
    // Modifier produit (admin)
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID manquant']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE produits SET marque=:marque, nom=:nom, specs=:specs, prix=:prix,
                               prix_ancien=:prix_ancien, badge=:badge, emoji=:emoji, type=:type,
                               etat=:etat, batterie=:batterie, garantie=:garantie, stock=:stock,
                               image=:image WHERE id=:id");
        $stmt->execute([
            ':marque'      => htmlspecialchars($data['marque'] ?? ''),
            ':nom'         => htmlspecialchars($data['nom'] ?? ''),
            ':specs'       => htmlspecialchars($data['specs'] ?? ''),
            ':prix'        => floatval($data['prix'] ?? 0),
            ':prix_ancien' => !empty($data['prix_ancien']) ? floatval($data['prix_ancien']) : null,
            ':badge'       => !empty($data['badge']) ? $data['badge'] : null,
            ':emoji'       => !empty($data['emoji']) ? $data['emoji'] : '📱',
            ':type'        => in_array($data['type'] ?? '', ['neuf','occasion','accessoire']) ? $data['type'] : 'neuf',
            ':etat'        => !empty($data['etat']) ? $data['etat'] : null,
            ':batterie'    => !empty($data['batterie']) ? intval($data['batterie']) : null,
            ':garantie'    => !empty($data['garantie']) ? $data['garantie'] : null,
            ':stock'       => intval($data['stock'] ?? 0),
            ':image'       => !empty($data['image']) ? $data['image'] : null,
            ':id'          => intval($data['id']),
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE produits SET actif = 0 WHERE id = ?");
    $stmt->execute([intval($data['id'])]);
    echo json_encode(['success' => true]);
}
