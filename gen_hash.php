<?php
$plainPassword = 'admin123';
$hashFromDB = '$2y$10$n4BOrl1wSZgudzowuhKpieb1h1hjtVYapj5Q6kIj7QxXioSq1Q/FS';

if (password_verify($plainPassword, $hashFromDB)) {
    echo "Đúng mật khẩu!";
} else {
    echo "Sai mật khẩu!";
}
?>

