<?php
// Параметры подключения
$host = 'localhost';
$port = '5432';
$dbname = 'names'; // Убедитесь, что имя базы данных правильное
$user = 'postgres';
$password = '12345678';

// Создание строки подключения
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Подключение к базе данных
$dbconn = pg_connect($conn_string);

// Проверка подключения
if (!$dbconn) {
    die("Ошибка подключения к базе данных.\n");
}

// Получение данных из POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $room = $_POST['room'] ?? '';
    $type = $_POST['type'] ?? '';
    $group = $_POST['group'] ?? '';

    // Запись данных в базу данных
    $query = "INSERT INTO schedule (name, room, type, group_name) VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($dbconn, $query, array($name, $room, $type, $group));

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при записи в базу данных.']);
    }
}

// Закрытие соединения
pg_close($dbconn);
?>