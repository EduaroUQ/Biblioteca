<?php
include('conecta.php');
session_start();
// Código PHP para procesar el formulario de añadir un libro
if (isset($_POST['anadir'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $nombre = $conexion->real_escape_string($_POST['titulo']);
    $autor = $conexion->real_escape_string($_POST['autor']);
    $publicacion = $conexion->real_escape_string($_POST['publicacion']);
    $isbn = $conexion->real_escape_string($_POST['isbn']);
    $sinopsis = $conexion->real_escape_string($_POST['sinopsis']);
    $n_disponibles = $conexion->real_escape_string($_POST['n_disponibles']);
    $n_totales = $conexion->real_escape_string($_POST['n_totales']);
    // Hacemos la consulta para saber si ya existe ese libro
    $verificar_libro = "SELECT * FROM libros WHERE isbn = '$isbn'";
    $resultado = $conexion->query($verificar_libro);
    // Si hay un libro se procesa lo siguiente
    if ($resultado->num_rows > 0) {
        // El libro ya existe, actualizamos n_totales y n_disponibles
        $libro = $resultado->fetch_assoc();
        $nuevo_total = $libro['n_totales'] + $n_totales;
        $nuevo_disponible = $libro['n_disponibles'] + $n_disponibles;
        $actualizar_libro = "UPDATE libros SET n_totales = $nuevo_total, n_disponibles = $nuevo_disponible WHERE isbn = '$isbn'";
        if ($conexion->query($actualizar_libro)) {
            $_SESSION['msg_anadir'] = "Libro actualizado correctamente.";
        } else {
            $_SESSION['msg_anadir'] = "Error al actualizar: " . $conexion->error;
        }
    } else {
        // El libro no existe, lo insertamos como nuevo
        $aniadir_libro = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales)
                      VALUES ('$nombre', '$autor', $publicacion, '$isbn', '$sinopsis', $n_disponibles, $n_totales)";
        if ($conexion->query($aniadir_libro)) {
            $_SESSION['msg_anadir'] = "Libro añadido correctamente.";
        } else {
            $_SESSION['msg_anadir'] = "Error al anadir: " . $conexion->error;
        }
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}

$conexion->close();
