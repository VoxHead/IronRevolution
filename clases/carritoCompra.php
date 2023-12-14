<?php
require '../configuracion/configProyecto.php';

if (isset($_POST['id'])) {

    $id = $_POST['id'];
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;
    $token = $_POST['token'];

    $tokenCarritoDes = hash_hmac('sha256', $id, PASSWORD_TOKEN);

    if ($token == $tokenCarritoDes && $cantidad > 0 && is_numeric($cantidad)) {

        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }
        $datos['numero'] = count($_SESSION['carrito']['productos']);
        $datos['ok'] = true;
    } else {
        $datos['ok'] = false;
    }


} else {
    $datos['ok'] = false;
}

echo json_encode($datos);
?>