<!-- Este archivo se encarga de crear un nuevo usuario en el servidor.
     Es el backend de la página webhosting. -->

<?php
// Recogemos los datos del formulario que se envían por POST.
$name = $_POST['name'];
$surname = $_POST['surname'];
$password = $_POST['password'];

// Las funciones checkLength y checkCharacters comprueban que los campos introducidos.
// Puedes verlas más abajo.
// Todos los campos deben cumplir con las condiciones de longitud y caracteres.
// Si no se cumplen, redirigimos a la página de error.

// Si nos fijamos, esta es la segunda vez que se comprueba la validez de los campos.
// La primera vez se hace en el frontend, en el <form> del index.html.
// Esto se hace puesto que cualquier usuario puede modificar el código de la página web,
// y podría saltarse la comprobación del frontend. En realidad, el frontend es solo una
// ayuda para el usuario, pero la comprobación real se hace en el backend.

if (!(checkLength($name, 3, 20) && checkLength($surname, 3, 20) && checkLength($password, 6, 20))) {
    header("Location: /error.html");
    exit();
}

if (!(checkCharacters($name) && checkCharacters($surname) && checkCharacters($password))) {
    header("Location: /error.html");
    exit();
}

// Concatenamos el nombre y apellido para formar el nombre de usuario.

$userName = $name . "_" . $surname;

// Ejecutamos el comando "cat /etc/passwd | grep $userName" para comprobar si el usuario ya existe.

$output = runCommand("cat /etc/passwd | grep $userName", 1);

// Si el usuario no existe, lo creamos con el comando "useradd -M -N -g webuser $userName".
// El flag -M indica que no se cree el directorio home.
// El flag -N indica que no se cree un grupo con el mismo nombre que el usuario.
// El flag -g indica el grupo al que pertenecerá el usuario.
// En este caso, el grupo es "webuser". Este grupo no tiene permitido el acceso SSH (Muy importante).

$output = runCommand("useradd -M -N -g webuser $userName", 0);

// Añadimos la contraseña al usuario con el comando "echo $userName:$password | chpasswd".

$output = runCommand("echo $userName:$password | chpasswd", 0);



// Creamos el directorio home del usuario y le damos permisos con los comandos "mkdir -p $userHome",
// El flag -p indica que se creen los directorios padres si no existen, o si el propio directorio no existe, no se muestre error.

$userHome = "/var/www/html/$userName";

$output = runCommand("mkdir -p $userHome", 0);

// Cambiamos el propietario del directorio home con el comando "chown -R $userName $userHome".
// El flag -R indica que se cambie el propietario de forma recursiva, a todos los archivos y directorios dentro de $userHome.

$output = runCommand("chown -R $userName:webuser $userHome", 0);

// Cambiamos los permisos del directorio home con el comando "chmod 700 $userHome".
// Los permisos 700 indican que el propietario tiene permisos de lectura, escritura y ejecución.
// Los demás no tienen permisos (grupo y otros).

$output = runCommand("chmod 700 $userHome", 0);

// Cambiamos el directorio home del usuario con el comando "usermod -d $userHome $userName".
// Esto es necesario pues el servidor FTP encierra a los usuarios en su directorio home.
// (porque lo hemos configurado así en el archivo de configuración del servidor FTP).

$output = runCommand("usermod -d $userHome $userName", 0);

// Obtenemos la dirección con la que el servidor se comunica con el cliente.

$currentIP = $_SERVER['SERVER_ADDR'];

// Función que ejecuta un comando y comprueba si ha tenido éxito según el valor que se le pase.
// success_value es el valor que devuelve el comando si ha tenido éxito.

function runCommand($command, $success_value)
{
    $output = array(); // Creamos un array vacío que contendrá cada línea de la salida del comando.
    exec($command, $output, $return_var); // Ejecutamos el comando y guardamos la salida en $output y el valor de retorno en $return_var.
    if ($return_var != $success_value) { // Si el valor de retorno no es el esperado, redirigimos a la página de error.
        header("Location: /error.html"); // La función header() redirige a la página que se le pase.
        exit(); // Salimos del script para que no se ejecute más código.
    }
    return $output; // Devolvemos la salida del comando.
}

// Comprueba si un campo tiene una longitud entre $min y $max, ambos inclusive.

function checkLength($field, $min, $max)
{
    if (strlen($field) < $min) {
        return false;
    } else if (strlen($field) > $max) {
        return false;
    }
    return true;
}

// Comprueba si un campo contiene solo letras, números y la letra ñ.
// No queremos que los usuarios puedan introducir caracteres especiales.

function checkCharacters($field)
{
    if (preg_match('/^[a-zA-Z0-9ñÑ]+$/', $field)) {
        return true;
    }
    return false;
}
?>

<!-- En PHP, el código HTML se escribe directamente en el archivo, sin necesidad de abrir y cerrar etiquetas PHP.
     Esto se debe a que PHP es un lenguaje de programación embebido en HTML.
     
     Este documento HTML se muestra al usuario solo si se ha creado el usuario con éxito. -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usuario creado</title>
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <meta name="description" content="Author: Angel Krasimirov Stoyanov, in collaboration with Isidro.">
</head>

<body>
    <header>
        <h2>WEBHOSTING AUTOMÁTICO</h2>
        <nav>
            <img src="icon/Dark-theme-01.png" alt="" class="toggle-theme">
            <input type="checkbox" id="toggle-theme" class="toggle-theme">
        </nav>
    </header>
    <main>
        <article class="container">
            <section class="subcontainer title">
                <div class="icon-container">
                    <h1>¡Creado!</h1>

                    <!-- Este div contiene el icono de éxito.
                         Simplemente cambiando el nombre de las clases, se puede cambiar el icono. -->
                    <div class="success-icon">
                        <span class="stick-container"><span class="success-stick1 stick"></span></span>
                        <span class="stick-container"><span class="success-stick2 stick"></span></span>
                    </div>
                </div>
            </section>
            <section class="subcontainer">
                <p>
                    <!-- Con PHP, podemos imprimir variables directamente en el HTML.
                         Esto se hace con la sintaxis <\?php echo $variable; ?>.
                         En este caso, estamos imprimiendo el nombre de usuario que se ha creado.
                         Lo que se haya hecho en etiquetas php anteriormente, se puede imprimir en cualquier parte del HTML. -->
                    Se ha creado el usuario <span class="accent"><?php echo $userName; ?></span> con éxito. ¡No te olvides de tu contraseña!
                </p>
                <p>
                    Con este usuario y contraseña, mediante un cliente FTP, podrás acceder a tu espacio web.
                    Simplemente introduce la dirección del servidor, tu usuario y contraseña, y podrás subir tus archivos.
                    No hace falta indicar el puerto, ya que por defecto es el 21.
                </p>
                <p>
                    A la hora de subir tu página, aunque no es obligatorio, se recomienda que el archivo principal se llame <span class="accent">index.html</span>.
                </p>
                <section class="subcontainer">
                    <a href="index.html" class="navButton">Volver</a>
                </section>
            </section>
        </article>
    </main>
    <section>
        <section class="container">
            <div class="subcontainer">
                <h2>¿Cómo puedo subir mi página web?</h2>
            </div>
            <div class="subcontainer">
                <p>
                    <span class="accent">¡Es muy sencillo!</span> Simplemente necesitas un cliente FTP. Hay muchos clientes FTP gratuitos y de pago.
                    Algunos ejemplos son <a href="https://filezilla-project.org/" class="secondary">FileZilla</a> o
                    <a href="https://winscp.net/eng/index.php" class="secondary">WinSCP</a>.
                </p>
                <p>
                    Una vez tengas tu cliente FTP, introduce la dirección del servidor, tu usuario y contraseña, y podrás
                    subir tus archivos. La dirección del servidor es <span class="accent"><?php echo $currentIP; ?></span>.
                </p>
            </div>
            <div class="subcontainer">
                <h2>No me acuerdo de la contraseña...</h2>
            </div>
            <div class="subcontainer">
                <p>
                    No te preocupes, coménteselo al profesor para que te la cambie. Simplemente debe de ejecutar el siguiente comando:<br>
                <pre><?php echo "sudo echo $userName:contraseñaNueva | chpasswd" ?></pre>
                </p>
            </div>
            <div class="subcontainer">
                <h2>¿Qué puedo subir?</h2>
            </div>
            <div class="subcontainer">
                <p>
                    <span class="accent">Páginas web</span>, por su puesto. Puedes crear y subir tantos subdirectorios como quieras.
                    También puedes subir <span class="accent">imágenes, vídeos, archivos...</span> ¡Lo que quieras!.<br>
                    Intenta no subir archivos muy pesados, ya que el espacio es limitado.
                </p>
            </div>
        </section>
    </section>
    <footer>
        <p>Made by <b>Angel Krasimirov Stoyanov</b> with love &lt;3</p>
        <p>In collaboration with <b>Isidro</b></p>
    </footer>
    <script src="js/loaded.js"></script>
    <script src="js/theme.js"></script>
</body>

</html>