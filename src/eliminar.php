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

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';
$confirmacion=false;
//Aqui directamente con la base de datos, no lo hize como si fuera localmente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      //Aqui digo: del boton con id "aceptar" si esta presionado, entonces la variable confirmacion es true
    if(isset($_POST["eliminar"])){
        $confirmacion=true;
    }
    if(!isset($_POST["aceptar"])){
        echo "Has cancelado la operacion";
    }else{
        $comprobar="SELECT * FROM usuarios WHERE id=?";
        if(!is_array($comprobar)){
            echo "El usuario no existe";
            exit;
        }
        $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
        $conexion = conectar();
        file_put_contents(
            "historial.txt",
            date("H:i") . " eliminar usuario" . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
        $sql = "DELETE FROM usuarios WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $buscar);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($buscar === "") {
            alerta_campo();
        } else {
            echo "Usuario eliminado";
        }
    }
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
    <a href="menu.php">Volver al menu</a>
    <form method="POST" action="">

        <h2>Buscar por identificación</h2><br><br>
        <input type="number" name="buscar"><br>
        <?php if(!$confirmacion){?>
            <button type="submit" name="eliminar">Eliminar criminal</button>
        <?php } else{?>
                <p>¿Estás seguro de que quieres eliminar este criminal?</p>

            <form method="POST">
                <button type="submit" name="aceptar">
                    Sí, continuar
                </button>

                <button type="submit" name="cancelar">
                    No, cancelar
                </button>
            </form>
<?php } ?>
    </form>
</body>



</html>