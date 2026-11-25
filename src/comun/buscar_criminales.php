<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

require_once __DIR__ . '/../funciones/alertas.php';
require_once __DIR__ . '/../funciones/conect.php';
$en = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = strtoupper(trim($_POST["buscar"] ?? ""));
   
    if($buscar === "") {
        alerta_campo();
    } else {
        $en = false;
        $conexion = conectar();
        $resultados = obtener_criminales($conexion, $buscar);
       
        $conexion->close();

        registrar_historial("Busqueda: " . $buscar);
        $en = true;
       
    }
        
    
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../javascript.js" defer></script>
</head>
<body>
    <a href="#" class="volver">Volver atrás</a>
    <a href="../funciones/cerrar.php">Cerrar sesion</a>
    <form method="POST" action="">

        <h2>Buscar por identificación</h2><br><br>
        <input type="text" name="buscar"><br>
        <button type="submit">Buscar</button>
    </form>
    <?php if ($en): ?>
        <hr>
        <!-- Mostrar resultados de la búsqueda 
         Si el array de $resultado es 0 significa que no hay-->
        <?php if (count($resultados) === 0): ?>
            <p style="color:red;">No se ha encontrado ninguna persona con ese documento.</p>
        <?php else: ?>
            <p>Resultados encontrados: <?= count($resultados) ?></p>
        <!--Aqui uso un foreach para recorre una array-->
            <?php foreach ($resultados as $criminal): ?>
                <?php mostrar_criminal($criminal); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
   
</body>

<?php
/*
Base de datos locales
      
            require_once __DIR__ . '/funciones/bd.php';

            // Criminales creados durante la sesión (si no existen, array vacío)
            $criminales_sesion = $_SESSION["criminales"] ?? [];

            // Unimos los de la BD y los de la sesión
            $todos = array_merge($criminales, $criminales_sesion);

            $en = false;

            foreach ($todos as $nombre => $datos) {
                if ($datos["documento"] === $buscar) {
                    echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
                    echo "Documento: " . htmlspecialchars($datos["documento"]) . "<br>";
                    echo "Antecedente: " . htmlspecialchars($datos["antecedente"]) . "<br>";
                    echo "Descripción: " . htmlspecialchars($datos["descripcion"]) . "<br>";
                    $en = true;
                    break;
                }
            }

            if ($en === false) {
                echo "No se ha encontrado la persona";
            }
                */
                ?>

</html>