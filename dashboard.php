<?php
require_once 'config.php';

session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Получаем данные о пользователе
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE u_user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверяем, является ли пользователь администратором
$is_admin = ($_SESSION['username'] === 'copp');

// Обработка отправки новой заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_admin) {
    $car_number = $_POST['car_number'];
    $violation_description = $_POST['violation_description'];

    // Проверяем, был ли уже отправлен POST-запрос
    $already_submitted = isset($_POST['submitted']);

    if (preg_match("/^[АВЕКМНОРСТУХ]\d{3}(?<!000)[АВЕКМНОРСТУХ]{2}\d{2,3}$/ui", $car_number)) 
    {
        if (!$already_submitted) 
        {
            // Выполняем вставку данных и помечаем, что POST-запрос отправлен
            $stmt = $pdo->prepare("INSERT INTO applications (user_id, car_number, violation_description, type_status) VALUES (?, ?, ?, 'Новая заявка')");
            $stmt->execute([$user_id, $car_number, $violation_description]);
            $_POST['submitted'] = true;
            header("Location: dashboard.php");
        }
    }


    //
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white">
        <h1>Заявки</h1>
        <nav>
            <ul>
                <li><a href="index.php">Домашняя страница</a></li>
                <?php if (!$is_admin): ?>
                    <li><a href="logout.php">Выйти</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="main-content">
        <h2>Добро пожаловать, <?php echo $user['full_name']; ?>!</h2>

        <?php if (!$is_admin): ?>
            <form method="post">
                <div class="form-group">
                    <label for="car_number">Номер машины:</label>
                    <input id="car_number" type="text" class="form-control" name="car_number" onchange="inputHandler()" required>
                </div>
                <div class="form-group">
                    <label for="violation_description">Описание:</label>
                    <textarea class="form-control" name="violation_description" required></textarea>
                </div>
                    
                <button type="submit" class="btn btn-primary">Потвердить</button>
            </form>
        <?php endif; ?>
        <p id="error_text" class="err_text_dash"></p>
        <h3>Ваши заявки</h3>
        <ul>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM applications WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($applications as $application) {
                echo "<li>{$application['car_number']} - {$application['violation_description']} - Статус: {$application['type_status']}</li>";
            }
            ?>
        </ul>
    </section>

    <!-- Bootstrap JS and dependencies (if needed) -->
    <script src="dashboard_validation.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>