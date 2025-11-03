<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
</head>

<body>
    <?php require('crear_tablas.php');
    require('conecta.php'); ?>
    <h1>Biblioteca</h1>
    <h2>Registrar un Nuevo Lector</h2>
    <form action="procesar_registro.php" method="post">
        <label for="lector">Nombre y apellidos: <input type="text" name="lector"></label>
        <br><br>
        <label for="dni">DNI: <input type="text" name="dni"></label>
        <!-- <br><br>
        <label for="estado">Estado: <select name="estado">
                <option value="1" selected>Alta</option>
                <option value="0">Baja</option>
            </select></label>
        <br><br>
        <label for="préstamos">Préstamos: <input type="number" name="prestamos" value="0"></label>
        <br><br> -->
        <input type="submit" value="Registrar" name="registrar">
    </form>
    <hr>
    <h2>Realizar un préstamo</h2>
    <form action="procesar_prestamo.php" method="post">
        <label for="lector">
            Lector: <select name="lector">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                $comprobar = "SELECT * FROM lectores WHERE estado=TRUE";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                ?>
            </select>
        </label>
        <label for="nombre">
            Libro: <select name="nombre">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                $comprobar = "SELECT * FROM libros WHERE n_disponibles > 0";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[nombre]</option>";
                }
                ?>
            </select>
        </label>
        <input type="submit" value="Realizar un préstamo" name="prestar">
    </form>
    <hr>
    <h2>Devolver un préstamo</h2>
    <form action="procesar_devolver.php" method="post">
        <label for="prestamo">Préstamo a devolver:
            <select name="prestamo" required>
                <option value="" selected disabled>Selecciona una opción</option>
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                $sql = "SELECT id_libro, id_lector,
                    (SELECT nombre FROM libros WHERE libros.id = prestamos.id_libro) AS libro_nombre,
                    (SELECT lector FROM lectores WHERE lectores.id = prestamos.id_lector) AS lector_nombre
                    FROM prestamos";
                $registro = $conexion->query($sql);
                while ($tupla = $registro->fetch_assoc()) {
                    $value = implode('-', [$tupla['id_lector'], $tupla['id_libro']]);
                    $texto = $tupla['lector_nombre'] . " - " . $tupla['libro_nombre'];
                    echo "<option value='$value'>$texto</option>";
                }
                ?>
            </select>
        </label>
        <button type="submit" value="devolver un prestamo" name="devolver">Devolver préstamo</button>
    </form>
    <hr>
    <h2>Añadir libro al catálogo</h2>
    <form action="procesar_anadir.php" method="post">
        <label for="titulo">Título del libro: <input type="text" name="titulo"></label>
        <label for="autor">Autor: <input type="text" name="autor"></label>
        <label for="publicacion">Año de publicación: <input type="number" name="publicacion"></label>
        <br><br>
        <label for="isbn">ISBN: <input type="text" name="isbn"></label>
        <label for="n_totales">Nº Libros: <input type="number" name="n_totales"></label>
        <label for="n_disponibles">Nº Disponibles: <input type="number" name="n_disponibles"></label>
        <br><br>
        <label for="sinopsis">Sinopsis: <textarea name="sinopsis" cols=110 rows=5></textarea></label>
        <br>
        <br>
        <button type="submit" value="anadir libro" name="anadir">Añadir libro</button>
    </form>


    <?php $conexion->close(); ?>
</body>

</html>