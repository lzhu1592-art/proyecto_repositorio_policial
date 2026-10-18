<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="menu.php">Volver al menu</a>
    <a href="cerrar.php">Cerrar sesión</a>
    <form method="POST" action="">

        <h2>Buscar por identificación</h2><br><br>
        <input type="text" name="buscar"><br>
        <button type="submit">Buscar</button>
    </form>
</body>

<?php

require_once('alertas.php');
require('conect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $en = false;
    $conexion = conectar();
    file_put_contents(
        "historial.txt",
        date("H:i") . " registro" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    $sql = "SELECT * FROM criminales WHERE documento=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($buscar === "") {
        alerta_campo();
    } else {
        while ($fila = $resultado->fetch_assoc()) {
            echo "Nombre: " . $fila["nombre_criminal"] . "<br>";
            echo "Documentacion: " . $fila["documento"] . "<br>";
            echo "Delito: " . $fila["delito"] . "<br>";
            echo "Descripcion del delito: " . $fila["descripcion_delito"] . "<br><br><br>";
        }
        /*
        //Aqui digo que busques en el array criminal con el nombre Y sus datos que estan en array
        foreach ($criminales as $nombre => $datos) {
            if ($datos["documento"] === $buscar) {

                echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
                echo "Documento: " . htmlspecialchars($datos["documento"]) . "<br>";
                echo "Antecedente: " . htmlspecialchars($datos["antecedente"]) . "<br>";
                echo "Descripción: " . htmlspecialchars($datos["descripcion"]) . "<br>";
                $en=true;
                break;
            } 
        }
        if ($en===false)
            {
                echo "No se ha encontrado la persona";
            }
                */
    }


}

?>

</html>