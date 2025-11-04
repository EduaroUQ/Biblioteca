<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <style>
        /* ===== Estilo base ===== */
        body {
            font-family: "Poppins", sans-serif;
            background: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 40px;
        }

        /* ===== Título principal ===== */
        h1 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 40px;
        }

        /* ===== Secciones (cada bloque de formulario) ===== */
        h2 {
            color: #1565c0;
            border-left: 5px solid #64b5f6;
            padding-left: 10px;
            margin-top: 50px;
        }

        /* ===== Formularios ===== */
        form {
            background: #ffffff;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-top: 15px;
            margin-bottom: 30px;
            max-width: 800px;
        }

        /* Etiquetas y campos */
        label {
            display: inline-block;
            margin: 10px 10px;
            font-weight: 500;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            font-family: inherit;
            font-size: 1rem;
            padding: 8px 10px;
            border: 2px solid #bbdefb;
            border-radius: 8px;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 5px rgba(66, 165, 245, 0.4);
        }

        /* ===== Botones ===== */
        button,
        input[type="submit"] {
            background: #42a5f5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover,
        input[type="submit"]:hover {
            background: #1e88e5;
            transform: scale(1.03);
        }

        button:active,
        input[type="submit"]:active {
            transform: scale(0.97);
        }

        /* ===== Separadores ===== */
        hr {
            border: none;
            border-top: 2px solid #e0e0e0;
            margin: 40px 0;
        }

        /* ===== Párrafos (catálogo) ===== */
        p {
            background: #e3f2fd;
            padding: 8px 14px;
            border-radius: 8px;
            width: fit-content;
            margin: 6px 0;
        }
    </style>
</head>

<body>
    <!-- Importamos la creación de la base de datos y las tablas una vez que se abra la página -->
    <?php require('crear_tablas.php');
    require('conecta.php'); ?>
    <h1>Biblioteca</h1>
    <h2>Registrar un Nuevo Lector</h2>

    <form action="procesar_registro.php" method="post">
        <label for="lector">Nombre y apellidos: <input type="text" name="lector"></label>
        <br><br>
        <label for="dni">DNI: <input type="text" name="dni"></label>
        <input type="submit" value="Registrar" name="registrar">
    </form>
    <?php
    session_start();
    if (!empty($_SESSION['msg'])) {
        echo "<p>" . $_SESSION['msg'] . "</p>";
        unset($_SESSION['msg']);
    }
    ?>

    <hr>
    <h2>Realizar un préstamo</h2>
    <form action="procesar_prestamo.php" method="post">
        <label for="lector">
            Lector: <select name="lector" required>
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                echo "<option selected disabled>Seleccione un lector</option>";
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
            Libro: <select name="nombre" required>
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                echo "<option selected disabled>Seleccione un libro</option>";
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
    <?php
    if (!empty($_SESSION['msg_prestamo'])) {
        echo "<p>" . $_SESSION['msg_prestamo'] . "</p>";
        unset($_SESSION['msg_prestamo']);
    }
    ?>

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
    <?php
    if (!empty($_SESSION['msg_devolver'])) {
        echo "<p>" . $_SESSION['msg_devolver'] . "</p>";
        unset($_SESSION['msg_devolver']);
    }
    ?>
    <hr>
    <h2>Añadir libro al catálogo</h2>
    <form action="procesar_anadir.php" method="post">
        <label for="titulo">Título del libro: <input type="text" name="titulo" required></label>
        <label for="autor">Autor: <input type="text" name="autor" required></label>
        <label for="publicacion">Año de publicación: <input type="number" name="publicacion" required></label>
        <br><br>
        <label for="isbn">ISBN: <input type="text" name="isbn" required></label>
        <label for="n_totales">Nº Libros: <input type="number" name="n_totales" required></label>
        <label for="n_disponibles">Nº Disponibles: <input type="number" name="n_disponibles" required></label>
        <br><br>
        <label for="sinopsis">Sinopsis: <textarea name="sinopsis" cols=90 rows=5 required></textarea></label>
        <br>
        <br>
        <button type="submit" value="anadir libro" name="anadir">Añadir libro</button>
    </form>
    <?php
    if (!empty($_SESSION['msg_anadir'])) {
        echo "<p>" . $_SESSION['msg_anadir'] . "</p>";
        unset($_SESSION['msg_anadir']);
    }
    ?>
    <hr>
    <h2>Dar de baja lector</h2>
    <form action="procesar_baja.php" method="post">
        <label for="lector">
            Lector: <select name="lector">
                <!--Rellenamos el select con los datos de la tabla-->
                <?php
                echo "<option selected disabled>Seleccione un lector</option>";
                $comprobar = "SELECT * FROM lectores WHERE estado=TRUE";
                $registro = $conexion->query($comprobar);
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                ?>
            </select>
        </label>
        <button type="submit" value="baja_lector" name="baja_lector">Dar de baja</button>
    </form>
    <?php
    if (!empty($_SESSION['msg_baja'])) {
        echo "<p>" . $_SESSION['msg_baja'] . "</p>";
        unset($_SESSION['msg_baja']);
    }
    ?>
    <hr>
    <h2>Catálogo de libros disponibles</h2>
    <?php
    $sql = "SELECT nombre FROM libros WHERE n_disponibles > 0";
    $registro = $conexion->query($sql);
    while ($tupla = $registro->fetch_assoc()) {
        $titulo = $tupla['nombre'];
        echo "<p>- $titulo</p>";
    }
    ?>

    <hr>
    <h2>Préstamos realizados</h2>
    <form action="procesar_consultar.php" method="post">
        <label for="lector"> Seleccione el lector:
            <select name="lector">
                <?php
                $comprobar = "SELECT * FROM lectores";
                $registro = $conexion->query($comprobar);
                echo "<option selected disabled>Seleccione un lector</option>";
                //recorremos las tuplas
                while ($resultado = $registro->fetch_assoc()) {
                    echo "<option value='$resultado[id]'>$resultado[lector] - $resultado[dni]</option>";
                }
                ?>
            </select>
        </label>
        <button type="submit" name="consultar">Consultar</button>
    </form>
    <?php
    if (!empty($_SESSION['msg_consulta'])) {
        echo $_SESSION['msg_consulta'];
        unset($_SESSION['msg_consulta']);
    }
    ?>

    <?php $conexion->close(); ?>
</body>

</html>