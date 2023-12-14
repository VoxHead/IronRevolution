<?php

// CONFIGURACION DEL TOKEN PARA CIFRAR LA INFORMACION DE DESCRIPCION
require 'configuracion/configProyecto.php';
// CONDIGURACION DE LA BASE DE DATOS
require 'configuracion/configDataBase.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

$sql = $conectar->prepare("SELECT id, nombre_producto, descripcion_producto, precio_producto
    FROM productos_tienda WHERE disponibilidad_producto = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// session_destroy();
//print_r($_SESSION);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


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

                    ?>  <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container pt-5">
            <div class="row row-cols-1 row-cols-sm-3 row-cols-md-3 g-3">
                <?php foreach ($resultado as $row) { ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <?php
                            $id = $row['id'];
                            
                            $imagen = "imagenes/productos/$id/productoMain.png";

                            if (!file_exists($imagen)) {
                                $imagen = "imagenes/fotoPredeterminada.png";
                            }
                            ?>
                        <img class="center" src="<?php echo $imagen; ?>" alt="" height="500px">
                        <div class="card-body">
                            <h3 class="card-title mt-3 mb-4 fs-3 fw-semibold">
                                <?php echo $row['nombre_producto']; ?>
                            </h3>
                            <p class="card-text fs-4">
                                <?php echo "$" . number_format($row['precio_producto'], 0, ',', '.') . " COP"; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary" type="button"
                                        onclick="añadirProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha256', $row['id'], PASSWORD_TOKEN); ?>')">Agregar
                                        al Carrito</button>
                                </div>
                                <a href="detallesProductos.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha256', $row['id'], PASSWORD_TOKEN); ?>"
                                    class="btn btn-success">Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
    
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

    <!-- Link js Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
    function añadirProducto(id, token) {
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
                    let elemento = document.getElementById('num_cart');
                    elemento.innerHTML = data.numero;
                }
            })
    }
    </script>
</body>

</html>