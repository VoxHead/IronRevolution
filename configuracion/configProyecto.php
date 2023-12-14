<?php
    // TOKEN PARA CIFRAR LA DESCRIPCION DE CADA PRODUCTO
    define ("PASSWORD_TOKEN" , "Ja39.Da.23-Jr9#");
    define ("SIGNO_MONEDA", "$");
    session_start();
    $numCart = 0;
    if (isset($_SESSION['carrito']['productos'])) {
        $numCart = count($_SESSION['carrito']['productos']);
    }
?>