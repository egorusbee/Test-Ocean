<?php
require_once 'config.php';

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сообщить о нарушении</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white">
        <h1>Сообщить о нарушении</h1>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                    <?php if ($_SESSION['username'] != 'copp'): ?>
                        <li><a href="dashboard.php">Заявки</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['username'] === 'copp'): ?>
                        <li><a href="admin_panel.php">Панель админа</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Выйти</a></li>
                <?php else: ?>
                    <li><a href="register.php">Регистрация</a></li>
                    <li><a href="login.php">Авторизация</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="main-content">
        <h2>Добро пожаловать на нарушений.нет</h2>
        <p>Сообщайте о нарушениях правил дорожного движения и вносите вклад в повышение безопасности дорог.</p>
    </section>
</body>
</html>
