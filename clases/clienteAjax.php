<?php

require '../configuracion/configDataBase.php';
require 'clienteFunciones.php';

$datos = [];

if(isset($_POST['action'])){
    $action = $_POST['action'];

    $database = new configDataBase();
    $conectar = $database->conexionDataBase();

    if($action == 'usuarioExiste'){
        $datos['ok']= existeUsuario($_POST['usuarioCliente'],$conectar);
    }elseif($action == 'emailExiste'){
        $datos['ok']= existeEmail($_POST['emailCliente'],$conectar);

    }
}
echo json_encode($datos);
?>