<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['baja_lector'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);

    // Verificar que no tenga libros prestados
    $verificar_prestamos = "SELECT * FROM lectores WHERE n_prestado>0";
    $resultado = $conexion->query($verificar_prestamos);

    if ($resultado->num_rows > 0) {
        echo "Devuelve todos los libros prestados, chorizo";
    } else {
        // Realizar la actualización del estado en la base de datos
        $actualizar_estado = "UPDATE lectores SET estado=FALSE WHERE id=$lector";

        if ($conexion->query($actualizar_estado)) {
            echo "Baja exitosa";
        } else {
            echo "Error al dar de baja" . $conexion->error;
        }
    }
}

$conexion->close();
