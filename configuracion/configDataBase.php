<?php
class configDataBase
{
    private $hostname = "localhost";
    private $dataBaseName = "ecommerce_proyecto";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conexionDataBase()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname . ";dbname=" . $this->dataBaseName . ";charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,

            ];
            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $pdo;
        } catch (PDOException $e) {
            echo 'Error 404 conexion base de datos: ' . $e->getMessage();
            exit;
        }
    }

}
?>