<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Расписание</title>
    <link rel="stylesheet" href="..\css\style_schelude.css" />
    <script src="..\js\script_schedule.js" defer></script>
    <!--<script src="..\libs\rain.js" defer></script>-->
    <!-- Unicons -->

  </head>
  <body>
    <!-- Header -->
    <section class="layers">
      <div class="layers__container">
        <div class="layers__item layer-1" style="background-image: url(../img/layer-1.jpg);"></div>
		<div class="layers__item layer-2" style="background-image: url(../img/layer-2.png);"></div>
        <div class="layers__item layer-3">
          <div class="hero-content">
              <a href="../admin.html" class="nav_link">Администратор</a>                           
              <a href="#" class="nav_link_2">Наряды</a>
              <a href="#" class="nav_link_2">ПМК</a>
              <a href="#" class="nav_link_2">История кафедры</a>
            </div>
            <style>
              .weekend {
                  background-color: #0d9f9c; /* Цвет для выходных (суббота и воскресенье) */
              }
              .date-picker {
                  display: flex;
                  position: fixed;
                  align-items: center;
                  margin-left: 800px;
                  margin-bottom: 410px;
              }
              .date-picker select {
                  margin: 0 5px;
                  margin: 35px;
              } 
          </style>
      </head>
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

// Функция для получения данных из таблицы
function getOptions($dbconn, $query) {
    $result = pg_query($dbconn, $query);
    if (!$result) {
        return []; // Возвращаем пустой массив в случае ошибки
    }
    $options = [];
    while ($row = pg_fetch_assoc($result)) {
        $options[] = $row; // Добавляем каждую строку результата в массив
    }
    pg_free_result($result); // Освобождаем память
    return $options; // Возвращаем массив с данными
}

// Получение данных для выпадающих списков
$disciplines = getOptions($dbconn, "SELECT title FROM courses"); // Замените на вашу таблицу
$classrooms = getOptions($dbconn, "SELECT name FROM rooms"); // Замените на вашу таблицу
$lessonTypes = getOptions($dbconn, "SELECT alias FROM eventtools"); // Замените на вашу таблицу
$groups = getOptions($dbconn, "SELECT name FROM  groupname"); // Замените на вашу таблицу

// Инициализация переменных
$groups_name_1 = [];
$users_1 = [];
$groups_name_2 = [];
$users_2 = [];
$groups_name_3 = [];
$users_3 = [];
$groups_name_4 = [];
$users_4 = [];

// Получение данных для ПМК 1
$result = pg_query($dbconn, "SELECT * FROM pmk WHERE id_pmk = 1");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $groups_name_1[] = $row;
    }
}

$result = pg_query($dbconn, "SELECT * FROM people WHERE mid = 1");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $users_1[] = $row;
    }
}

$result = pg_query($dbconn, "SELECT * FROM people WHERE mid = 2");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $users_1[] = $row;
    }
}

// Получение данных для ПМК 2
$result = pg_query($dbconn, "SELECT * FROM pmk WHERE id_pmk = 2");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $groups_name_2[] = $row;
    }
}

$result = pg_query($dbconn, "SELECT * FROM people WHERE mid = 3");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $users_2[] = $row;
    }
}

$result = pg_query($dbconn, "SELECT * FROM people WHERE mid = 4");
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $users_2[] = $row;
    }
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

// Повторите для ПМК 2, 3 и 4...
// Закрытие соединения
pg_close($dbconn);
?>

<div class="checkbox-all">
    <div class="checkbox-group">
        <h3>ПМК 1</h3>
        <?php foreach ($groups_name_1 as $group): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_1" id="checkbox_<?php echo $group['id_pmk']; ?>" value="<?php echo $group['name_pmk']; ?>"/>
                <label for="checkbox_<?php echo $group['id_pmk']; ?>"><?php echo htmlspecialchars($group['name_pmk']); ?></label>
            </div>
        <?php endforeach; ?>
        
        <h4>Фамилии:</h4>
        <?php foreach ($users_1 as $user): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_2" id="my_checkbox_<?php echo $user['mid']; ?>" value="<?php echo htmlspecialchars($user['lastname'] . ' ' . $user['firstname'] . ' ' . $user['patronymic']); ?>"/>
                <label for="my_checkbox_<?php echo $user['mid']; ?>">
                <?php echo htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['patronymic']); ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="checkbox-group">
        <h3>ПМК 2</h3>
        <?php foreach ($groups_name_2 as $group): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_1" id="checkbox_<?php echo $group['id_pmk']; ?>" value="<?php echo $group['name_pmk']; ?>"/>
                <label for="checkbox_<?php echo $group['id_pmk']; ?>"><?php echo htmlspecialchars($group['name_pmk']); ?></label>
            </div>
        <?php endforeach; ?>
        
        <h4>Фамилии:</h4>
        <?php foreach ($users_2 as $user): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_2" id="my_checkbox_<?php echo $user['mid']; ?>" value="<?php echo htmlspecialchars($user['lastname'] . ' ' . $user['firstname'] . ' ' . $user['patronymic']); ?>"/>
                <label for="my_checkbox_<?php echo $user['mid']; ?>">
                    <?php echo htmlspecialchars($user['lastname']) . ' ' . htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['patronymic']); ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>\\

<script>
    document.querySelectorAll('.my-checkbox_1').forEach(function(groupCheckbox) {
        groupCheckbox.addEventListener('change', function() {
            // Получаем все чекбоксы пользователей в текущей группе
            const userCheckboxes = groupCheckbox.closest('.checkbox-group').querySelectorAll('.my-checkbox_2');
            userCheckboxes.forEach(function(userCheckbox) {
                userCheckbox.checked = groupCheckbox.checked; // Устанавливаем состояние чекбоксов пользователей
            });
        });
    }); 
</script>

    <div class="checkbox-group">
        <?php foreach ($groups_name_3 as $group): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_1" id="checkbox_<?php echo $group['id_pmk']; ?>" value="<?php echo $group['name_pmk']; ?>"/>
                <label for="checkbox_<?php echo $group['id_pmk']; ?>"><?php echo htmlspecialchars($group['name_pmk']); ?></label>
            </div>
        <?php endforeach; ?>
        
        <?php foreach ($users_3 as $user): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_2" id="my_checkbox_<?php echo $user['mid']; ?>" value="<?php echo $user['lastname']; ?>"/>
                <label for="my_checkbox_<?php echo $user['mid']; ?>"><?php echo htmlspecialchars($user['lastname']); ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="checkbox-group">
        <?php foreach ($groups_name_4 as $group): ?>
            <div class="checkbox-item">
            <input type="checkbox" class="my-checkbox_1" id="checkbox_<?php echo $group['id_pmk']; ?>" value="<?php echo $group['name_pmk']; ?>"/>
                <label for="checkbox_<?php echo $group['id_pmk']; ?>"><?php echo htmlspecialchars($group['name_pmk']); ?></label>
            </div>
        <?php endforeach; ?>
        
        <?php foreach ($users_4 as $user): ?>
            <div class="checkbox-item">
                <input type="checkbox" class="my-checkbox_2" id="my_checkbox_<?php echo $user['mid']; ?>" value="<?php echo $user['lastname']; ?>"/>
                <label for="my_checkbox_<?php echo $user['mid']; ?>"><?php echo htmlspecialchars($user['lastname']); ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
          <div class="date-picker">
            <select id="month"></select>
            <select id="year"></select>
            <button class="button" id="link-button">Сформировать расписание</button>
        </div>
        
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Расписание</h2>
                    <p id="selected-date"></p>
                </div>
                <div class="modal-body">
                    <table id="data-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Звание, фамилия</th>
                                <th rowspan="2">Часы занятий</th>
                                <th colspan="31">Дни месяца</th>
                            </tr>
                            <tr id="day-headers"></tr>
                        </thead>
                        <tbody id="table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

<div id="myModal2" class="modal2">
    <div class="modal-content2">
        <div class="modal-header2">
            <span class="close2">&times;</span>
            <h2 style="text-align: center;">Внести изменения</h2>
        </div>
        <div class="select_choice">
            <form>
                <label for="name-select">Введите название дисциплины:</label>
                <select id="name-select" name="discipline">
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?php echo htmlspecialchars($discipline['title']); ?>">
                            <?php echo htmlspecialchars($discipline['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <form>
                <label for="age-select">Введите номер кабинета:</label>
                <select id="age-select" name="classroom">
                    <?php foreach ($classrooms as $classroom): ?>
                        <option value="<?php echo htmlspecialchars($classroom['name']); ?>">
                            <?php echo htmlspecialchars($classroom['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <form>
                <label for="city-select">Введите тип занятия:</label>
                <select id="city-select" name="lesson_type">
                    <?php foreach ($lessonTypes as $lessonType): ?>
                        <option value="<?php echo htmlspecialchars($lessonType['alias']); ?>">
                            <?php echo htmlspecialchars($lessonType['alias']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <form>
                <label for="country">Введите название учебной группы:</label>
                <select id="country" name="group">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo htmlspecialchars($group['name']); ?>">
                            <?php echo htmlspecialchars($group['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <input id="submit-button" type="button" value="Подтвердить">
            <input id="delete-button" type="button" value="Удалить">
        </div>
    </div>
</div>
        
        <script>
    // Заполнение выпадающих списков для месяцев и годов
    const monthSelect = document.getElementById('month');
    const yearSelect = document.getElementById('year');

    const months = [
        "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
    ];

    const currentYear = new Date().getFullYear();
    for (let year = currentYear - 5; year <= currentYear; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    months.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index + 1; // Месяцы от 1 до 12
        option.textContent = month;
        monthSelect.appendChild(option);
    });

    document.getElementById('link-button').addEventListener('click', function() {
        const selectedMonth = parseInt(monthSelect.value) - 1; // Месяцы от 0 до 11
        const selectedYear = parseInt(yearSelect.value);

        // Получаем количество дней в выбранном месяце
        const daysInMonth = new Date(selectedYear, selectedMonth + 1, 0).getDate();

        // Обновляем заголовки дней
        const dayHeadersRow = document.getElementById('day-headers');
        dayHeadersRow.innerHTML = ''; // Очищаем предыдущие заголовки

        for (let day = 1; day <= daysInMonth; day++) {
            const th = document.createElement('th');
            th.textContent = day;
            dayHeadersRow.appendChild(th);
        }

        // Очищаем тело таблицы
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = ''; // Очищаем предыдущие строки

        // Получаем выбранные чекбоксы пользователей
        const selectedUsers = Array.from(document.querySelectorAll('.my-checkbox_2:checked')).map(checkbox => checkbox.value);

        // Генерация строк таблицы для каждого выбранного пользователя
        // Генерация строк таблицы для каждого выбранного пользователя
selectedUsers.forEach(userName => {
    const user = {
        fullName: userName, // Используем полное имя
        hours: ["1 - 2", "3 - 4", "5 - 6", "7 - 8"],
        subjects: [["ТАД1", "АСУ2", "АСУ3", "АСУ4"], ["", "", "АСУ8", "АСУ9"], ["", "", "АСУ13", "АСУ14"], ["", "", "АСУ18", "АСУ19"]]
    };

    user.hours.forEach((hour, index) => {
        const row = document.createElement('tr');
        if (index === 0) {
            const nameCell = document.createElement('td');
            nameCell.rowSpan = user.hours.length; // Объединяем ячейку для имени пользователя 
            nameCell.textContent = user.fullName; // Заполняем ячейку полным именем
            row.appendChild(nameCell);
        }
        const hourCell = document.createElement('td');
        hourCell.textContent = hour;
        row.appendChild(hourCell);

        // Добавляем предметы в ячейки
        user.subjects[index].forEach((subject, dayIndex) => {
            const subjectCell = document.createElement('td');
            subjectCell.textContent = subject || ""; // Если предмета нет, оставляем пустую ячейку

            // Определяем, является ли день выходным
            const dayOfMonth = dayIndex + 1; // День месяца
            const date = new Date(selectedYear, selectedMonth, dayOfMonth);
            const dayOfWeek = date.getDay(); // 0 - воскресенье, 6 - суббота

            // Если день - суббота или воскресенье, добавляем класс
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                subjectCell.classList.add('weekend');
            }

            row.appendChild(subjectCell);
        });

        tableBody.appendChild(row);
    });
});

        // Обновляем выбранную дату
        document.getElementById('selected-date').innerText = 'Выбранный месяц: ' + months[selectedMonth] + ' ' + selectedYear;

        // Открываем модальное окно
        var modal = document.getElementById('myModal');
        modal.style.display = "block";

        // Добавляем обработчик клика для ячеек таблицы
        const tds = document.querySelectorAll('#data-table tbody td');
        tds.forEach((td) => {
            td.onclick = function() {
                // Получаем индекс столбца
                const columnIndex = td.cellIndex;

                // Проверяем, является ли клик по столбцам "Звание, фамилия" (0) или "Часы занятий" (1)
                if (columnIndex === 0 ) {
                    return; // Игнорируем клик по этим столбцам
                }

                    // Проверяем, является ли ячейка "1 - 2" (например, если это первая ячейка в строке)
                if (td.textContent.trim() === "1 - 2") {
                     return; // Игнорируем клик по ячейке с "1 - 2"
    }

                // Если клик по ячейкам с данными
                selectedCell = td; // Сохраняем ссылку на выбранную ячейку
                // Убираем выделение с остальных ячеек
                tds.forEach(c => c.style.backgroundColor = ''); // Сбрасываем цвет
                td.style.backgroundColor = 'blueviolet'; // Выделяем выбранную ячейку
                document.getElementById('myModal2').style.display = "block"; // Открываем myModal2
            }
        });
    });

    const span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        document.getElementById('myModal').style.display = "none"; // Закрываем myModal
    }

    // Обработчик клика для закрытия myModal2
    const span2 = document.getElementsByClassName("close2")[0];
    if (span2) {
        span2.onclick = function() {
            document.getElementById('myModal2').style.display = "none"; // Закрываем myModal2
        }
    }

    // Закрытие myModal2 при клике вне его
    window.onclick = function(event) {
        const modal2 = document.getElementById('myModal2');
        if (event.target == modal2) {
            modal2.style.display = "none"; // Закрываем myModal2
        }
    }

    // Обработчик для чекбоксов групп
    document.querySelectorAll('.my-checkbox_1').forEach(function(groupCheckbox) {
        groupCheckbox.addEventListener('change', function() {
            // Получаем все чекбоксы пользователей в текущей группе
            // Получаем все чекбоксы пользователей в текущей группе
            const userCheckboxes = groupCheckbox.closest('.checkbox-group').querySelectorAll('.my-checkbox_2');
            userCheckboxes.forEach(function(userCheckbox) {
                userCheckbox.checked = groupCheckbox.checked; // Устанавливаем состояние чекбоксов пользователей
            });
        });
    });

    document.getElementById('submit-button').addEventListener('click', function() {
    const nameSelect = document.getElementById('name-select');
    const ageSelect = document.getElementById('age-select');
    const citySelect = document.getElementById('city-select');
    const groupSelect = document.getElementById('country');

    // Получаем значения из select
    const selectedName = nameSelect.value;
    const selectedRoom = ageSelect.value;
    const selectedType = citySelect.value;
    const selectedGroup = groupSelect.value;

    // Проверяем, выбрана ли ячейка
    if (selectedCell) {
        // Создаем HTML для вывода данных в столбик
        const combinedValue = `
            ${selectedName || "Не указано"}<br>
            ${selectedRoom || "Не указано"}<br>
            ${selectedType || "Не указано"}<br>
            ${selectedGroup || "Не указано"}
        `; // Используем <br> для переноса строк

        // Обновляем содержимое выбранной ячейки
        selectedCell.innerHTML = combinedValue; // Записываем объединённое значение в ячейку

        // Отправляем данные на сервер
        fetch('save_schedule.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'name': selectedName,
                'room': selectedRoom,
                'type': selectedType,
                'group': selectedGroup
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Данные успешно сохранены!');
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });

        // Сбрасываем выделение
        selectedCell.style.backgroundColor = ''; // Убираем выделение
        selectedCell = null; // Сбрасываем выбранную ячейку

        // Закрываем модальное окно для внесения изменений
        document.getElementById('myModal2').style.display = "none";
    } else {
        alert("Пожалуйста, выберите ячейку для обновления.");
    }
});

document.getElementById('delete-button').addEventListener('click', function() {
    if (selectedCell) {
        selectedCell.textContent = ''; // Очищаем содержимое выбранной ячейки
        selectedCell.style.backgroundColor = ''; // Убираем выделение
        selectedCell = null; // Сбрасываем выбранную ячейку
        document.getElementById('myModal2').style.display = "none"; // Закрываем модальное окно
    } else {
        alert("Пожалуйста, выберите ячейку для удаления.");
    }
});
</script>

      </div>
    </div>
  </div>
    </section>
  </body>
</html>
    