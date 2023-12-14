<?php

// CONFIGURACION DEL TOKEN PARA CIFRAR LA INFORMACION DE DESCRIPCION
require 'configuracion/configProyecto.php';
// CONDIGURACION DE LA BASE DE DATOS
require 'configuracion/configDataBase.php';
require 'clases/clienteFunciones.php';

$database = new configDataBase();
$conectar = $database->conexionDataBase();

$error = [];

if (!empty($_POST)) {
    $nombres = trim($_POST['nombresCliente']);
    $apellidos = trim($_POST['apellidosCliente']);
    $email = trim($_POST['emailCliente']);
    $celular = trim($_POST['celularCliente']);
    $cedula = trim($_POST['dniCliente']);
    $usuario = trim($_POST['usuarioCliente']);
    $contraseña = trim($_POST['contraseñaCliente']);
    $reContraseña = trim($_POST['reContraseñaCliente']);

    // VALIDACIONES REALIZADAS EN EL FORMULARIO PARA EVITAR ERRORES EN LOS REGISTROS
    if (igualNulo([$nombres, $apellidos, $email, $celular, $cedula, $usuario, $contraseña, $reContraseña])) {
        $error[] = "HAY CAMPOS VACIOS, VERIFIQUE DE NUEVO EL REGISTRO.";
    }

    if (!igualEmail($email)) {
        $error[] = "LA DIRECCION DE CORREO ELECTRONICO NO ES VALIDA.";
    }

    if (!validarContraseña($contraseña, $reContraseña)) {
        $error[] = "LAS CONTRASEÑAS NO SON IGUALES.";
    }

    if (existeUsuario($usuario, $conectar)) {
        $error[] = "EL NOMBRE DE USUARIO $usuario YA SE ENCUENTRA REGISTRADO.";
    }

    if (existeEmail($email, $conectar)) {
        $error[] = "EL CORREO ELECTRONICO $email YA SE ENCUENTRA REGISTRADO EN EL SISTEMA.";
    }

    // SI NO EXISTE NINGUN ERROR EN LAS VALIDACIONES SE PUEDE REGISTRAR EL USUARIO
    if (count($error) == 0) {
        $id = registraCliente([$nombres,$apellidos,$email,$celular,$cedula], $conectar);

        if ($id > 0) {
            $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $token = generarToken();
            if (!registraUsuario([$usuario, $contraseña_hash, $token, $id], $conectar)){
                $error[] = "ERROR AL REGISTRAR EL CLIENTE."; 
            }
        } else {
            $error[] = "ERROR AL REGISTRAR EL CLIENTE.";
        }
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
                    <a href="login.php" class="btn bg-success px-3 me-2 me-5" id="login">
                        <span id="num_cart" class="badge bg-success">Login</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="text-center fs-1 mt-3">Registrate</h2>
                    <br>
                    <br>
                    <?php echo  mostrarError($error);?>
                    <br>
                    <br>

                    <form class="form_register row g-3 card d-flex flex-row p-5" action="registroCliente.php"
                        method="post" autocomplete="off">

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="text" name="nombresCliente" id="nombresCliente"
                                placeholder="Ingrese su nombre" requireda>
                            <label class="form-label" for="nombresCliente"><span class="text-danger m-2">*
                                </span>Ingrese su nombre</label>
                            <small id="disNomCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="text" name="apellidosCliente" id="apellidosCliente"
                                placeholder="Ingrese su apellido" requireda>
                            <label class="form-label" for="apellidosCliente"><span class="text-danger">*
                                </span>Apellidos</label>
                            <small id="disApeCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="email" name="emailCliente" id="emailCliente"
                                placeholder="Ingrese se email" requireda>
                            <label class="form-label" for="emailCliente"><span class="text-danger">* </span>Correo
                                Electronico</label>
                            <small id="disEmailCliente" class="form-text text-muted">Este campo es obligatorio</small>
                            <span id="validaEmail" class="text-danger"></span>
                        </div>

                        <div class="form-floating col-md-6">
                            <input class="form-control" type="tel" name="celularCliente" id="celularCliente"
                                placeholder="Ingrese se numero celular" requireda>
                            <label class="form-label" for="celularCliente"><span class="text-danger">* </span>Numero
                                Celular</label>
                            <small id="disCelCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="form-floating col-md-6">
                            <input class="form-control" type="number" name="dniCliente" id="dniCliente"
                                placeholder="Ingrese su identificacion personal" requireda>
                            <label class="form-label" for="dniCliente"><span class="text-danger">* </span>Identificacion
                                Personal</label>
                            <small id="disDniCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="text" name="usuarioCliente" id="usuarioCliente"
                                placeholder="Ingrese su nombre de usuario" requireda>
                            <label class="form-label" for="usuarioCliente"><span class="text-danger">*
                                </span>Usuario</label>
                            <small id="disUserCliente" class="form-text text-muted">Este campo es obligatorio</small>
                            <span id="validaUsuario" class="text-danger"></span>
                        </div>

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="password" name="contraseñaCliente" id="contraseñaCliente"
                                placeholder="Ingrese su contraseña" requireda>
                            <label class="form-label" for="contraseñaCliente"><span class="text-danger">*
                                </span>Contraseña</label>
                            <small id="disConCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="form-floating col-md-4">
                            <input class="form-control" type="password" name="reContraseñaCliente"
                                id="reContraseñaCliente" placeholder="Ingrese de nuevo su contraseña" requireda>
                            <label class="form-label" for="reContraseñaCliente"><span class="text-danger">*
                                </span>Repetir Contraseña</label>
                            <small id="disReConCliente" class="form-text text-muted">Este campo es obligatorio</small>
                        </div>

                        <div class="col-12 mt-5">
                            <button class="btn btn-success" type="submit">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Link js Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>


    <script>
    //Usuario
    let txtUsuario = document.getElementById("usuarioCliente")
    txtUsuario.addEventListener("blur", function() {
        usuarioExiste(txtUsuario.value)
    }, false)

    function usuarioExiste(usuario) {
        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "usuarioExiste")
        formData.append("usuarioCliente", usuario)

        fetch(url, {

                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById("usuarioCliente").value = ""
                    document.getElementById("validaUsuario").innerHTML = 'Usuario no disponible'
                } else {
                    document.getElementById("validaUsuario").innerHTML = ""
                }
            })
    }

    //Email

    let txtEmail = document.getElementById("emailCliente")
    txtEmail.addEventListener("blur", function() {
        emailExiste(txtEmail.value)
    }, false)



    function emailExiste(email) {
        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "emailExiste")
        formData.append("emailCliente", email)

        fetch(url, {

                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById("emailCliente").value = ""
                    document.getElementById("validaEmail").innerHTML = 'Email no disponible'
                } else {
                    document.getElementById("validaEmail").innerHTML = ""
                }
            })
    }
    </script>

</body>

</html>