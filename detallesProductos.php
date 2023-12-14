<?php

// CONFIGURACION DEL TOKEN PARA CIFRAR LA INFORMACION DE DESCRIPCION
require 'configuracion/configProyecto.php';
// CONDIGURACION DE LA BASE DE DATOS
require 'configuracion/configDataBase.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

// EL ID Y EL TOKEN DEL PRODUCTO VIAJAN POR LA URL, POR ESO SE UTILIZA EL METODO GET.
// SE UTILIZA ISSET PARA VALIDAR QUE ESTA DEFINIDA LA VARIABLE
$id = isset($_GET['id']) ? $_GET['id'] : '';
$tokenProducto = isset($_GET['token']) ? $_GET['token'] : '';

// SI ID O TOKEN ESTAN VACIOS, ENTONCES ERROR AL PROCESAR LA PETICION
if ($id == '' || $tokenProducto == '') {
    echo "ERROR AL PROCESAR LA PETICION POR FALTA DE ID O TOKEN";
    exit;
} else {
    // VARIABLE PARA DESENCRIPTAR EL SHA256 DONDE PROVIENE EL ID DEL PRODUCTO
    $tokenProducto_des = hash_hmac('sha256', $id, PASSWORD_TOKEN);

    if ($tokenProducto == $tokenProducto_des) {
        $sql = $conectar->prepare("SELECT count(id) FROM productos_tienda WHERE id=? AND disponibilidad_producto = 1");
        $sql->execute([$id]);
        $sqlCaracter = $conectar -> prepare("SELECT DISTINCT(det.id_caracteristica) AS idCat, cat.caracteristica FROM det_prod_caracter AS det INNER JOIN caracteristicas AS cat ON det.id_caracteristica=cat.id WHERE det.id_producto=?");
        $sqlCaracter -> execute(['id']);
        // CONDICIONAL IF PARA ENCONTRAR EL DATO
        if ($sql->fetchColumn() > 0) {
            $sql = $conectar->prepare("SELECT nombre_producto, descripcion_producto, precio_producto, descuento_producto FROM productos_tienda WHERE id=? AND disponibilidad_producto = 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre_producto = $row['nombre_producto'];
            $descripcion_producto = $row['descripcion_producto'];
            $precio_producto = $row['precio_producto'];
            $descuento_producto = $row['descuento_producto'];
            $precio_descuento = $precio_producto - (($precio_producto * $descuento_producto) / 100);
            $direccion_imagenes = 'imagenes/productos/' . $id . '/';
            $rutaImg = $direccion_imagenes . 'productoMain.png';

            // SI EL ARCHICO $rutaImg NO EXISTE, ENTONCES PROYECTE LA IMAGEN PREDETERMIANDA
            if (!file_exists($rutaImg)) {
                $rutaImg = 'imagenes/fotoPredeterminada.png';
            }

            $imagenes_productos = array();

            if (file_exists($direccion_imagenes)) {
                $direccion = dir($direccion_imagenes);

                while (($archivo = $direccion->read() != false)) {
                    if ($archivo != 'productoMain.jpg' && (strpos($archivo, 'jpg'))) {
                        $imagenes_productos[] = $direccion_imagenes . $archivo;
                    }
                }
                $direccion->close();
            }
            $sqlCaracter = $conectar -> prepare("SELECT DISTINCT(det.id_caracteristica) AS idCat, cat.caracteristica FROM det_prod_caracter AS det INNER JOIN caracteristicas AS cat ON det.id_caracteristica=cat.id WHERE det.id_producto=?");
            $sqlCaracter -> execute([$id]);
        } else {
            echo "ERROR AL PROCESAR LA PETICION POR FALTA DE ID O TOKEN";
            exit; 
        }
    } else {
        echo "ERROR AL PROCESAR LA PETICION POR FALTA DE ID O TOKEN";
        exit;
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

<body>

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
    <main class="mt-5">
        <div class="container">
            <div class="row">

                <div class="col-md-6 order-md-1">
                    <img class="img-fluid" src="<?php echo $rutaImg; ?>" alt="">
                </div>
                <div class="col-md-6 order-md-2">
                    <h1 class="mb-3">
                        <?php echo $nombre_producto; ?>
                    </h1>
                    <?php if ($descuento_producto > 0) { ?>
                    <p><del>
                            <?php echo SIGNO_MONEDA . number_format(($precio_producto), 0, ',', '.'); ?>
                        </del></p>
                    <h2>
                        <?php echo SIGNO_MONEDA . number_format(($precio_descuento), 0, ',', '.'); ?>
                        <small class="text-success">
                            <?php echo $descuento_producto; ?> % descuento
                        </small>
                    </h2>
                    <?php } else { ?>
                    <h1 class="mb-3">
                        <?php echo SIGNO_MONEDA . number_format(($precio_producto), 0, ',', '.'); ?>
                    </h1>
                    <?php } ?>
                    <p class="lead">
                        <?php echo $descripcion_producto; ?>
                    </p>

                    <div class="col-3 my-3">
                        <?php
                            while ($row_cat = $sqlCaracter->fetch(PDO::FETCH_ASSOC)) {
                                $idCat = $row_cat['idCat'];
                                echo $row_cat['caracteristica'];

                                echo "<select class='form-select' id='cat_$idCat'>";

                                $sqlDetalles = $conectar -> prepare('SELECT id, valor, stock FROM det_prod_caracter WHERE id_producto=? AND id_caracteristica=?');
                                $sqlDetalles -> execute([$id, $idCat]);

                                while ($row_detalles = $sqlDetalles -> fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option id='".$row_detalles['id']."'>".$row_detalles['valor']. "</option>";
                                }

                                echo "</select>";
                            }
                        ?>
                    </div>

                    <div class="col-2 my-3 mb-5">
                        Cantidad: <input type="number" class="form-control" name="cantidad" id="cantidad" min="1"
                            max="50" value="1">
                    </div>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <a href="contenidoCarrito.php" class="btn btn-primary" type="button" onclick="añadirProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $tokenProducto_des; ?>')">Comprar Ahora</a>
                        <button class="btn btn-outline-primary" type="button" id="botonAgregar" onclick="añadirProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $tokenProducto_des; ?>')">Agregar al Carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Link js Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
    function añadirProducto(id, cantidad, token) {
        let url = 'clases/carritoCompra.php';
        let formData = new FormData()
        formData.append('id', id)
        formData.append('cantidad', cantidad)
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