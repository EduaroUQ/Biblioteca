<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
</head>

<body>
    <h1>Biblioteca</h1>
    <h2>Registrar un Nuevo Lector</h2>
    <form action="procesar_registro.php" method="post">
        <label for="lector">Nombre y apellidos: <input type="text" name="lector"></label>
        <br><br>
        <label for="dni">DNI: <input type="text" name="dni"></label>
        <br><br>
        <label for="estado">Estado: <select name="estado">
                <option value="1" selected>Alta</option>
                <option value="0">Baja</option>
            </select></label>
        <br><br>
        <label for="préstamos">Préstamos: <input type="number" name="prestamos" value="0"></label>
        <br><br>
        <input type="button" value="Registrar" name="registrar">
    </form>
    <hr>
    <h2>Realizar un préstamo</h2>
    <form action="procesar_prestamo.php" method="post">
        <label for="lector">
            Lector: <select name="lector">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                require 'conecta.php';
                $conexion->select_db($database);
                $comprobar = "SELECT * FROM lectores WHERE estado=TRUE";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                $conexion->close();
                ?>
            </select>
        </label>
        <label for="nombre">
            Libro: <select name="nombre">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                require 'conecta.php';
                $conexion->select_db($database);
                $comprobar = "SELECT * FROM libros WHERE disponibles > 0";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_row()) {
                    echo "<option value='$resultado[0]'>$resultado[0]</option>";
                }
                $conexion->close();
                ?>
            </select>
        </label>
        <input type="button" value="Realizar un préstamo" name="prestar">
    </form>
    <hr>
    <h2>Devolver un préstamos</h2>
    <form action="procesar_devolver.php" method="post">
        <label for="lector">
            Lector: <select name="lector">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                require 'conecta.php';
                $conexion->select_db($database);
                $comprobar = "SELECT * FROM lectores WHERE n_prestado>0";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                $conexion->close();
                ?>
            </select>
        </label>
        <label for="lector">
            Libro a devolver: <select name="nombre">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                require 'conecta.php';
                $conexion->select_db($database);
                $comprobar = "SELECT * FROM libros WHERE n_prestado>0";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                $conexion->close();
                ?>
            </select>
        </label>
    </form>
</body>

</html>