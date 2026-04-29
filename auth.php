<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';
require_once '../config/session.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'check') {
    echo json_encode(['logged_in' => isAdminLoggedIn()]);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($username) || empty($password)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Identifiants manquants']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    // Vérifier le mot de passe (bcrypt ou texte simple pour faciliter le dev)
    $valid = false;
    if ($admin) {
        if (password_verify($password, $admin['password'])) {
            $valid = true;
        } elseif ($password === 'mdp123456' && $username === 'admin') {
            // fallback dev
            $valid = true;
        }
    }

    if ($valid) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        echo json_encode(['success' => true]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Identifiants incorrects']);
    }
}
