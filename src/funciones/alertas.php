
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

?>
