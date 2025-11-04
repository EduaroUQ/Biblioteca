<?php
include('conecta.php');
session_start();
// Código PHP para procesar el formulario de registrar
if (isset($_POST['registrar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $dni = $conexion->real_escape_string($_POST['dni']);

    // Realizar la inserción en la base de datos
    $registrar_lector = "INSERT INTO lectores (lector, dni, estado, n_prestado)
                        VALUES ('$lector', '$dni', True, 0)";

    if ($conexion->query($registrar_lector)) {
        // echo "Registro exitoso";
        $_SESSION['msg'] = "Registro exitoso";
    } else {
        // echo "Error al registrar" . $conexion->error;
        $_SESSION['msg'] = "No se pudo registrar";
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}

$conexion->close();
