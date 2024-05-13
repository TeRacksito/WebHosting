// Este script se encarga de realizar una validación más avanzada de los campos del formulario de registro.
// Además de mostrar visualmente si todos los campos son válidos.

// Creamos una constante para cada campo de contraseña del formulario.

const pass1Field = document.getElementById('pass1Field'); // document representa el documento HTML actual.
const pass2Field = document.getElementById('pass2Field');

// Añadimos un evento de tipo input a cada campo de contraseña para que se ejecute la función validatePasswords cada vez que se introduzca un valor en ellos.
// Cada vez que se cambia el valor de uno de los campos de contraseña, se ejecuta la función validatePasswords.

pass1Field.addEventListener('input', validatePasswords);
pass2Field.addEventListener('input', validatePasswords);

// La función validatePasswords se encarga de comprobar si el valor de los dos campos de contraseña es el mismo.

// Hemos creado constantes para los campos de contraseña para poder acceder a sus valores 
// independientemente de qué campo de contraseña haya cambiado.
// Nótese que esto no lo hemos hecho con el resto de campos ni validaciones, ya que no es necesario.

function validatePasswords() {
    if (pass1Field.value === pass2Field.value) {
        pass2Field.setCustomValidity('');
    } else {
        pass2Field.setCustomValidity('La contraseña no coincide');
    }
}

// Creamos constantes para el botón de envío y para todos los campos de entrada del formulario.

const submitButton = document.getElementById('submitButton');
const inputs = document.querySelectorAll('.input-data>input'); // Aquí están todos los campos de entrada del formulario, incluidos los campos de contraseña.

// Foreach es un método parecido a un bucle for que recorre todos los elementos de un array y ejecuta una función para cada uno de ellos.
// La función se define entre paréntesis precedida de una flecha =>.

inputs.forEach(input => {

    // Solo para el campo de contraseña 2, añadimos un evento de tipo input que se ejecuta cada vez que se introduce un valor en el campo.
    if (input.id !== 'pass2Field') {
        input.addEventListener('input', validateField);
    }

    // Para todos los campos de entrada, añadimos un evento de tipo input que se ejecuta cada vez que se introduce un valor en el campo.
    input.addEventListener('input', validateButton);
});

const validCharacters = /^[a-zA-Z0-9ñÑ]+$/; // Expresión regular que solo permite letras, números y la letra ñ.

// La función validateField se encarga de comprobar si el valor introducido en un campo de entrada contiene caracteres no válidos.
function validateField(event) {
    if (!validCharacters.test(event.target.value)) {
        event.target.setCustomValidity('Caracteres no válidos. Solo se permiten letras y números');
    } else {
        event.target.setCustomValidity('');
    }
}

// La función validateButton se encarga de comprobar si todos los campos de entrada son válidos.
// Comprueba si alguno de los campos de entrada no es válido y, en ese caso, deshabilita el botón de envío.

// Más que deshabilitar el botón de envío, lo que hace es añadir o eliminar un atributo 'valid' al botón de envío, 
// lo que permite cambiar su estilo visual mediante CSS.

function validateButton() {
    // Esto es una práctica común en JavaScript moderno. Forma parte del paradigma de programación funcional.
    // En vez de usar un bucle for, se usa el método map para recorrer todos los elementos de un array y ejecutar una función para cada uno de ellos.
    // checkValidity es un método propio de los campos de entrada que devuelve true si el valor del campo es válido y false si no lo es.
    // Lo que determina si es válido o no es su atributo "setCustomValidity", que se establece en la función validateField.
    let isValid = [...inputs].map(input => input.checkValidity()).includes(false) ? false : true;

    if (isValid) {
        // Si todos los campos son válidos, añadimos el atributo 'valid' al botón de envío.
        submitButton.setAttribute('valid', '');
    } else {
        // Si alguno de los campos no es válido, eliminamos el atributo 'valid' del botón de envío.
        submitButton.removeAttribute('valid');
    }
}

