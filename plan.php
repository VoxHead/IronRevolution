<?php

// CONFIGURACION DEL TOKEN PARA CIFRAR LA INFORMACION DE DESCRIPCION
require 'configuracion/configProyecto.php';
// CONDIGURACION DE LA BASE DE DATOS
require 'configuracion/configDataBase.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

$sqlShow = $conectar->prepare("SELECT id, nombre_plan, descripcion_plan, precio_plan,beneficios
    FROM planes WHERE disponibilidad_plan = 1");
$sqlShow->execute();
$resultPlan = $sqlShow->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- Link Iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- link estilos.css -->
    <link rel="stylesheet" href="./estilos/styles.css">
</head>

<body class="body">
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
                    <!-- Comprobar si hay un login -->
                    <?php 
                        if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
                    ?>
                    <a href="#" class="btn btn-success me-5">
                        <i class="fa-solid fa-user me-1"></i>
                        <?php echo $_SESSION['usuario']; ?>
                    </a>
                    <?php
                        } else {
                            
                    ?> <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->

    <section class="d-flex align-items-center min-vh-100 flex-column">
        <h1 class="pcustom2">Elije tu plan y empieza a entrenar!</h1>
        <div class="container mt-4">
            <div class="row justify-content-center text-center">

                <!--Cards-->

                <?php foreach($resultPlan as $rowPlan) {
                        ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title pcustom card_title"><?php echo $rowPlan["nombre_plan"] ?></h2>
                            <p class="card-text text-muted lead"><?php echo $rowPlan["descripcion_plan"] ?></p>
                            <p class="precio">$<?php echo number_format($rowPlan["precio_plan"],2,".",",") ?></p>
                            <h3 class="pcustom">Beneficios</h3>

                            <p class="mb-5"><?php echo $rowPlan["beneficios"]?> </p>
                            <a href="productos.php" type="button" class="w-100 btn btn-lg btn-primary">Ir</a>

                        </div>
                    </div>
                </div>


                <?php }?>
            </div>
    </section>
             <!-- Footer -->
             <footer class="footer bg mt-auto d-flex p-5">
        <div class="align-items-center container-fluid ms-3">
            <a href="productos.php" class="navbar-brand">
                <div class="d-flex mx-5">
                    <img class="log" src="imagenes/logo.png" alt="" srcset="">
                    <h2 class="m-2 mx-5"><strong id="nombreEmpresa">Iron Revolution</strong></h2>
                </div>
            </a>
        </div>
        <div>
            <p class=""> Contactanos: <a
                    href="https://www.google.com/intl/es-419/gmail/about/">IronRevolution@gmail.com</a></p>
        </div>

    </footer>
    <div class="bg text-center p-1">
        <p>&copy; 2020–2023 Iron Revolution SAS <a href="./manual/manual_usuario.pdf"
                download="manual_usuario.pdf">Manual de usuario</a></p>
    </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                crossorigin="anonymous">

            </script>
            <script>
            function añadirPlan(id, token) {
                let url = 'clases/carritoCompra.php';
                let formData = new FormData()
                formData.append('id', id)
                formData.append('token', token)

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            let elemento = document.getElementById("num_cart")
                            elemento.innerHTML = data.numero
                        }
                    })
            }
            </script>


</html>