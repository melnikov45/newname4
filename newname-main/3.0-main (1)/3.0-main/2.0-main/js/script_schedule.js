//const currentDateDate = new Date();
    
// Получаем текущий год и месяц
//const year = currentDateDate.getFullYear();
//const month = String(currentDateDate.getMonth() + 1).padStart(2, '0'); // Месяцы начинаются с 0
    

/*var button = document.getElementById("link-button"); 
button.addEventListener("click", () => {
	document.addEventListener('mousemove', e => {
		Object.assign(document.documentElement, {
			style: `
			--move-x: 0;
			--move-y: 0;
			`
		})
	})
});

span.addEventListener("click", () => {
	document.addEventListener('mousemove', e => {
		Object.assign(document.documentElement, {
			style: `
			--move-x: ${(e.clientX - window.innerWidth / 2) * -.005}deg;
			--move-y: ${(e.clientY - window.innerHeight / 2) * .01}deg;
			`
		})
	})
});*/



//скрип нажатия на ячейки
/*var tds = document.querySelectorAll('td');
var modal2 = document.getElementById('myModal2');
var span2 = document.getElementsByClassName("close2")[0];
for (var i = 0; i < tds.length; i++) {
		tds[i].onclick = function() { 
			modal2.style.display = "block";
		}
		
		span2.onclick = function() {
			modal2.style.display = "none";
		}
	};	*/	
		
		/*var input = document.createElement('input');
		input.value = this.innerHTML;
		this.innerHTML = '';
		this.appendChild(input);
		
		var td = this;
		input.addEventListener('blur', function() {
			td.innerHTML = this.value;
			td.addEventListener('click', func);
		});
		
		this.removeEventListener('click', func);*/

/* работы с данными в таблицу */
// Функция для сохранения данных в Local Storage



/*let selectedCell = null; // Переменная для хранения выбранной ячейки

// Добавляем обработчик событий на каждую ячейку таблицы
const cells = document.querySelectorAll('#data-table td');
cells.forEach(cell => {
    cell.addEventListener('click', function() {
        selectedCell = cell; // Сохраняем ссылку на выбранную ячейку
        // Убираем выделение с остальных ячеек (если нужно)
        cells.forEach(c => c.style.backgroundColor = ''); // Сбрасываем цвет
        cell.style.backgroundColor = '#e0e0e0'; // Выделяем выбранную ячейку
    });
});

// Обработчик события для кнопки обновления
document.getElementById('submit-button').addEventListener('click', function() {
    const selectElement = document.getElementById('value-select');
    const selectedValue = selectElement.value;

    if (selectedCell && selectedValue) {
        selectedCell.textContent = selectedValue; // Обновляем содержимое выбранной ячейки
        selectElement.selectedIndex = 0; // Сбрасываем выбор в select
        selectedCell.style.backgroundColor = ''; // Убираем выделение
        selectedCell = null; // Сбрасываем выбранную ячейку
    } else {
        alert("Пожалуйста, выберите ячейку и значение для обновления.");
    }
});*/

	// Находим ячейку по id
	/*const cellToUpdate = document.getElementById('cell_1');
  
	if (cellToUpdate) {
	  // Если ячейка найдена, обновляем ее значение
	  cellToUpdate.textContent = selectedValue;
	} else {
	}
	});*/
