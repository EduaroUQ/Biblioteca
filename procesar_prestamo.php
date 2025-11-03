<?php
include('conecta.php');
// Conectamos a la base de datos
$conexion->select_db($database);

// Código PHP para procesar el formulario de registrar
if (isset($_POST['prestar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $nombre_libro = $conexion->real_escape_string($_POST['nombre']);

    // Realizar la inserción en la base de datos
    $registrar_lector = "INSERT INTO lectores (lector, dni, estado, n_prestado) 
                        VALUES ('$lector', '$dni', '$estado', '$prestamos')";

    if ($conexion->query($registrar_lector)) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar" . $conexion->error;
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    // header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    // exit;
}

$conexion->close();
