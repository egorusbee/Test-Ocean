<?php
session_start();
require_once 'config.php';

$is_admin = ($_SESSION['username'] === 'copp');

// Проверяем, авторизован ли администратор
if (!$is_admin) {
    header("Location: login.php");
    exit();
}


// Проверяем, получены ли необходимые данные
if (isset($_POST['application_id'], $_POST['new_status'])) {
    $applicationId = $_POST['application_id'];
    $newStatus = $_POST['new_status'];

    // Обновляем статус заявки в базе данных
    $stmt = $pdo->prepare("UPDATE applications SET type_status = :new_status WHERE id = :application_id");
    $stmt->execute(['new_status' => $newStatus, 'application_id' => $applicationId]);

    // Перенаправляем на страницу администратора
    header("Location: admin_panel.php");
    exit();
} else {
    // Если не получены необходимые данные, перенаправляем на главную страницу
    header("Location: index.php");
    exit();
}
?>