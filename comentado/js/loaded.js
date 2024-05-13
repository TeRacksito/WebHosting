// Este script se encarga de esperar a que la página se haya cargado completamente para mostrar el contenido.
// Como es el primer script que se ejecuta, la siguiente línea es la primera de todas.

// Añadimos un evento de tipo DOMContentLoaded al documento HTML actual.
// Este evento se ejecuta cuando el documento HTML ha sido completamente cargado y parseado, sin esperar a que se carguen las imágenes y otros recursos.
document.addEventListener('DOMContentLoaded', function () {

    // Añadimos un evento de tipo load a la ventana actual.
    // Este evento se ejecuta cuando la ventana, incluyendo sus recursos, ha sido completamente cargada.
    window.addEventListener('load', function () {

        // Obtenemos el tipo de entrega de la página actual.
        // Esto nos permite saber si la página ha sido cargada desde la caché del navegador o desde el servidor.
        let deliveryType = performance.getEntriesByType("navigation")[0].deliveryType;
        if (deliveryType === "cache") {
            showPageFast();
        } else {

            // Si la página no ha sido cargada desde la caché, esperamos 2 segundos y mostramos el contenido.
            // Esto se hace para que el usuario pueda ver el spinner de carga durante un tiempo.
            // No es necesario esperar 2 segundos, lo hacemos para que se vea mejor. No es una buena práctica.
            this.setTimeout(showPage, 2000);
        }
    });
});

// La función showPage se encarga de mostrar el contenido de la página.

function showPage() {

    // Hacemos scroll hasta el principio de la página.
    window.scrollTo(0, 0);

    // Obtenemos el elemento con la clase loading.
    let loading = document.querySelector('.loading');

    // Ocultamos el elemento loading.
    loading.setAttribute('hidden', '');

    // Eliminamos el elemento loading después de 200 milisegundos.
    setTimeout(() => {
        loading.remove();
    }, 200);
}

// La función showPageFast se encarga de mostrar el contenido de la página rápidamente.
function showPageFast() {

    // Hacemos scroll hasta el principio de la página.
    window.scrollTo(0, 0);

    // Obtenemos el elemento con la clase loading y el elemento con la clase spinner.
    let loading = document.querySelector('.loading');
    let spinner = document.querySelector('.spinner');

    // Eliminamos el elemento spinner lo antes posible y ocultamos el elemento loading.
    spinner.remove();
    loading.setAttribute('hidden', '');

    // Eliminamos el elemento loading después de 200 milisegundos.
    setTimeout(() => {
        loading.remove();
    }, 200);
}