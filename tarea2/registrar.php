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
?>

<?php


//require_once __DIR__ . '/funciones/bd.php';
require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';

//LLamo a la function cuando necesite algo
$confirmacion=false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    //Aqui digo: del boton con id "aceptar" si esta presionado, entonces la variable confirmacion es true
    if(isset($_POST["registrar"])){
        $confirmacion=true;
    }
    if(!isset($_POST["aceptar"])){
        echo "Has cancelado la operacion";
    }else{

    
    $nombre = strtolower(trim($_POST["nombre"] ?? ""));
    $documento = strtoupper(trim($_POST["documento"] ?? ""));
    $antecedente = trim($_POST["antecedente"] ?? "");
    $descripcion = $_POST["descripcion"] ?? "";
    if (strlen($documento) != 9 || str_contains($documento, " ")) {
        formato();
    }
    //El file_put_contents sirve para escribir en un archibo.txt
    file_put_contents(
        "historial.txt",
        date("H:i") . " registro criminal" . PHP_EOL,
        FILE_APPEND
    );
    // Comprobar que todos los campos estén completos
    if (
        $nombre === "" ||
        $documento === "" ||
        $antecedente === "" ||
        $descripcion === ""
    ) {
        alerta_campo();
    }else {
        $conectar2 = conectar();
        //Aqui compruebo si el criminal ya existe en la base de datos, si es asi no lo guardo
        $comprobar ="SELECT * FROM criminales WHERE documento = ?";
        $stmt = $conectar2->prepare("$comprobar");
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            alerta_repetido();
        }else{
        
        $guardar = "INSERT INTO criminales(nombre_criminal,delito,descripcion_delito,documento) 
        VALUES(?,?,?,?)";
        $stmt = $conectar2->prepare("$guardar");
        //Se debe poner el tipo (string, int ) por cada variable        
        $stmt->bind_param("ssss", $nombre, $antecedente, $descripcion, $documento);
        $stmt->execute();
        echo "Datos guardados";

        $stmt->close();
        $conectar2->close();
        /*
        // Cargar los criminales iniciales al comenzar una sesión
        Es decir que yo digo que si no existe una sesion de criminales me los creas
            if (!isset($_SESSION["criminales"])) {
                $_SESSION["criminales"] = $criminales;
            }
        */
        /* // Guardar temporalmente en la sesión
        PARA GUARDARLO Y ESCRIBIRLO PERO AL CERRAR SESION SE BORRA TODO
         $_SESSION["criminales"][$nombre] = [
             "documento" => $documento,
             "antecedente" => $antecedente,
             "descripcion" => $descripcion
         ];
*/   }
    }
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

    <h1>Registrar criminal</h1>

    <a href="menu.php">Volver al menú</a>
    <a href="funciones/cerrar.php">Cerrar sesion</a>

    <form method="POST" action="">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>

        <label for="documento">Documento:</label>
        <input type="text" id="documento" name="documento" required>
        <br><br>

        <label for="antecedente">Antecedente:</label>
        <textarea id="antecedente" name="antecedente" required></textarea>
        <br><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
        <br><br>
        <!-- Aqui estoy diciendo que si no hay confirmacion me muestre el boton de registrar, si hay 
         confirmacion me muestre el mensaje de confirmacion y los botones de aceptar o cancelar -->
<?php if(!$confirmacion){?>
        <button type="submit" name="registrar">Registrar criminal</button>
<?php } else{?>
        <p>¿Estás seguro de que quieres eliminar el usuario?</p>

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

    <hr>

    <h2>Criminales registrados</h2>
    <?php
    $conexion = conectar();
    $query = "SELECT * FROM criminales";
    $resultado = $conexion->query($query);
    while ($fila = $resultado->fetch_assoc()) {
        echo "Nombre: " . $fila["nombre_criminal"] . "<br>";
        echo "Documentacion: " . $fila["documento"] . "<br>";
        echo "Delito: " . $fila["delito"] . "<br>";
        echo "Descripcion del delito: " . $fila["descripcion_delito"] . "<br><br><br>";
    }
    ;
    ?>

    <?php

    /*
    Aqui estoy diciendo qu de la session guardada me lo muestre

      <?php foreach ($_SESSION["criminales"] as $nombre => $datos): ?>

            <h3><?= htmlspecialchars($nombre) ?></h3>

            <p>Documento: <?= htmlspecialchars($datos["documento"]) ?></p>

            <p>Antecedente: <?= htmlspecialchars($datos["antecedente"]) ?></p>

            <p>Descripción: <?= htmlspecialchars($datos["descripcion"]) ?></p>

            <hr>

        <?php endforeach; ?>
    */
    ?>
</body>

</html>