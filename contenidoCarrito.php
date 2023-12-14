<?php

// CONFIGURACION DEL TOKEN PARA CIFRAR LA INFORMACION DE DESCRIPCION
require 'configuracion/configProyecto.php';
// CONDIGURACION DE LA BASE DE DATOS
require 'configuracion/configDataBase.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$listaCarrito = array();

$planes = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$listaPlanes = array();

if ($productos != null) {
    foreach ($productos as $claveId => $cantidadProducto) {
        $sql = $conectar->prepare("SELECT id, nombre_producto, precio_producto, descuento_producto, $cantidadProducto AS cantidadProducto
        FROM productos_tienda WHERE id = ? AND disponibilidad_producto = 1");
        $sql->execute([$claveId]);
        $listaCarrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}


// session_destroy();

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
                        <div class="dropdown">
                            <button class="btn btn btn-success me-5 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user me-1"></i><?php echo $_SESSION['usuario']; ?></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" onclick="<?php session_destroy();?>">Cerrar Sesion</a></li>
                            </ul>
                        </div>    
                        <?php
                        } else {
                            
                    ?>  <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </header>
    <!-- CONTENIDO -->
    <main>
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($listaCarrito == null) {
                            echo '<tr><td colspan="5" class="text-center"><b>Usted no tiene productos en el carrito</b></td></tr>';
                        } else {
                            $totalCompra = 0;
                            foreach ($listaCarrito as $producto) {
                                $id = $producto['id'];
                                $nombreProducto = $producto['nombre_producto'];
                                $precioProducto = $producto['precio_producto'];
                                $descuentoProducto = $producto['descuento_producto'];
                                $cantidadProducto = $producto['cantidadProducto'];
                                $precioDescuento = $precioProducto - (($precioProducto * $descuentoProducto) / 100);
                                $subTotalCompra = $cantidadProducto * $precioDescuento;
                                $totalCompra += $subTotalCompra;
                                ?>
                        <tr>
                            <td>
                                <?php echo $nombreProducto; ?>
                            </td>
                            <td>
                                <?php echo SIGNO_MONEDA . number_format($precioProducto, 0, ',', '.') . " COP"; ?>
                            </td>
                            <td>
                                <input type="number" min="1" max="10" step="1" value="<?php echo $cantidadProducto; ?>"
                                    size="5" id="cantidadProducto_<?php echo $id; ?>"
                                    onchange="actualizarCantidad(this.value, <?php echo $id; ?>)">
                            </td>
                            <td>
                                <div id="subTotalCompra_<?php echo $id; ?>" name="subTotalCompra[]">
                                    <?php echo SIGNO_MONEDA . number_format($subTotalCompra, 0, ',', '.'); ?>
                                </div>
                            </td>
                            <td>
                                <a href="#" id="eliminarProducto" class="btn btn-danger btn-sm"
                                    data-bs-id="<?php echo $id; ?>" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar">
                                    Eliminar Producto
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <h4 id="total">
                                    <?php echo SIGNO_MONEDA . number_format($totalCompra, 0, ',', '.'); ?>
                                </h4>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            <?php 
                if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
            ?>                      
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <a href="#" class="btn btn-success btn-lg">Ya estas loggueado</a>
                    </div>
                </div>
            <?php
            } else {      
            ?>
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <a href="login.php" class="btn btn-primary btn-lg">Realizar Pago</a>
                    </div>
                </div>
            <?php
            }
            ?>



            
        </div>
        <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalEliminarLabel">Alerta</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Esta seguro de eliminar el producto de la lista?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-eliminar" class="btn btn-danger"
                            onclick="eliminar()">Eliminar</button>
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
    let eliminarModal = document.getElementById('modalEliminar');
    eliminarModal.addEventListener('show.bs.modal', function(event) {
        let valorBoton = event.relatedTarget
        let id = valorBoton.getAttribute('data-bs-id')
        let botonEliminar = eliminarModal.querySelector('.modal-footer #btn-eliminar')
        botonEliminar.value = id;
    })


    function actualizarCantidad(cantidadProducto, id) {
        let url = 'clases/actuCantiCarrito.php';
        let formData = new FormData()
        formData.append('accion', 'agregar')
        formData.append('id', id)
        formData.append('cantidadProducto', cantidadProducto)
        fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if (data.ok) {
                    let subTotalAgregado = document.getElementById('subTotalCompra_' + id);
                    subTotalAgregado.innerHTML = data.subTotal;

                    let total = 0.00;
                    let listaTotal = document.getElementsByName('subTotalCompra[]');

                    for (let i = 0; i < listaTotal.length; i++) {
                        total += parseInt(listaTotal[i].innerHTML.replace(/[$.]/g, ''));
                    }

                    const convertidor = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP',
                        minimumFractionDigits: 0
                    })

                    document.getElementById('total').innerHTML = convertidor.format(total);
                }
            })
    }

    function eliminar() {
        let buttonEliminar = document.getElementById('btn-eliminar')
        let id = buttonEliminar.value

        let url = 'clases/actuCantiCarrito.php';
        let formData = new FormData()
        formData.append('accion', 'eliminar')
        formData.append('id', id)

        fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if (data.ok) {
                    location.reload()
                }
            })
    }
    </script>
</body>

</html>