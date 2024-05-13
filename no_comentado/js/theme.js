var theme = "light";

if (localStorage.getItem("theme")) {
    if (localStorage.getItem("theme") == "dark") {
        var theme = "dark";
    }
} else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    var theme = "dark";
}

document.documentElement.setAttribute("data-theme", theme);

let toggleTheme = document.getElementById("toggle-theme");

function switchTheme(e) {
    if (e.target.checked) {
        localStorage.setItem('theme', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
        toggleTheme.checked = true;
    } else {
        localStorage.setItem('theme', 'light');
        document.documentElement.setAttribute('data-theme', 'light');
        toggleTheme.checked = false;
    }
}

toggleTheme.addEventListener('change', switchTheme, false);

if (document.documentElement.getAttribute("data-theme") == "dark") {
    toggleTheme.checked = true;
}