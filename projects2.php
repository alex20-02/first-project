<?php
// projects.php
session_start();
require 'db.php';
// Инициализация переменной $projects
$projects = [];
try {
    // Получение всех проектов
    $stmt = $pdo->query("SELECT * FROM projects");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление проектами</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('orig.jpg'); /* Укажите URL вашего фонового изображения */
    background-size: cover;
    background-position: center bottom;
    background-repeat: no-repeat;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

h1 {
    color: #333;
    text-align: center;
    margin-top: 20px;
    font-size: 2em; /* Увеличенный размер заголовка */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border: 2px solid #007BFF;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    background-color: white;
    font-size: 25px; /* Увеличен базовый размер шрифта таблицы */
}

th,
td {
    border: 1px solid #ddd;
    padding: 14px; /* Увеличенный отступ */
    text-align: left;
    font-size: 1.5rem; /* Увеличен размер шрифта в ячейках */
    line-height: 1.4; /* Межстрочный интервал для лучшей читаемости */
}

th {
    background-color: #007BFF;
    color: white;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e0e0e0;
}

td a {
    color: #007BFF;
    text-decoration: none;
    font-size: 3rem; /* Размер шрифта для ссылок */
}

td a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
    <h1>Проекты</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Дата начала</th>
            <th>Дата окончания</th>
            <th>Бюджет</th>
        </tr>
        <?php if (empty($projects)): ?>
            <tr>
                <td colspan="6">Нет доступных проектов.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= htmlspecialchars($project['id']) ?></td>
                <td><?= htmlspecialchars($project['name']) ?></td>
                <td><?= htmlspecialchars($project['description']) ?></td>
                <td><?= htmlspecialchars($project['start_date']) ?></td>
                <td><?= htmlspecialchars($project['end_date']) ?></td>
                <td><?= htmlspecialchars($project['budget']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>