
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

    // PASO 1: REGISTRAR
    if (isset($_POST["modificar"])) {

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

            // Guardamos los datos temporalmente
            $_SESSION["registro_pendiente"] = [
                "nombre" => $nombre,
                "documento" => $documento,
                "antecedente" => $antecedente,
                "descripcion" => $descripcion
            ];
        }
    }

        if (isset($_SESSION["registro_pendiente"])) {

            $datos = $_SESSION["registro_pendiente"];

            
            if (modificar_criminal($conexion, $datos)) {

                registrar_historial(
                    "registro criminal: " . $datos["nombre"]
                );

                echo "Datos guardados correctamente.";

            } else {

                echo "Error al guardar los datos.";
            }

            unset($_SESSION["registro_pendiente"]);

        } else {

            echo "No hay ningún registro pendiente de confirmar.";
        }
}

   
    $conexion->close();


// ======================================
// RESPUESTA A FETCH
// ======================================

// Si la petición viene de la ventana emergente,
// devolvemos una respuesta sin mostrar el HTML.
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    (
        isset($_POST["aceptar_ventana_aviso"]) ||
        isset($_POST["cancelar"])
    )
) {
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de criminales</title>
    <script src="../javascript.js" defer></script>
</head>

<body>

<h1>Modificar criminal</h1>

<a href="#" class="volver">Volver al menú</a>
<a href="../funciones/cerrar.php">Cerrar sesión</a>

<br><br>

<form method="POST" action="">
    <label for="documento">Documento: (Para identificarlo)</label>
    <input type="text" id="documento" name="documento" required>
    <br><br>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    <br><br>

    <label for="antecedente">Antecedente:</label>
    <textarea id="antecedente" name="antecedente" required></textarea>
    <br><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required></textarea>
    <br><br>

    <button type="submit" id="boton_alerta" name="modificar">
        Modificar
    </button>

</form>

</body>
</html>
