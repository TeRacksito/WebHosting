const pass1Field = document.getElementById('pass1Field');
const pass2Field = document.getElementById('pass2Field');

pass1Field.addEventListener('input', validatePasswords);
pass2Field.addEventListener('input', validatePasswords);

function validatePasswords() {
    if (pass1Field.value === pass2Field.value) {
        pass2Field.setCustomValidity('');
    } else {
        pass2Field.setCustomValidity('La contraseña no coincide');
    }
}

const submitButton = document.getElementById('submitButton');
const inputs = document.querySelectorAll('.input-data>input');

inputs.forEach(input => {
    if (input.id !== 'pass2Field') {
        input.addEventListener('input', validateField);
    }
    input.addEventListener('input', validateButton);
});

const validCharacters = /^[a-zA-Z0-9ñÑ]+$/;
function validateField(event) {
    if (!validCharacters.test(event.target.value)) {
        event.target.setCustomValidity('Caracteres no válidos. Solo se permiten letras y números');
    } else {
        event.target.setCustomValidity('');
    }
}

function validateButton() {
    let isValid = [...inputs].map(input => input.checkValidity()).includes(false) ? false : true;

    if (isValid) {
        submitButton.setAttribute('valid', '');
    } else {
        submitButton.removeAttribute('valid');
    }
}

