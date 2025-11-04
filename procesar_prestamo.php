<?php
include('conecta.php');
session_start();
// Código PHP para procesar el formulario de prestar un libro
if (isset($_POST['prestar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $nombre_libro = $conexion->real_escape_string($_POST['nombre']);

    // Verificamos si el lector ya tiene prestado ese libro
    $verificar_prestamo = "SELECT * FROM prestamos WHERE id_lector = $lector AND id_libro = $nombre_libro";
    $resultado_verificacion = $conexion->query($verificar_prestamo);

    if ($resultado_verificacion->num_rows > 0) {
        $_SESSION['msg_prestamo'] = "Este lector ya tiene prestado este libro.";
    } else {
        // Verificamos la disponibilidad del libro
        $consulta_disponibilidad = "SELECT n_disponibles FROM libros WHERE id = $nombre_libro";
        $resultado_disponibilidad = $conexion->query($consulta_disponibilidad);

        if ($fila_libro = $resultado_disponibilidad->fetch_assoc()) {
            if ($fila_libro['n_disponibles'] > 0) {
                //Insertamos el préstamo
                $registrar_prestamo = "INSERT INTO prestamos VALUES ($lector, $nombre_libro)";
                if ($conexion->query($registrar_prestamo)) {
                    $_SESSION['msg_prestamo'] = "Préstamo exitoso";

                    // Actualizar número de préstamos del lector
                    $consulta_prestamo = "SELECT n_prestado FROM lectores WHERE id=$lector";
                    $resultado_prestamo = $conexion->query($consulta_prestamo);
                    if ($fila = $resultado_prestamo->fetch_assoc()) {
                        $prestamo_nuevo = $fila['n_prestado'] + 1;
                        $conexion->query("UPDATE lectores SET n_prestado=$prestamo_nuevo WHERE id=$lector");
                    }

                    // Actualizar disponibilidad del libro
                    $nuevo_stock = $fila_libro['n_disponibles'] - 1;
                    $conexion->query("UPDATE libros SET n_disponibles=$nuevo_stock WHERE id=$nombre_libro");
                    // Actualizar libros totales
                    $consulta_totales = "SELECT n_totales FROM libros WHERE id = $nombre_libro";
                    $resultado_totales = $conexion->query($consulta_totales);
                    $fila_total = $resultado_totales->fetch_assoc();
                    $nuevo_total = $fila_total['n_totales'] - 1;
                    $conexion->query("UPDATE libros SET n_totales=$nuevo_total WHERE id=$nombre_libro");
                } else {
                    $_SESSION['msg_prestamo'] = "Error al solicitar: " . $conexion->error;
                }
            } else {
                $_SESSION['msg_prestamo'] = "No hay ejemplares disponibles de este libro.";
            }
        } else {
            $_SESSION['msg_prestamo'] = "Libro no encontrado.";
        }
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}
$conexion->close();
