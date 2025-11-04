<?php
include('conecta.php');
session_start();
// Código PHP para procesar el formulario de consultar los libros prestados por un lector
if (isset($_POST['consultar'])) {
    $mensaje = "";
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $_POST['lector'];
    // Hacemos la consulta de todos los libros prestados por el lector
    $libros_prestados = "SELECT id_lector, (SELECT nombre from libros WHERE libros.id = prestamos.id_libro) AS libro_nombre FROM prestamos WHERE id_lector=$lector";
    $comprobar = $conexion->query($libros_prestados);
    // Si tiene libros se imprime una lista
    if ($comprobar->num_rows > 0) {
        $mensaje = "<h3>Libros prestados:</h3><ul>";
        while ($tupla = $comprobar->fetch_assoc()) {

            $titulo = $tupla['libro_nombre'];
            $mensaje .= "<li>$titulo</li>";
        }
        $mensaje .= "</ul>";
    } else {
        // Caso contrario se manda un mensaje de incentivo
        $mensaje = "Ponte a leer un libro cariño mío";
    }

    $_SESSION['msg_consulta'] = $mensaje;
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}

$conexion->close();
