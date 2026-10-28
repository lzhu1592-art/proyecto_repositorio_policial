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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>
</head>

<body>
    
    
    <div style="background-color:#eef1f5;  border: 1px solid black; ">
        <table border="0">
           
                <tr >
                    <td width="550px">
                        <h2>REGISTRO POLICIAL CRIMINAL</h2>
                    </td>
                </tr>
            
            <tr>
                <td>
                    <p align="center">MENÚ PRINCIPAL</p>
                    <p><a href="buscar.php"> Buscar persona </a></p>
                    <p><a href="registrar.php"> Registrar persona </a></p>
                    <p><a href="historial.php">Historial de operaciones</a> </p>
                    <p><a href="gestion.php">Gestion de usuarios</a></p>
                    <p><a href="funciones/cerrar.php">Cerrar sesion</a></p>
                </td>
            </tr>

        </table>
   </div>
</body>
</html>