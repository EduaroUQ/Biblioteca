<?php
include('conecta.php');
session_start();
if (isset($_POST['prestamo'])) {
    // Se asigna a las variables las entradas como posiciones de un array
    list($id_lector, $id_libro) = explode('-', $_POST['prestamo']);
    // Ahora se puede usar $id_lector e $id_libro para eliminar el préstamo
    $eliminar = "DELETE FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro";
    if ($conexion->query($eliminar)) {
        $_SESSION['msg_devolver'] = "Préstamo devuelto correctamente.";
        // Actualizar n_prestado del lector
        $conexion->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id = $id_lector");
        // Actualizar n_disponible del libro
        $conexion->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id = $id_libro");
        // Actualizar n_totales del libro
        $conexion->query("UPDATE libros SET n_totales = n_totales + 1 WHERE id = $id_libro");
    } else {
        $_SESSION['msg_devolver'] = "Error al devolver el préstamo: " . $conexion->error;
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}

$conexion->close();
