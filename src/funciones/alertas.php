
<?php
//Alertas

# Alerta por campos vacíos
function alerta_campo() {
    echo "<p style='color:red;'>Debes rellenar todos los campos.</p>";
}

# Alerta por formato incorrecto
function formato() {
    echo "<p style='color:red;'>Formato no válido.</p>";
}

# Alerta por persona repetida
function alerta_repetido() {
    echo "<p style='color:red;'>Esa persona ya existe, en caso de añadir nuevo delito, debe modificarlo.</p>";
}

//Aqui pongo una funcion para registrar en el historial, ya que se repite en varios archivos
function registrar_historial($accion)
{
    $linea = date("H:i") . " " . $accion . PHP_EOL;
    file_put_contents(__DIR__ . "/../historial.txt", $linea, FILE_APPEND | LOCK_EX);
}
//CRIMINALES

//Funcion para verificar si un criminal ya existe en la base de datos
function existe_criminal($conexion, $documento)
{
    $stmt = $conexion->prepare("SELECT documento FROM criminales WHERE documento = ?");
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    return $existe;
}
//Modificar criminales
function modificar_criminal($conexion,$datos){
$sql = "UPDATE criminales 
        SET nombre_criminal = ?, 
            delito = ?, 
            descripcion_delito = ?
        WHERE documento = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ssss",
    $datos["nombre"],
    $datos["antecedente"],
    $datos["descripcion"],
    $datos["documento"]
);

$stmt->execute();

$stmt->close();

}
//Funcion para insertar un criminal en la base de datos
function insertar_criminal($conexion, $datos)
{
    $sql = "INSERT INTO criminales (nombre_criminal, delito, descripcion_delito, documento)
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $datos["nombre"],
        $datos["antecedente"],
        $datos["descripcion"],
        $datos["documento"]
    );
    $correcto = $stmt->execute();
    $stmt->close();

    return $correcto;
}
//FUncion de eliminar criminales
function eliminar_criminales($conexion,$datos)
{
    $sql ="DELETE FROM criminales WHERE=?";
    $stmt = $conexion->prepare($sql);
    $stmt ->bind_param("s",$datos["documento"]);
    $correcto=$stmt->execute();
    $stmt->close();
    return $correcto;
}
//Buscar criminales
function obtener_criminales($conexion, $documento)
{
    $criminales = [];

    if ($documento === "") {
        $resultado = $conexion->query("SELECT * FROM criminales ORDER BY nombre_criminal");
    } else {
        $stmt = $conexion->prepare("SELECT * FROM criminales WHERE documento = ?");
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();
    }

    // Bucle: pasamos cada fila de la base de datos al array
    while ($fila = $resultado->fetch_assoc()) {
        $criminales[] = $fila;
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    return $criminales;
}
//Mostrar los datos de un criminal en la pantalla, usando htmlspecialchars para evitar inyecciones de código
function mostrar_criminal($criminal)
{
    echo "Nombre: " . htmlspecialchars($criminal["nombre_criminal"]) . "<br>";
    echo "Documentación: " . htmlspecialchars($criminal["documento"]) . "<br>";
    echo "Delito: " . htmlspecialchars($criminal["delito"]) . "<br>";
    echo "Descripción del delito: " . htmlspecialchars($criminal["descripcion_delito"]) . "<br><br>";
}

function consultar_inicio_sesion($nombre,$contrasena){
    $conexion = conectar();
        if ($nombre === "" || $contrasena === "") {
            alerta_campo();
        } else{
            $query = "SELECT * FROM usuarios WHERE nombre_usuario= ? AND contr=?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ss", $nombre, $contrasena);
            $stmt->execute();
            $resultado = $stmt->get_result();
              if ($resultado->num_rows > 0) {
                $_SESSION["usuario"]=$nombre;
                $_SESSION["contra"]=$contrasena;
                //Divido las filas en un array en $fila
                $fila = $resultado->fetch_assoc();
                $rol = $fila['rol'];
                if ($rol === "administrador") {
                    header("Location: usuarios/menu_administrador.php");
                    exit;
                } else if($rol==="policia"){
                    header("Location:usuarios/menu_policia.php");
                    exit;
                }else{
                    header("Location:usuarios/menu_invitado.php");
                    exit;
                }

        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
            echo $mensaje;
        }
        }
      
        $stmt->close();
        $conexion->close();
}

//Usuarios:

//Mostrar los datos de un usuario en la pantalla, usando htmlspecialchars para evitar inyecciones de código

   function mostrar_usuario($resultado)
{
    while ($usuario = $resultado->fetch_assoc()) {

        echo "Nombre: " . htmlspecialchars($usuario["nombre_usuario"]) . "<br>";
        echo "ID: " . htmlspecialchars((string)$usuario["id"]) . "<br>";
        echo "Rol: " . htmlspecialchars($usuario["rol"]) . "<br><br>";

    }

}
//Creamos usuarios
function crear_usuario($conexion,$datos){
    $guardar="INSERT INTO usuarios (nombre_usuario,contr,rol) VALUES(?,?,?)";
        $stmt=$conexion->prepare($guardar);
        //Se debe poner el tipo (string, int ) por cada variable        
        $stmt->bind_param("sss", $datos["nombre"],$datos["contr"],$datos["rol"]);   
        if($stmt->execute()){
            $stmt->close();
            return true ;       
        }else{
            $stmt->close();
            return false;
        }
                
    }

//Creamos buscar usuarios
        
function buscar_usuarios($conexion,$nombre){
     $sql = "SELECT * FROM usuarios WHERE nombre_usuario=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        file_put_contents(
        "historial.txt",
        date("H:i") . " Busqueda usuario" . PHP_EOL,
        FILE_APPEND | LOCK_EX
        );
        $stmt->close();
        return $resultado;
}
//Eliminar usuario
function eliminar_usuario($conexion,$datos){
    $sql ="DELETE FROM usuarios WHERE  id=?";
    $stmt=$conexion->prepare($sql);
    $stmt->bind_param("i",$datos["id"]);
     if ($stmt->affected_rows > 0) {
            echo"Usuario eliminado correctamente.<br><br>";

            file_put_contents(
                __DIR__ . "/historial.txt",
                date("H:i") . " Eliminación de usuario ID: "
                . $_SESSION["buscar_usuario"] . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
            $stmt->close();

        } else {
            echo "No se encontró ningún usuario con ese ID.<br><br>";
            $stmt->close();
        }
}

//Modificar usuario
function modificar_usuario($conexion,$datos){
 $sql = "UPDATE usuarios SET nombre_usuario=?,contr=?,rol=? WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssi", $nombre,$contr,$rol,$buscar);
                if($stmt->execute()){
                    
                    echo "Usuario modificado";

                    file_put_contents(
                        "historial.txt",
                        date("H:i") . " modificar usuario: " . $_SESSION["registro_pendiente"]["nombre"] . PHP_EOL,
                        FILE_APPEND | LOCK_EX
                    );
                    unset($_SESSION["registro_pendiente"]);
                }else{
                    echo "Error al modificar el usuario: " . $stmt->error;
                }
            $stmt->close();    
}

?>
