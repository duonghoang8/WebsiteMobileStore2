<?php
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.html');
        exit();
    }
}

// Kiểm tra quyền admin
function checkAdmin() {
    checkAuth(); // Kiểm tra đăng nhập trước
    
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../../index.php');
        exit();
    }
}

// Lấy thông tin user hiện tại
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

// Kiểm tra có phải admin không (trả về boolean)
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Kiểm tra đã đăng nhập chưa (trả về boolean)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>