<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Registro Policial Criminal</h1>
    <h2>Inicio de Sesión</h2>

        <form method="post">
            <label>Usuario: </label>
            <input type="text" name="nombre" >
            <br><br>

            <label>Contraseña: </label>
            <input type="password" name="contrasena" >
            <br><br>

            <input type="submit" value="Iniciar sesion">
        </form>
       
<?php

    require_once('alertas.php');
    require('conect.php');
    
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #El uso de trim es para evitar espacios innecesarios
    #El uso ?? "" es para poner de predeterminado nada
    $nombre     = trim($_POST["nombre"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $conexion=conectar();
    $query="SELECT * FROM usuarios WHERE nombre_usuario= ? AND contr=?";
    $stmt=$conexion->prepare($query);
    $stmt->bind_param("ss", $nombre, $contrasena);
    $stmt->execute();
    $resultado=$stmt->get_result();
    if ($nombre === "" || $contrasena === "") {
        alerta_campo();
    } elseif (
      $resultado->num_rows > 0
    ) {
        //Divido las filas en un array en $fila
        $fila=$resultado->fetch_assoc();
        $rol=$fila['rol'];  
        if($rol==="administrador"){
            header("Location: menu.php");
            exit;
        }else{
            header("Location:menu_normal.php");
            exit;
        }
       
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
        echo $mensaje;
    }
    $stmt->close();
    $conexion->close();
}

?>
    
    
</body>
</html>