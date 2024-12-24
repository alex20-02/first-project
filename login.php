<?php
session_start();
require 'db.php'; // Подключение к базе данных

// Инициализация переменной для ошибок
$error = '';
$userRole = ''; // Инициализация переменной $userRole

// Обработка формы авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['login']; // Изменено на 'login'
    $password = $_POST['password'];

    // Пример проверки учетных данных (замените на вашу логику)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login AND password = :password");
    $stmt->execute(['login' => $user, 'password' => md5($password)]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['id'] = $user['id']; // Сохраняем ID пользователя в сессии
        $userRole = $user['role']; // Получаем роль пользователя из базы данных

        // Перенаправление в зависимости от роли
        if ($userRole == 'user') {
            header('Location: projects2.php');
            exit();
        } elseif ($userRole == 'admin') {
            header('Location: projects.php');
            exit();
        } else {
            // Логика для других ролей
            header('Location: other_page.php');
            exit();
        }
    } else {
        $error = 'Неверное имя пользователя или пароль.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <style>
        /* Ваши стили остаются без изменений */
        :root {
            --primary-color: #007BFF;
            --secondary-color: #0056b3;
            --text-color: #333;
            --light-grey: #f0f2f5;
            --border-color: #ddd;
            --input-border: #ccc;
            --error-color: red;
            --form-padding: 30px;
            --form-border-radius: 10px;
            --input-padding: 12px;
            --input-margin: 10px 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-grey);
            background-image: linear-gradient(to bottom right, #f0f2f5, #e6e8eb);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-y: auto;
        }
        h1 {
            color: var(--text-color);
            text-align: center;
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: var(--form-padding);
            border: 1px solid var(--border-color);
            border-radius: var(--form-border-radius);
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        img {
            max-width: 100%;
            max-height: 100px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: block;
            object-fit: contain;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: var(--input-padding);
            margin: var(--input-margin);
            border: 1px solid var(--input-border);
            border-radius: 5px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }
        button {
            width: 100%;
            padding: var(--input-padding);
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        button:hover {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .error {
            color: var(--error-color);
            text-align: center;
            margin-top: 10px;
            font-weight: 500;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <form method="post">
        <img src="17.jpg" alt="Логотип"> <!-- Добавлено изображение -->
        <h1>Авторизация</h1> <!-- Заголовок теперь внутри формы -->
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="text" name="login" placeholder="Имя пользователя" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>