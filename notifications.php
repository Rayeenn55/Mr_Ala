<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

// Nouvelles commandes non vues
$stmt = $pdo->query("SELECT COUNT(*) as count FROM commandes WHERE vu = 0");
$nouvelles = $stmt->fetch()['count'];

// Produits en alerte stock (stock <= 3 et actif)
$stmt2 = $pdo->query("SELECT id, marque, nom, stock FROM produits WHERE stock <= " . STOCK_ALERT . " AND stock > 0 AND actif = 1");
$alertes_stock = $stmt2->fetchAll();

// Produits en rupture totale
$stmt3 = $pdo->query("SELECT id, marque, nom FROM produits WHERE stock = 0 AND actif = 1");
$ruptures = $stmt3->fetchAll();

echo json_encode([
    'nouvelles_commandes' => intval($nouvelles),
    'alertes_stock'       => $alertes_stock,
    'ruptures'            => $ruptures,
], JSON_UNESCAPED_UNICODE);
