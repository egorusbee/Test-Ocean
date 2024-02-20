<?php
require_once 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Устанавливаем сессию
        $_SESSION['user_id'] = $user['u_user_id'];
        $_SESSION['username'] = $user['username'];

        // Перенаправляем пользователя на dashboard.php
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head class="bg-dark text-white">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Авторизация</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" >
        <label for="username">Логин:</label>
        <input type="text" name="username" required><br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Авторизация</button>
    </form>
</body>
</html>
