<?php
include('conecta.php');
session_start();
// Código PHP para procesar el formulario de dar de baja a un lector
if (isset($_POST['baja_lector'])) {
    // Obtener los datos del formulario con caracteres especiales:
    $lector = $conexion->real_escape_string($_POST['lector']);

    // Verificar que esté dado de alta
    $verificar_estado = "SELECT * FROM lectores WHERE estado=TRUE AND id=$lector";
    $resultado = $conexion->query($verificar_estado);
    if ($resultado->num_rows <= 0) {
        $_SESSION['msg_baja'] = "El lector no está dado de alta o no existe";
    } else {
        $lector_resultado = $resultado->fetch_assoc();
        // Se verifica que no tenga libros prestados
        if ($lector_resultado['n_prestado'] > 0) {
            $_SESSION['msg_baja'] = "Devuelve los libros, chorizo";
        } else {
            // Realizar la actualización del estado en la base de datos
            $actualizar_estado = "UPDATE lectores SET estado=FALSE WHERE id=$lector";
            if ($conexion->query($actualizar_estado)) {
                $_SESSION['msg_baja'] = "Baja exitosa";
            } else {
                $_SESSION['msg_baja'] = "Error al dar de baja" . $conexion->error;
            }
        }
    }
    // Redirigir a la misma página para que nos muestre la información actualizada
    header("Location: index.php");
    // "No me sigas mostrando esta página. Vuelve a cargar la URL que te paso".
    exit;
}

$conexion->close();
