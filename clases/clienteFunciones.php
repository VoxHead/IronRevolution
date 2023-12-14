<?php

// VALIDACIONES
function igualNulo(array $parametros){
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function igualEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true; 
    }
    return false;
}

function validarContraseña($contraseña, $reContraseña) {
    // STRCMP COMPARA DOS STRINGS DE FORMA BINARIA
    if (strcmp($contraseña, $reContraseña) === 0) {
        return true;
    }
    return false;
}

// REGISTRO CLIENTE Y CREACION DE USUARIO
function generarToken() {
    return md5(uniqid(mt_rand(), false));
}

function registraCliente(array $datos, $conexion) {
    $sql = $conexion->prepare('INSERT INTO clientes (nombres, apellidos, email, celular, cedula, estatus, fecha_alta) VALUES (?,?,?,?,?,1,now())');

    if ($sql->execute($datos)) {
        return $conexion->lastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos, $conexion) {
    $sql = $conexion->prepare('INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES (?,?,?,?)');

    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

// CONSULTA PARA VERIFICAR SI EL USUARIO YA EXISTE
function existeUsuario($usuario, $conexion) {
    $sql = $conexion->prepare('SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1');
    $sql->execute([$usuario]);
    
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

// CONSULTA PARA VERIFICAR SI EL CORREO YA FUE REGISTRADO
function existeEmail($email, $conexion) {
    $sql = $conexion->prepare('SELECT id FROM clientes WHERE email LIKE ? LIMIT 1');
    $sql->execute([$email]);
    
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function mostrarError(array $error) {
    if (count($error) > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul>';
        foreach ($error as $errores) {
            echo '<li><strong>'.$errores.'</strong></li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}
?>