<?php
$host = "134.90.167.42:10306"; // Замените $host на $server
$user = "Pigaltsev";
$pass = "FczBIR"; // Замените $pw на $pass
$db = "project_Pigaltsev";

try {
    // Создание подключения с использованием PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>