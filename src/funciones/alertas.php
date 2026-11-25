
<?php

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

# Confirmación antes de registrar
function alerta_confirmacion() {
    echo '<p>Confirma tu selección</p>';

    echo '<form method="POST">
        <button type="submit" name="aceptar">Sí, continuar</button>
        <button type="submit" name="cancelar">No, cancelar</button>
    </form>';
}
//Aqui pongo una funcion para registrar en el historial, ya que se repite en varios archivos
function registrar_historial($accion)
{
    $linea = date("H:i") . " " . $accion . PHP_EOL;
    file_put_contents(__DIR__ . "/../historial.txt", $linea, FILE_APPEND | LOCK_EX);
}
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

//Esto sirve parabuscar un criminal en la base de datos, si no se pasa un documento, devuelve todos los criminales
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

function consultar($nombre,$contrasena){
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
?>
