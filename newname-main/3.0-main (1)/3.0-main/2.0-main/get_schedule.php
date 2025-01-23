<?php
// Параметры подключения
$host = 'localhost'; // Хост (обычно localhost)
$port = '5432';      // Порт (по умолчанию 5432)
$dbname = 'names';   // Имя базы данных
$user = 'postgres';   // Имя пользователя
$password = '12345678'; // Пароль

// Создание строки подключения
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Подключение к базе данных
$dbconn = pg_connect($conn_string);

// Проверка подключения
if (!$dbconn) {
    die("Ошибка подключения к базе данных.\n");
}

// Выполнение SQL-запроса
$query = "
    SELECT 
        p.lastname AS person_lastname, 
        p.firstname AS person_firstname, 
        p.patronymic AS person_patronymic, 
        crs.cid AS course_id, 
        crs.alias AS course_alias, 
        r.rid AS room_id, 
        r.name AS room_name, 
        e.typeid AS event_type_id, 
        e.alias AS event_type_alias, 
        g.gid AS group_id, 
        g.name AS group_name
    FROM public.nnz_schedule AS nnz_s
    JOIN people AS p ON p.mid = 1
    JOIN courses AS crs ON crs.cid = nnz_s.cid
    JOIN rooms AS r ON r.rid = ANY(nnz_s.rid)
    JOIN eventtools AS e ON nnz_s.pair_type_id = e.typeid
    JOIN groupname AS g ON nnz_s.gid = g.gid
    WHERE 1 = ANY(nnz_s.teacher_mid) AND nnz_s.sh_var_id = 15 AND nnz_s.day_of_week = 1
";

$result = pg_query($dbconn, $query);
$data = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $data[] = $row; // Добавляем каждую строку результата в массив
    }
    pg_free_result($result); // Освобождаем память только если запрос успешен
} else {
    // Обработка ошибки запроса
    echo "Ошибка выполнения запроса: " . pg_last_error($dbconn);
}

// Закрытие соединения
pg_close($dbconn);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Расписание</title>
    <link rel="stylesheet" href="..\css\style_schelude.css" />
    <script src="..\js\script_schedule.js" defer></script>
</head>
<body>
    <table id="data-table">
        <thead>
            <tr>
                <th>Фамилия, Имя, Отчество</th>
                <th>Курс</th>
                <th>Комната</th>
                <th>Тип занятия</th>
                <th>Группа</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <!-- Данные будут добавлены здесь -->
        </tbody>
    </table>

    <script>
        const scheduleData = <?php echo json_encode($data); ?>; // Передаем данные в JavaScript

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('table-body'); // Получаем тело таблицы

            // Заполняем таблицу данными
            scheduleData.forEach(item => {
                const row = document.createElement('tr');

                // Создаем ячейки для каждой колонки
                const fullNameCell = document.createElement('td');
                fullNameCell.textContent = `${item.person_lastname} ${item.person_firstname} ${item.person_patronymic}`;
                row.appendChild(fullNameCell);

                const courseCell = document.createElement('td');
                courseCell.textContent = item.course_alias; // Псевдоним курса
                row.appendChild(courseCell);

                const roomCell = document.createElement('td');
                roomCell.textContent = item.room_name; // Название комнаты
                row.appendChild(roomCell);

                const eventTypeCell = document.createElement('td');
                eventTypeCell.textContent = item.event_type_alias; // Псевдоним типа события
                row.appendChild(eventTypeCell);

                const groupCell = document.createElement('td');
                groupCell.textContent = item.group_name; // Название группы
                row.appendChild(groupCell);

                // Добавляем строку в тело таблицы
                tableBody.appendChild(row);
            });
        });
    </script>
</body>
</html>