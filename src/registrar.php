
<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION["usuario"]) || !isset($_SESSION["contra"])) {
    header("Location: funciones/login_error.php");
    exit;
}

require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';

$mostrarConfirmacion = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Paso 1: el usuario pulsa Registrar
    if (isset($_POST["registrar"])) {

        $nombre = strtolower(trim($_POST["nombre"] ?? ""));
        $documento = strtoupper(trim($_POST["documento"] ?? ""));
        $antecedente = trim($_POST["antecedente"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");

        if (
            $nombre === "" ||
            $documento === "" ||
            $antecedente === "" ||
            $descripcion === ""
        ) {
            alerta_campo();

        } elseif (
            strlen($documento) !== 9 ||
            str_contains($documento, " ")
        ) {
            formato();

        } else {
            // Guardamos temporalmente los datos para confirmar
            $_SESSION["registro_pendiente"] = [
                "nombre" => $nombre,
                "documento" => $documento,
                "antecedente" => $antecedente,
                "descripcion" => $descripcion
            ];

            $mostrarConfirmacion = true;
        }
    }

    // Paso 2: el usuario cancela
    elseif (isset($_POST["cancelar"])) {
//En caso de cancelar la operación, eliminamos los datos de la sesión y mostramos un mensaje
        unset($_SESSION["registro_pendiente"]);
        echo "Has cancelado la operación.";

    }

    // Paso 3: el usuario confirma
    elseif (isset($_POST["aceptar"])) {

        if (isset($_SESSION["registro_pendiente"])) {
    //Aqui habiamos guardado datos como array en la sesion, entonces los recuperamos como array
            $datos = $_SESSION["registro_pendiente"];
    //Dividmos el array en variables simples para usarlo en la consulta SQL
            $nombre = $datos["nombre"];
            $documento = $datos["documento"];
            $antecedente = $datos["antecedente"];
            $descripcion = $datos["descripcion"];

            $conexion = conectar();

            $sql = "SELECT documento
                    FROM criminales
                    WHERE documento = ?";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $documento);
            $stmt->execute();

            $resultado = $stmt->get_result();
//Aqui uso el -> para decir que si el $resultado  es num_rows(que es una variable predeterminada en php) es mayor a 0, entonces la persona ya existe 
// y no se puede registrar
            if ($resultado->num_rows > 0) {

                alerta_repetido();

            } else {

                $sql = "INSERT INTO criminales
                        (nombre_criminal, delito,
                         descripcion_delito, documento)
                        VALUES (?, ?, ?, ?)";

                $stmt->close();
                $stmt = $conexion->prepare($sql);

                $stmt->bind_param(
                    "ssss",
                    $nombre,
                    $antecedente,
                    $descripcion,
                    $documento
                );

                if ($stmt->execute()) {
                    echo "Datos guardados correctamente.";

                    file_put_contents(
                        __DIR__ . "/historial.txt",
                        date("H:i") . " registro criminal" . PHP_EOL,
                        FILE_APPEND
                    );

                    unset($_SESSION["registro_pendiente"]);
                } else {
                    echo "Error al guardar los datos.";
                }
            }

            $stmt->close();
            $conexion->close();

        } else {
            echo "No hay ningún registro pendiente de confirmar.";
        }
    }
}
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
        */   
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de criminales</title>
</head>

<body>
       <!-- Aqui al usar el php dentro del body
        evito que al pulsar los botonos de aceptar o cancelar, se vuelvan
        inservibles, ya que al pulsar el boton de registrar,  
        se muestra los botones de confirmar pero se vacia el formulario, entonces al pulsar aceptar o cancelar, no se puede hacer nada
        porque estan obligados a estar rellenados
        Y se usa una _SESSION para guardar los datos temporalmente y usarlo en sql
-->
<?php if ($mostrarConfirmacion): ?>

    <?php alerta_confirmacion(); ?>

<?php else: ?>

    <form method="POST" action="">
        
    <h1>Registrar criminal</h1>

    <a href="menu.php">Volver al menú</a>
    <a href="funciones/cerrar.php">Cerrar sesion</a>
    <br><br>
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

        <button type="submit" name="registrar">
            Registrar
        </button>
    </form>

<?php endif; ?>


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