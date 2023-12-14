<?php
require '../configuracion/configProyecto.php';
require '../configuracion/configDataBase.php';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id = isset($_POST['id']) ? ($_POST['id']) :0;
    
    if ($accion == 'agregar') {
        $cantidadProducto = isset($_POST['cantidadProducto']) ? $_POST['cantidadProducto'] :0;
        $respuesta = agregarNuevo($id, $cantidadProducto);
        if ($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            $datos ['ok'] = false;
        }
        $datos['subTotal'] = SIGNO_MONEDA . number_format($respuesta,0,',','.');
    } else if ($accion == 'eliminar'){
        $datos['ok'] = eliminar($id);
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);

function agregarNuevo($id, $cantidadProducto) {
    $totalFinal  = 0;
    if ($id > 0 && $cantidadProducto > 0 && is_numeric($cantidadProducto)) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidadProducto;

            $baseDatos = new configDataBase();
            $conectar = $baseDatos->conexionDataBase();

            $sql = $conectar->prepare("SELECT precio_producto, descuento_producto FROM productos_tienda WHERE id=? AND disponibilidad_producto = 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $precio_producto = $row['precio_producto'];
            $descuento_producto = $row['descuento_producto'];
            $precio_descuento = $precio_producto - (($precio_producto * $descuento_producto) / 100);
            $totalFinal = $cantidadProducto * $precio_descuento;

            return $totalFinal;
        }
    } else {
        return $totalFinal;
    }
}

function eliminar($id) {
    if ($id > 0) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    } else {
        return false;
    }
}






?>