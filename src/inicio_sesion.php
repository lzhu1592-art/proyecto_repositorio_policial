 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    //Aqui hize usando base de datos con mariadb y localmente
    require_once __DIR__ . '/funciones/alertas.php';
    require_once __DIR__ . '/funciones/conect.php';
   
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #El uso de trim es para evitar espacios innecesarios
        #El uso ?? "" es para poner de predeterminado nada
        $nombre = trim($_POST["nombre"] ?? "");
        $contrasena = $_POST["contrasena"] ?? "";
        consultar_inicio_sesion($nombre,$contrasena);
       
    }
   
    ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Registro Policial Criminal</h1>
    <h2>Inicio de Sesión</h2>

    <form method="post">
        <label>Usuario: </label>
        <input type="text" name="nombre">
        <br><br>

        <label>Contraseña: </label>
        <input type="password" name="contrasena">
        <br><br>

        <input type="submit" value="Iniciar sesion">
    </form>

   
<?php
 /* Aqui como si fuera localmente
     // require_once __DIR__ . '/funciones/bd.php';
        if (empty($nombre) || empty($contrasena)) {
            alerta_campo();
        } elseif (
            isset($usuarios[$nombre]) &&
            $usuarios[$nombre]["contrasena"] === $contrasena
        ) {
            $_SESSION["usuario"]=$nombre;
            $_SESSION["contra"]=$contrasena;
            header("Location: menu.php");
        } else {
            echo "Usuario o contraseña incorrectos";
        }
            */
?>

</body>

</html>