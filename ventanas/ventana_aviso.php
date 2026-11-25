
<?php
/*Aqui uso javascript, pero lo congelo por mantenimiento
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["aceptar_ventana_aviso"])) {
        // Enviar la confirmación al PHP principal
        ?>
        <form id="confirmacion" method="POST" action="../src/admin_policia_menu/registrar_criminal.php">
            <input type="hidden" name="aceptar_ventana_aviso" value="1">
        </form>

        <script>
            //Envia el formulario al archivo correspondiente en action="../admin_policia_menu/registrar_criminal.php"
            document.getElementById("confirmacion").submit();
            window.close();
        </script>
        <?php
        exit;
    }

    if (isset($_POST["cancelar"])) {
        ?>
        <form id="cancelacion" method="POST" action="../src/admin_policia_menu/registrar_criminal.php">
            <input type="hidden" name="cancelar" value="1">
        </form>

        <script>
            window.close();
        </script>
        <?php
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventana de alerta</title>
</head>
<body>

<h2>Confirma tu elección</h2>

<form method="POST" action="../src/admin_policia_menu/registrar_criminal.php">
    <button type="submit" name="aceptar_ventana_aviso">
        Sí, continuar
    </button>

    <button type="submit" name="cancelar">
        No, cancelar
    </button>
</form>

</body>
</html>
*/?>
