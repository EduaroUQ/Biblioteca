<?php
include('conecta.php');

if (isset($_POST['devolver'])) {
    $valor = $_POST['prestamo'];

    $partes = explode("-", $valor);

    $id_nombre = (int)$partes[0];
    $id_lector = (int)$partes[1];

    $sql = "DELETE FROM prestamos WHERE id_libro = $id_nombre AND id_lector = $id_lector";
    $conexion->query($sql);
}
$conexion->close();

header('Location: index.php');