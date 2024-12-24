<?php
session_start();
require 'db.php'; // Подключение к базе данных
// Инициализация переменной $projects
$projects = [];
try {
    // Получение всех проектов
    $stmt = $pdo->query("SELECT * FROM projects");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
// Проверка, была ли отправлена форма для добавления или редактирования проекта
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Добавление проекта
        $name = $_POST['name'];
        $description = $_POST['description'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $budget = $_POST['budget'];
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (name, description, start_date, end_date, budget) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $start_date, $end_date, $budget]);
            $_SESSION['message'] = "Проект успешно добавлен!";
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    } elseif (isset($_POST['edit'])) {
        // Редактирование проекта
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $budget = $_POST['budget'];
        try {
            $stmt = $pdo->prepare("UPDATE projects SET name = ?, description = ?, start_date = ?, end_date = ?, budget = ? WHERE id = ?");
            $stmt->execute([$name, $description, $start_date, $end_date, $budget, $id]);
            $_SESSION['message'] = "Проект успешно обновлен!";
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    }
}
// Проверка, нужно ли удалить проект
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['message'] = "Проект успешно удален!";
    } catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }
    header("Location: projects.php"); // Перенаправление на страницу проектов
    exit;
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
            padding: 20px;
            background-image: url('12.jpg'); /* Укажите путь к вашему изображению */
            background-size: cover; /* Покрытие всего экрана */
            background-position: center bottom; /* Центрирование фона по горизонтали и смещение вниз */
            background-repeat: no-repeat; /* Запрет повторения изображения */
            height: 100vh; /* Высота вьюпорта */
        }
        h1 {
            color: #333;
            text-align: center; /* Центрирование заголовка */
            background-color: rgba(255, 255, 255, 0.8); /* Полупрозрачный фон для заголовка */
            padding: 10px;
            border-radius: 5px; /* Закругленные углы */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid #007BFF; /* Толстая рамка для таблицы */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Более выраженная тень */
            border-radius: 8px; /* Закругленные углы таблицы */
            overflow: hidden; /* Скрыть переполнение для закругленных углов */
            background-color: rgba(255, 255, 255, 0.9); /* Полупрозрачный фон для таблицы */
            font-size: 1.5rem;
        }
        th, td {
            border: 1px solid #ddd; /* Обрамление для ячеек */
            padding: 12px; /* Увеличенные отступы для ячеек */
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold; /* Жирный шрифт для заголовков */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Четные строки с другим фоном */
        }
        tr:hover {
            background-color: #e0e0e0; /* Цвет при наведении на строку */
        }
        td a {
            color: #007BFF; /* Цвет ссылок */
            text-decoration: none; /* Убираем подчеркивание */
        }
        td a:hover {
            text-decoration: underline; /* Подчеркивание при наведении на ссылку */
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9); /* Полупрозрачный фон для формы */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Проекты</h1>
    <a href="#" onclick="document.getElementById('addForm').style.display='block'">Добавить проект</a>
    <div id="addForm" style="display:none;">
        <form method="POST">
            <input type="text" name="name" placeholder="Название проекта" required>
            <textarea name="description" placeholder="Описание проекта" required></textarea>
            <input type="date" name="start_date" placeholder="Дата начала" required>
            <input type="date" name="end_date" placeholder="Дата окончания" required>
            <input type="number" name="budget" placeholder="Бюджет" required>
            <button type="submit" name="add">Добавить проект</button>
        </form>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Дата начала</th>
            <th>Дата окончания</th>
            <th>Бюджет</th>
            <th>Действия</th>
        </tr>
        <?php if (empty($projects)): ?>
            <tr>
                <td colspan="7">Нет доступных проектов.</td>
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
                <td>
                    <a href="#" onclick="document.getElementById('editForm<?= $project['id'] ?>').style.display='block'">Редактировать</a>
                    <a href="projects.php?delete=<?= $project['id'] ?>" onclick="return confirm('Вы уверены, что хотите удалить этот проект?');">Удалить</a>
                </td>
            </tr>
            <tr id="editForm<?= $project['id'] ?>" style="display:none;">
                <td colspan="7">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $project['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
                        <textarea name="description" required><?= htmlspecialchars($project['description']) ?></textarea>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($project['start_date']) ?>" required>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($project['end_date']) ?>" required>
                        <input type="number" name="budget" value="<?= htmlspecialchars($project['budget']) ?>" required>
                        <button type="submit" name="edit">Обновить проект</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>