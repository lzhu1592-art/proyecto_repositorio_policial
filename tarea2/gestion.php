<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion</title>
</head>
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

<body>
    <a href="menu.php">Volver al menu</a>
    <a href="cerrar.php">Cerrar sesion</a>
    <div style="background-color:#eef1f5;  border: 1px solid black; >
        <table border="0">
           
                <tr >
                    <td width="550px">
                        <h2>REGISTRO POLICIAL CRIMINAL</h2>
                    </td>
                </tr>
            
            <tr>
                <td>
                    <p align="center">GESTIÓN</p>
                    <p><a href="buscar_a.php"> Buscar usuario </a></p>
                    <p><a href="crear.php"> Crear usuario </a></p>
                    <p><a href="modificar.php">Modificar usuario</a> </p>
                    <p><a href="eliminar.php">Eliminar usuario</a></p>
                </td>
            </tr>

        </table>
   </div>
</body>
</html>