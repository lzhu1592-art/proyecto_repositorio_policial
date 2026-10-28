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

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';
/*
// Cargar los usuarios iniciales al comenzar una sesión
require_once __DIR__ . '/funciones/bd.php';
if (!isset($_SESSION["usuarios"])) {
    $_SESSION["usuarios"] = $usuarios;
}
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = strtolower(trim($_POST["nombre"] ?? ""));
    $contr = strtoupper(trim($_POST["contr"] ?? ""));
    $rol = trim($_POST["rol"] ?? "");
//El file_put_contents sirve para escribir en un archibo.txt
    file_put_contents(
    "historial.txt",
    date("H:i") . " Crear usuario" . PHP_EOL,
    FILE_APPEND 
);
    // Comprobar que todos los campos estén completos
    if (
        $nombre === "" ||
        $contr === "" ||
        $rol === ""
    ) {
        alerta_campo();
    }elseif (isset($_SESSION["usuarios"][$nombre])) {
        echo "<p>Ya existe un usuario con ese nombre.</p>";
    } else {
        $_SESSION["usuarios"][$nombre] = [
            "contr" => $contr,
            "rol"   => $rol
        ];
        echo "<p>Usuario creado correctamente.</p>";
    }
}
*/
//LLamo a la function cuando necesite algo
$confirmacion=false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  //Aqui digo: del boton con id "aceptar" si esta presionado, entonces la variable confirmacion es true
    if(isset($_POST["crear"])){
        $confirmacion=true;
    }
    if(!isset($_POST["aceptar"])){
        echo "Has cancelado la operacion";
    }else{
        $nombre = strtolower(trim($_POST["nombre"] ?? ""));
        $contr = strtoupper(trim($_POST["contr"] ?? ""));
        $rol = trim($_POST["rol"] ?? "");
    //El file_put_contents sirve para escribir en un archibo.txt
        file_put_contents(
        "historial.txt",
        date("H:i") . " Crear usuario" . PHP_EOL,
        FILE_APPEND 
    );
        // Comprobar que todos los campos estén completos
        if (
            $nombre === "" ||
            $contr === "" ||
            $rol === ""
        ) {
            alerta_campo();
        }elseif(
            strlen($nombre) != 3 || strlen($contr) < 5 || !str_contains($rol, "invitado") && !str_contains($rol, "administrador") && !str_contains($rol, "policia") 
        ){      
            formato();
            exit;
        }else {
            $conectar2=conectar();
            $guardar="INSERT INTO usuarios(nombre_usuario,contr,rol) 
            VALUES(?,?,?)";
            $stmt=$conectar2->prepare("$guardar");
            //Se debe poner el tipo (string, int ) por cada variable        
            $stmt->bind_param("sss", $nombre,$contr,$rol);
            $stmt->execute();
            echo "Datos guardados";

            $stmt->close();
            $conectar2->close();    
            
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuarios</title>
</head>
<body>

    <h1>Crear Usuarios</h1>

    <a href="menu.php">Volver al menú</a>

    <form method="POST" action="">

        <label for="nombre">Nombre de usuario:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>

        <label for="contr">Contraseña:</label>
        <input type="text" id="contr" name="contr" required>
        <br><br>

        <label for="rol">Rol (administrador, policia, invitado):</label>
        <input type="text" id="rol" name="rol" required>
        <br><br>

        <?php if(!$confirmacion){?>
            <button type="submit" name="crear">Crear Usuario</button>
        <?php } else{?>
                <p>¿Estás seguro de que quieres crear este usuario?</p>

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
