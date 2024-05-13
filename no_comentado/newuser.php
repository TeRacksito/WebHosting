<?php
$name = $_POST['name'];
$surname = $_POST['surname'];
$password = $_POST['password'];

function checkLength($field, $min, $max)
{
    if (strlen($field) < $min) {
        return false;
    } else if (strlen($field) > $max) {
        return false;
    }
    return true;
}

function checkCharacters($field)
{
    if (preg_match('/^[a-zA-Z0-9ñÑ]+$/', $field)) {
        return true;
    }
    return false;
}

if (!(checkLength($name, 3, 20) && checkLength($surname, 3, 20) && checkLength($password, 6, 20))) {
    if (!(checkCharacters($name) && checkCharacters($surname) && checkCharacters($password))) {
        header("Location: /error.html");
        exit();
    }
}

// user verification and creation

$userName = $name . "_" . $surname;

$output = runCommand("cat /etc/passwd | grep $userName", 1);

$output = runCommand("useradd -M -N -g webuser $userName", 0);

$output = runCommand("echo $userName:$password | chpasswd", 0);

// create and set user home directory

$userHome = "/var/www/html/$userName";

$output = runCommand("mkdir -p $userHome", 0);

$output = runCommand("chown -R $userName:webuser $userHome", 0);

$output = runCommand("chmod 700 $userHome", 0);

$output = runCommand("usermod -d $userHome $userName", 0);

$currentIP = $_SERVER['SERVER_ADDR'];

function runCommand($command, $success_value)
{
    $output = array();
    exec($command, $output, $return_var);
    if ($return_var != $success_value) {
        header("Location: /error.html");
        exit();
    }
    return $output;
}

function echoArray($array)
{
    foreach ($array as $line) {
        echo $line . "<br>";
    }
}
?>

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
                    <div class="success-icon">
                        <span class="stick-container"><span class="success-stick1 stick"></span></span>
                        <span class="stick-container"><span class="success-stick2 stick"></span></span>
                    </div>
                </div>
            </section>
            <section class="subcontainer">
                <p>
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