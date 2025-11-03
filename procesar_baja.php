<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['baja_lector'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);

    // Verificar que no tenga libros prestados
    $verificar_estado = "SELECT * FROM lectores WHERE estado=TRUE";
    $resultado = $conexion->query($verificar_estado);
    $lector_resultado = $resultado->fetch_assoc();
    if ($resultado->num_rows <= 0) {
        echo "No hay lectores en alta";
    } else {
        if ($lector_resultado['n_prestado'] > 0) {
            echo "Devuelve los libros, chorizo";
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
}

$conexion->close();
