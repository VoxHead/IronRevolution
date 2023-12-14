<?php
require 'configuracion/configProyecto.php';
require 'configuracion/configDataBase.php';
require 'clases/clienteFunciones.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM usuarios WHERE usuario = :usuario";
    $stmt = $conectar->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verifica la contraseña utilizando password_verify
        if (password_verify($password, $row['password'])) {
            // Contraseña válida
            $_SESSION['usuario'] = $usuario;
            header("location: productos.php"); // Redirige a la página de inicio
            exit(); // Importante: detiene la ejecución del script después de la redirección
        } else {
            $error[] = "Usuario o contraseña incorrectos";
        }
    } else {
        $error[] = "Usuario o contraseña incorrectos";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Ecommerce</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Style Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- link estilos.css -->
    <link rel="stylesheet" href="./estilos/styles.css">
    <!-- link iconos font awesome -->
    <script src="https://kit.fontawesome.com/27d5659520.js" crossorigin="anonymous"></script>

</head>

<body class="bga">
    <header class="bg" data-bs-theme="dark">
        <div class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="align-items-center container-fluid ms-3">
                <a href="productos.php" class="navbar-brand">

                    <div class="d-flex mx-5">

                        <img class="log" src="imagenes/logo.png" alt="" srcset="">
                        <h2 class="m-2 mx-5"><strong id="nombreEmpresa">Iron Revolution</strong></h2>
                    </div>

                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="nosotros.php" class="nav-link mx-3">Nosotros</a>
                        </li>

                        <li class="nav-item">
                            <a href="plan.php" class="nav-link mx-3">Planes de Entrenamiento</a>
                        </li>

                        <li class="nav-item">
                            <a href="productos.php" class="nav-link">Catalogo</a>
                        </li>
                    </ul>
                    <a href="contenidoCarrito.php" class="btn btn-primary px-3 me-2 me-5" id="botonCompra">
                        <i class="fa-solid fa-cart-shopping me-1"></i><span id="num_cart" class="badge bg-primary">
                            <?php echo $numCart; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main class="form-login m-auto pt-5 card d-flex p-4 mt-5">


        <h2 class="text-center pb-3">Iniciar sesion</h2>
        <?php echo  mostrarError($error);?>



        <form class="row g-3" action="login.php" autocomplete="off" method="post">

            <div class="form-floating">
                <input class="form-control" name="usuario" id="usuario" type="text" placeholder="Usuario">
                <label for="usuario">Usuario</label>
            </div>

            <div class="form-floating">
                <input class="form-control" name="password" id="password" type="password" placeholder="Contraseña">
                <label for="password">Contraseña</label>
            </div>


            <div class="">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="row g-3">
            <a href="contenidoCarrito.php"> <button type="submit" class="btn btn-primary">Ingresar</button></a>
            </div>
            <hr>

            <div>
                No tienes cuenta? <a href="registroCliente.php">Registrate aqui!</a>
            </div>
        </form>

    </main>

    <!-- Link js Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>




</body>

</html>