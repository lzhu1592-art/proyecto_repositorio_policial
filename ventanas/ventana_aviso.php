<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventana de alerta</title>
</head>
<body>
    

    <h2>Confirma tu eleccion</h2>
    <?php   
        echo 
        '<form method="POST">
            <button type="submit" name="aceptar_ventana_aviso" onclick="window.close()">Sí, continuar</button>
            <button type="submit" name="cancelar" onclick="window.close()">No, cancelar</button>
        </form>';
    ?>
</body>
</html>