<?php
include('conecta.php');
// Conectamos a la base de datos
$conexion->select_db($database);

// Código PHP para procesar el formulario de registrar
if (isset($_POST['registrar'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);
    $dni = $conexion->real_escape_string($_POST['dni']);
    $estado = $conexion->real_escape_string($_POST['estado']);
    $prestamos = $conexion->real_escape_string($_POST['prestamos']);
    $registrar = $conexion->real_escape_string($_POST['registrar']);

    // Realizar la inserción en la base de datos
    $registrar_lector = "INSERT INTO lectores (lector, dni, estado, n_prestado)
                        VALUES ('$lector', '$dni', $estado, $prestamos)";

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de registro</title>
</head>

<body>
    <a href="index.php">Regresar al inicio</a>
</body>

</html>