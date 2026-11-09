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
            <button type="submit" name="aceptar">Sí, continuar</button>
            <button type="submit" name="cancelar">No, cancelar</button>
        </form>';
    ?>
</body>
</html>