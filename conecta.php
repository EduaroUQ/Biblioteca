<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$database = "biblioteca";


$conexion = new mysqli($servidor, $usuario, $password);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
} else {
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conexion->query($sql)) {
        echo "Base de Datos creada correctamente";
    } else {
        echo "Error al crear";
    }
}
