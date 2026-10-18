
<?php
session_start();

require_once("alertas.php");
require("conect.php");
/*
// Cargar los criminales iniciales al comenzar una sesión
if (!isset($_SESSION["criminales"])) {
    $_SESSION["criminales"] = $criminales;
}
*/
//LLamo a la function cuando necesite algo

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
    } else {
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de criminales</title>
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

        <button type="submit">Crear Usuario</button>

    </form>


</body>
</html>
