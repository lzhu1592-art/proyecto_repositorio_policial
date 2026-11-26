
<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION["usuario"]) || !isset($_SESSION["contra"])) {
    header("Location: funciones/login_error.php");
    exit;
}

require_once __DIR__ . '/../funciones/conect.php';
require_once __DIR__ . '/../funciones/alertas.php';

// ======================================
// PETICIONES POST
// ======================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conexion = conectar();

    if (isset($_POST["eliminar"])) {

        // Limpiar posibles datos anteriores
        unset($_SESSION["registro_pendiente"]);

        $documento = strtoupper(trim($_POST["documento"] ?? ""));

        if ($documento === "") {

            alerta_campo();

        } elseif (
            strlen($documento) !== 9 ||
            str_contains($documento, " ")
        ) {

            formato();

        } else {

            $_SESSION["registro_pendiente"] = [
                "documento" => $documento
            ];

            $datos = $_SESSION["registro_pendiente"];

            if (existe_criminal($conexion, $datos)) {

                if (eliminar_criminales($conexion, $datos)) {

                    registrar_historial(
                        "Eliminar criminal: " . $datos["documento"]
                    );

                    echo "Datos eliminados correctamente.";

                } else {

                    echo "Error al eliminar los datos.";
                }

            } else {

                echo "No existe esta persona.";
            }

            unset($_SESSION["registro_pendiente"]);
        }
    }

    $conexion->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eliminar criminal</title>
    <script src="../javascript.js" defer></script>
</head>

<body>

<h1>Eliminar criminal</h1>

<a href="#" class="volver">Volver al menú</a>
<a href="../funciones/cerrar.php">Cerrar sesión</a>

<br><br>

<form method="POST" action="">

    <label for="documento">
        Documento: (Para identificarlo)
    </label>

    <input type="text" id="documento" name="documento" required>

    <br><br>

    <button type="submit" id="boton_alerta" name="eliminar">
        Eliminar
    </button>

</form>

</body>
</html>
