<?php
require_once 'config.php';

session_start();

// Проверяем, авторизован ли пользователь и является ли он администратором
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['username'] !== 'copp') {
    header("Location: index.php");
    exit();
}

// Получаем все заявки
$stmt = $pdo->prepare("SELECT * FROM applications");
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
$is_admin = ($_SESSION['username'] === 'copp');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white">
        <div>
            <h1>Панель администратора</h1>
            <nav>
                <ul >
                    <li ><a href="index.php">Домашняя страница</a></li>
                    <li ><a href="logout.php">Выйти</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="main-content">
        <div class="container">
            <h2>Добро пожаловать, Администратор!</h2>

            <h3>Все заявки</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Идентификатор пользователя</th>
                        <th>Номер автомобиля</th>
                        <th>Описание нарушения</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM applications JOIN users ON applications.user_id = users.u_user_id ORDER BY CASE WHEN type_status LIKE '%Новая%' THEN 0 ELSE 1 END;");
                    $stmt->execute();
                    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($applications as $application) {
                        echo "<tr>
                        <td>{$application['id']}</td>
                        <td>{$application['car_number']}</td>
                        <td>{$application['violation_description']}</td>
                        <td>{$application['type_status']}</td>
                        ";
                        if($application['type_status'] == 'Новая заявка')
                        {
                            echo "<td>
                            <form action='change_status.php' method='post'>
                                <input type='hidden' name='application_id' value='{$application['id']}'>
                                
                                <select name='new_status' class='form-control'>
                                    <option value='Подтверждено' " . ($application['type_status'] == 'Подтверждено' ? 'selected' : '') . ">Подтверждено</option>
                                    <option value='Отклонено' " . ($application['type_status'] == 'Отклонено' ? 'selected' : '') . ">Отклонено</option>
                                </select>
                                <button type='submit' class='btn btn-primary'>Изменить</button>
                            </form>
                            </td>
                            </tr>";
                        }
                        else
                        {
                            echo "<td>
                            <form action='change_status.php' method='post'>
                                <input type='hidden' name='application_id' value='{$application['id']}'>
                                <p>{$application['type_status']}</p>
                            </form>
                            </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Bootstrap JS and dependencies (if needed) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>


