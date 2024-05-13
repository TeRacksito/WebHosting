// Este script se encarga de cambiar el tema de la página web entre claro y oscuro.

// Por defecto, el tema es claro.
var theme = "light";

// Si el tema está guardado en el almacenamiento local, lo usamos.

// JavaScript localStorage es un objeto que permite almacenar pares clave/valor en un navegador web.
// La información almacenada en localStorage no tiene fecha de caducidad y permanece después de cerrar el navegador.
if (localStorage.getItem("theme")) {
    if (localStorage.getItem("theme") == "dark") {
        var theme = "dark";
    }

    // Si el tema no está guardado en el almacenamiento local, comprobamos si el tema preferido por el usuario es oscuro.
} else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    var theme = "dark";
}

// Establecemos el tema en el documento HTML.
document.documentElement.setAttribute("data-theme", theme);

// Obtenemos el interruptor de tema.
let toggleTheme = document.getElementById("toggle-theme");

// Función para cambiar el tema.
function switchTheme(e) {

    // Si el interruptor está activado, establecemos el tema en oscuro y lo guardamos en el almacenamiento local.
    if (e.target.checked) {
        localStorage.setItem('theme', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
        toggleTheme.checked = true;

        // Si el interruptor no está activado, establecemos el tema en claro y lo guardamos en el almacenamiento local.
    } else {
        localStorage.setItem('theme', 'light');
        document.documentElement.setAttribute('data-theme', 'light');
        toggleTheme.checked = false;
    }
}

// Añadimos un evento de tipo change al interruptor de tema.

// El evento change se dispara cuando el valor de un elemento de formulario ha cambiado.
toggleTheme.addEventListener('change', switchTheme, false);

// Si el tema es oscuro, activamos el interruptor.
// Esto es útil para que el interruptor de tema esté activado si el tema oscuro está activado.
// Esto se hace para que el interruptor de tema refleje el tema actual de la página y evitar
// algunos casos en los que, al principio, el interruptor de tema no refleja el tema actual.
if (document.documentElement.getAttribute("data-theme") == "dark") {
    toggleTheme.checked = true;
}