<?php
session_start();

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}
