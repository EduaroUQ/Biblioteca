<?php
include('conecta.php');

// Código PHP para procesar el formulario de registrar
if (isset($_POST['aniadir'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $autor = $conexion->real_escape_string($_POST['autor']);
    $publicacion = $conexion->real_escape_string($_POST['publicacion']);
    $isbn = $conexion->real_escape_string($_POST['isbn']);
    $sinopsis = $conexion->real_escape_string($_POST['sinopsis']);
    $n_disponibles = $conexion->real_escape_string($_POST['n_disponibles']);
    $n_totales = $conexion->real_escape_string($_POST['n_totales']);

    $verificar_libro = "SELECT * FROM libros WHERE isbn = '$isbn'";
    $resultado = $conexion->query($verificar_libro);

    if ($resultado->num_rows > 0) {
        // El libro ya existe, actualizamos n_totales y n_disponibles
        $libro = $resultado->fetch_assoc();
        $nuevo_total = $libro['n_totales'] + 1;
        $nuevo_disponible = $libro['n_disponible'] + 1;  //***AÚN POR VERIFICAR***

        $actualizar_libro = "UPDATE libros SET n_totales = $nuevo_total, n_disponible = $nuevo_disponible WHERE isbn = '$isbn'";
        if ($conexion->query($actualizar_libro)) {
            echo "Libro actualizado correctamente.";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
    } else {
        // El libro no existe, lo insertamos como nuevo
        $aniadir_libro = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponible, n_totales)
                      VALUES ('$nombre', '$autor', $publicacion, '$isbn', '$sinopsis', $n_disponibles, $n_totales)";
        if ($conexion->query($aniadir_libro)) {
            echo "Libro añadido correctamente.";
        } else {
            echo "Error al registrar: " . $conexion->error;
        }
    }
}

$conexion->close();

header("location: index.php");
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