<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['prestar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $nombre_libro = $conexion->real_escape_string($_POST['nombre']);

    // Verificar si el lector ya tiene prestado ese libro
    $verificar_prestamo = "SELECT * FROM prestamo WHERE id_lector = $lector AND id_libro = $nombre_libro";
    $resultado_verificacion = $conexion->query($verificar_prestamo);

    if ($resultado_verificacion->num_rows > 0) {
        echo "Este lector ya tiene prestado este libro. No se puede realizar otro préstamo.";
    } else {
        // Realizar la inserción en la base de datos
        $registrar_prestamo = "INSERT INTO prestamo VALUES ($lector, $nombre_libro)";

        if ($conexion->query($registrar_prestamo)) {
            echo "Registro exitoso";

            // Recuperamos la cantidad de préstamo para aumentarle uno
            $consulta_prestamo = "SELECT n_prestado FROM lectores WHERE id=$lector";
            $resultado_prestamo = $conexion->query($consulta_prestamo);

            if ($fila = $resultado_prestamo->fetch_assoc()) {
                $prestamo_anterior = $fila['n_prestado'];
                $prestamo_nuevo = $prestamo_anterior + 1;
                // Incrementar libro en el número de préstamos del lector
                $actualizar = "UPDATE lectores SET n_prestado=$prestamo_nuevo WHERE id=$lector";
                $conexion->query($actualizar) or die("Error al actualizar los datos");
                echo "Actualizado con éxito. Nuevo stock: $prestamo_nuevo";
            }
        } else {
            echo "Error al registrar: " . $conexion->error;
        }
    }
}

$conexion->close();
