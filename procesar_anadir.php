<?php
include('conecta.php');

if(isset($_POST['anadir'])){
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $publicacion = $_POST['publicacion'];
    $isbn = $_POST['isbn'];
    $n_totales = $_POST['n_totales'];
    $n_disponibles = $_POST['n_disponibles'];
    $sinopsis = $_POST['sinopsis'];

    $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales) 
    VALUES ('$titulo', '$autor', $publicacion, '$isbn', '$sinopsis', $n_disponibles, $n_totales)";

    $conexion->query($sql);
}
$conexion->close();

header('Location: index.php');
?>