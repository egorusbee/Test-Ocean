<?php 
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{ 
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    $password = hash('sha256', $password);
    $full_name = $_POST['full_name']; 
    $phone = $_POST['phone']; $email = $_POST['email']; 
    if (!preg_match("/^[А-ЯЁ][а-яё]+$/u", $full_name)) 
    { 
		echo " <title>Регистрация</title> <div class=\"reg_error_cont\"><link rel=\"stylesheet\" href=\"styles.css\">";
        echo "<h1 class=\"reg_error\">Неверный формат ФИО! Должно состоять только из букв.</h1>";
        echo "<center><a class=\"reg_error\" href=\"/register.php\">Вернуться к регистрации</a><center>";
        exit();
    } 
    if (!preg_match('/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', $phone)) 
    {
		echo " <title>Регистрация</title> <div class=\"reg_error_cont\"><link rel=\"stylesheet\" href=\"styles.css\">";
		echo "<link rel=\"stylesheet\" href=\"styles.css\">";
        echo "<h1 class=\"reg_error\">Неверный формат Нормер телефона! Должно состоять только из букв.</h1>";
        echo "<center><a class=\"reg_error\" href=\"/register.php\">Вернуться к регистрации</a><center>";

        exit();
    } 
    if (strlen($password) < 6) 
    {
		echo " <title>Регистрация</title> <div class=\"reg_error_cont\"><link rel=\"stylesheet\" href=\"styles.css\">";
		echo "<link rel=\"stylesheet\" href=\"styles.css\">";
        echo "<h1 class=\"reg_error\">Пароль должен содержать не менее 6 символов!</h1>";
		echo "<center><a class=\"reg_error\" href=\"/register.php\">Вернуться к регистрации</a><center>";
        exit();
    } 
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, phone, email) VALUES (?, ?, ?, ?, ?)"); 
        $stmt->execute([$username, $password, $full_name, $phone, $email]);
    } catch (PDOException $e) {
        echo " <title>Регистрация</title> <div class=\"reg_error_cont\"><link rel=\"stylesheet\" href=\"styles.css\">";
		echo "<link rel=\"stylesheet\" href=\"styles.css\">";
        echo "<h1 class=\"reg_error\">Такой пользователь уже существует!</h1>";
		echo "<center><a class=\"reg_error\" href=\"/register.php\">Вернуться к регистрации</a><center>";
        exit();
    }
	header("Location: login.php");
	exit(); 
} ?> 
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Регистрация</title> 
    <nav> 
        <ul> 
            <li><a href="index.php">Домашняя страница</a></li> 
        </ul> 
    </nav> <link rel="stylesheet" href="styles.css"> 
</head> 
<body>
    <h1>Регистрация</h1> 
    <form method="post">
        <label for="username">Логин:</label> 
        <input type="text" name="username" required><br> 
        <label for="password">Пароль:</label> 
        <input type="password" name="password" required><br>
        <label for="full_name">Полное имя:</label> 
        <input type="text" name="full_name" required><br> 
        <label for="phone">Номер телефона:</label>
        <input type="tel" name="phone" required><br> 
        <label for="email">Email:</label>
        <input type="email" name="email" required><br> 
        <button type="submit">Зарегистрироваться</button> 
    </form> 
</body> 
</html>