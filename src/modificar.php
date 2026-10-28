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

/*
Aqui lo hare usando bd.php como base de datos
session_start();
require_once __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
    $nombre=trim($_POST["nombre"] ??"");
    $contr=trim($_POST["contr"] ??"");
    $rol=trim($_POST["rol"] ??"");
   
   
    if ($buscar === "") {
        alerta_campo();
    } else {
        $_SESSION["usuarios"][$buscar] = [
            "nombre" => $nombre,
            "contr" => $contr,
            "rol" => $rol
        ];
         file_put_contents(
        "historial.txt",
        date("H:i") . " modificar usuario" . PHP_EOL,
        FILE_APPEND | LOCK_EX

        echo "Usuario modificado";
    );
    }

*/

require __DIR__ . '/funciones/alertas.php';
require_once __DIR__ . '/funciones/conect.php';
$confirmacion=false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Aqui digo: del boton con id "aceptar" si esta presionado, entonces la variable confirmacion es true
    if(isset($_POST["modificar"])){
       
        $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
        $nombre=trim($_POST["nombre"] ??"");
        $contr=trim($_POST["contr"] ??"");
        $rol=trim($_POST["rol"] ??"");
        if ($buscar === "" || $nombre === "" || $contr === "" || $rol === "") {
                alerta_campo();
                
        } else{
         //GUardamos los datos en la sesion para usarlos en sql
        $_SESSION["registro_pendiente"] = [
            "nombre" => $nombre, 
            "contr" => $contr,
            "rol" => $rol,
            "buscar" => $buscar
        ];
        
        $confirmacion=true;   
        }
        

    }elseif(isset($_POST["cancelar"])){

        unset($_SESSION["registro_pendiente"]);
        echo "Has cancelado la operación.";

    }elseif(isset($_POST["aceptar"])){
        if(isset($_SESSION["registro_pendiente"]))

            $datos = $_SESSION["registro_pendiente"];
            $nombre = $datos["nombre"];
            $contr = $datos["contr"];
            $rol = $datos["rol"];
            $buscar = $datos["buscar"];


            $conexion = conectar();
            
            $sql = "UPDATE usuarios SET nombre_usuario=?,contr=?,rol=? WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssi", $nombre,$contr,$rol,$buscar);
                if($stmt->execute()){
                    
                    echo "Usuario modificado";

                    file_put_contents(
                        "historial.txt",
                        date("H:i") . " modificar usuario" . PHP_EOL,
                        FILE_APPEND | LOCK_EX
                    );
                    unset($_SESSION["registro_pendiente"]);
                }else{
                    echo "Error al modificar el usuario: " . $stmt->error;
                }
            $stmt->close();
            $conexion->close();
        }           
    }     

        
    
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar usuario</title>
</head>

<body>
    <a href="menu.php">Volver al menu</a>
      <!-- Aqui al usar el php dentro del body
        evito que al pulsar los botonos de aceptar o cancelar, se vuelvan
        inservibles, ya que al pulsar el boton de registrar,  
        se muestra los botones de confirmar pero se vacia el formulario, entonces al pulsar aceptar o cancelar, no se puede hacer nada
        porque estan obligados a estar rellenados
        Y se usa una _SESSION para guardar los datos temporalmente y usarlo en sql
-->
<?php if ($confirmacion): ?>

    <?php alerta_confirmacion(); ?>

<?php else: ?>

    <form method="POST" action="">

        <h2>Buscar por identificación o nombre</h2><br><br>
        <input type="text" name="buscar" required><br>
        <h2>Cambiar nombre </h2><br><br>
        <input type="text" name="nombre" required><br>
        <h2>Cambiar contraseña</h2><br><br>
        <input type="text" name="contr" required><br>
        <h2>Cambiar rol</h2><br><br>
        <input type="text" name="rol" required><br><br>
        <button type="submit" name="modificar">
            Modificar
        </button>
    </form>
    <?php endif; ?>
</body>



</html>