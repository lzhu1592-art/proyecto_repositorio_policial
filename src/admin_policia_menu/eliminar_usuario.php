<?php
session_start();

// Evitar que el navegador guarde la página en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Si falta usuario O contraseña en la sesión, error
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["contra"])) {
    header("Location: funciones/login_error.php");
    exit;
}

require_once __DIR__ . '/../funciones/conect.php';
require_once __DIR__ . '/../funciones/alertas.php';

//Aqui directamente con la base de datos de xampp
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      //Aqui digo: del boton con id "eliminar" si esta presionado, entonces la variable confirmacion es true
    
    
        $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
        if ($buscar === "") {
            alerta_campo();
        } else{
            $_SESSION["buscar_usuario"] = (int)$buscar;
        }
        $conexion = conectar();
        eliminar_usuario($conexion,$buscar);
        $conexion->close();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
</head>

<body>
    <a href="#" class="volver">Volver al menu</a>
    <form method="POST" action="">

        <h2>Buscar por identificación</h2><br><br>
        <input type="number" name="buscar"><br>
        
            <button type="submit" name="eliminar" id="boton_alerta">Eliminar usuario</button>
        

    </form>
    <script src="javascript.js"></script>
</body>

<?php
//Aqui se hace de manera local usando bd.php
/*
require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/bd.php';
// Cargar los usuarios iniciales al comenzar una sesión
if (!isset($_SESSION["usuarios"])) {
    $_SESSION["usuarios"] = $usuarios;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = strtolower(trim($_POST["buscar"] ?? ""));

    // Comprobar que se ha elegido un usuario
    if ($nombre === "") {
        alerta_campo();
    } elseif (!isset($_SESSION["usuarios"][$nombre])) {
        echo "<p>El usuario no existe.</p>";
    } elseif ($nombre === strtolower($_SESSION["usuario"])) {
        echo "<p>No puedes eliminar el usuario con el que has iniciado sesión.</p>";
    } else {
        unset($_SESSION["usuarios"][$nombre]);

        file_put_contents(
            __DIR__ . "/historial.txt",
            date("H:i") . " Eliminar usuario: " . $nombre . PHP_EOL,
            FILE_APPEND
        );

        echo "<p>Usuario eliminado correctamente.</p>";
    }
}
*/
?>

</html>