document.addEventListener('mousemove', e => {
	Object.assign(document.documentElement, {
		style: `
		--move-x: ${(e.clientX - window.innerWidth / 2) * -.005}deg;
		--move-y: ${(e.clientY - window.innerHeight / 2) * .01}deg;
		`
	})
})
var button = document.getElementById("link-button");
button.addEventListener("click", function() { 
  // Перейдите на вторую страницу при нажатии кнопки 
  window.location.href = "main.html"; 
  }); 