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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Quienes somos</title>
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

        <h1 class="pcustom2 text-center">¿Quienes somos?</h1>
        <div class="card container mt-5">
            <!-- Contenido -->
            <!-- Mision -->
            <div class="n_container">
                <div class="col-md-5">
                    <h2 class="card-title card_title n_title">Mision</h2>
                    <p class="paragraph lead">En Iron Revolution, nos comprometemos a inspirar y guiar a nuestros
                        miembros hacia un estilo de vida activo y saludable. Nos esforzamos por proporcionar un
                        ambiente acogedor y motivador donde cada persona, sin importar su nivel de condición física,
                        encuentre el apoyo necesario para alcanzar sus metas de bienestar. Nuestro objetivo es ser
                        más que un gimnasio; aspiramos a ser un centro integral de salud y fitness que fomente la
                        excelencia en el rendimiento físico, la nutrición adecuada y el equilibrio mental.</p>
                </div>
                <div class="col-md-5">
                    <img class="n_img" src="./imagenes/nosotros/img_one.jpg" width="500px">
                    <p class="pt-3"> En Iron Revolution, creemos en el poder transformador del ejercicio y nos dedicamos
                        a impulsar a
                        nuestra comunidad hacia una vida plena y activa.</p>
                </div>
            </div>
            <br>
            <!-- Vision -->
            <div class="n_container pb-5">
                <div class="col-md-5 order-md-2">
                    <h2 class="card-title card_title n_title text-center">Vision</h2>
                    <p class="lead paragraph">En Iron Revolution, nos visualizamos como un referente en la promoción de
                        la salud
                        y el bienestar
                        integral. Buscamos ser reconocidos como un espacio líder que va más allá de la simple actividad
                        física, siendo un
                        faro que guía a nuestra comunidad hacia un estilo de vida activo y saludable. Nos esforzamos por
                        ofrecer programas
                        innovadores, instalaciones de primera clase y un equipo dedicado que inspire a nuestros miembros
                        a superar sus
                        límites y alcanzar sus metas de fitness. Nuestra visión es crear un impacto duradero en la salud
                        y felicidad de
                        las personas, contribuyendo a una sociedad más fuerte y saludable. </p>
                </div>
                <div class="col-md-5 order-md-1">
                    <img class="n_img" src="./imagenes/nosotros/img_two.jpg" width="520" height="500">
                    <p class="pt-3">En Iron Revolution, aspiramos a ser la elección preferida para aquellos que buscan
                        un cambio
                        positivo en sus vidas a través del ejercicio, la nutrición y el bienestar mental.</p>
                </div>
            </div>
            <br>
            <!-- Metodo Entrenamiento -->
            <div class="n__container col-md-12">
                <h2 class="pb-5">Nuestro metodo Trainer & campo de entreno</h2>
                <p class="paragraph lead">En Iron Revolution, adoptamos un enfoque integral hacia el fitness con nuestro
                    exclusivo 'Método Trainer'. Este método se basa en la personalización y la variedad para
                    asegurar que cada miembro alcance sus objetivos de forma efectiva y sostenible. Nuestros
                    entrenadores altamente capacitados desarrollarán planes de entrenamiento personalizados que se
                    adaptan a las necesidades específicas de cada individuo, ya sea que estén buscando perder peso,
                    ganar músculo, mejorar la resistencia o simplemente mantenerse en forma.
                <h2 class="pt-5 pb-5">Descripcion del gimnasio & sus espacios</h2>
                <p class="paragraph lead">Iron Revolution es más que un lugar para hacer ejercicio; es un espacio
                    diseñado para
                    inspirar y motivar. Ubicado en Cra. 14 #6-41, Villanueva, Casanare, nuestro gimnasio
                    cuenta con instalaciones de
                    vanguardia que crean un ambiente propicio para el éxito de nuestros miembros.
                    Las instalaciones incluyen:</p>
                <p class="paragraph lead">Zona de Cardio: Equipada con lo último en máquinas de cardio, ofrecemos
                    una amplia gama de
                    opciones, desde cintas de correr hasta bicicletas estáticas, para satisfacer las
                    necesidades de
                    todos.</p>
                <p class="paragraph lead">Área de Pesas y Entrenamiento de Fuerza: Contamos con una extensa
                    selección de pesas libres,
                    máquinas de entrenamiento de fuerza y zonas específicas para entrenamientos de alta
                    intensidad.</p>
                <p class="paragraph lead">Estudios de Clases Grupales: Ofrecemos una variedad de clases grupales,
                    desde yoga y pilates hasta
                    entrenamientos de alta intensidad y spinning, impartidas por instructores
                    certificados.</p>
                <p class="paragraph lead">Área de Flexibilidad y Recuperación: Disponemos de espacios dedicados
                    para estiramientos, yoga
                    y servicios de recuperación como masajes y tratamientos de fisioterapia.</p>
                <img class="n_img pt-5" src="./imagenes/nosotros/img_three.jpg" width="600" height="600">
                <p class="pt-3">Nuestra mas reciente ampliación de Iron Revolution</p>
            </div>
        </div>


        <!-- Cards -->
        <div class="container d-flex cards text-center">
            <div class="gap n_card col-md-5">
                <img class=" rounded-circle" width="140" height="140" src="./imagenes/nosotros/img_four.jpg">
                <h2 class="fw-normal">Registrate</h2>
                <p>Registrate en Iron Revolution y haz parte de la familia fitness mas grande de Colombia</p>
                <p><a class="btn btn-primary" href="registroCliente.php">Ver mas</a></p>
            </div>
            <div class="gap n_card col-md-5 ">
                <img class=" rounded-circle" width="140" height="140" src="./imagenes/nosotros/img_five.jpg">
                <h2 class="fw-normal">Productos</h2>
                <p>Conoce mas sobre nuestros productos relacionados al mundo fitness.</p>
                <p><a class="btn btn-primary" href="productos.php">Ver mas</a></p>
            </div>
            <div class="gap n_card col-md-5">
                <img class=" rounded-circle" width="140" height="140" src="./imagenes/nosotros/img_six.jpg">
                <h2 class="fw-normal">Planes de Entrenamiento</h2>
                <p>Los mejores planes de entrenamiento al mejor precio, !Que estas esperando!</p>
                <p><a class="btn btn-primary" href="plan.php">Ver mas</a></p>
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