<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['prestar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $nombre_libro = $conexion->real_escape_string($_POST['nombre']);

    // 1. Verificar si el lector ya tiene prestado ese libro
    $verificar_prestamo = "SELECT * FROM prestamo WHERE id_lector = $lector AND id_libro = $nombre_libro";
    $resultado_verificacion = $conexion->query($verificar_prestamo);

    if ($resultado_verificacion->num_rows > 0) {
        echo "Este lector ya tiene prestado este libro.";
    } else {
        // 2. Verificar disponibilidad del libro
        $consulta_disponibilidad = "SELECT n_disponibles FROM libros WHERE id = $nombre_libro";
        $resultado_disponibilidad = $conexion->query($consulta_disponibilidad);

        if ($fila_libro = $resultado_disponibilidad->fetch_assoc()) {
            if ($fila_libro['n_disponibles'] > 0) {
                // 3. Insertar préstamo
                $registrar_prestamo = "INSERT INTO prestamo VALUES ($lector, $nombre_libro)";
                if ($conexion->query($registrar_prestamo)) {
                    echo "Registro exitoso";

                    // Actualizar número de préstamos del lector
                    $consulta_prestamo = "SELECT n_prestado FROM lectores WHERE id=$lector";
                    $resultado_prestamo = $conexion->query($consulta_prestamo);
                    if ($fila = $resultado_prestamo->fetch_assoc()) {
                        $prestamo_nuevo = $fila['n_prestado'] + 1;
                        $conexion->query("UPDATE lectores SET n_prestado=$prestamo_nuevo WHERE id=$lector");
                    }

                    // Actualizar disponibilidad del libro
                    $nuevo_stock = $fila_libro['n_disponible'] - 1;
                    $conexion->query("UPDATE libros SET n_disponibles=$nuevo_stock WHERE id=$nombre_libro");
                } else {
                    echo "Error al registrar: " . $conexion->error;
                }
            } else {
                echo "No hay ejemplares disponibles de este libro.";
            }
        } else {
            echo "Libro no encontrado.";
        }
    }
}

$conexion->close();
