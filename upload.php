<?php
// ── API : Upload image produit ──
header('Content-Type: application/json; charset=utf-8');

$uploadDir = '../uploads/produits/';

// Vérifier que le dossier existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Aucun fichier reçu']);
    exit;
}

$file     = $_FILES['image'];
$maxSize  = 5 * 1024 * 1024; // 5 MB max
$allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

// Vérifications
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Erreur upload: ' . $file['error']]);
    exit;
}
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'error' => 'Image trop lourde (max 5MB)']);
    exit;
}
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Format non supporté. Utilisez JPG, PNG ou WebP']);
    exit;
}

// Générer un nom unique
$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('prod_', true) . '.' . strtolower($ext);
$dest     = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'url'      => 'uploads/produits/' . $filename
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Impossible de sauvegarder le fichier']);
}
