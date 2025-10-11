<?php

/*Conexión con la base de datos*/

class Conexion
{
    private $conect;
    public function __construct()
    {
        $pdo = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";.DB_CARSET.";
        try {
            $this->conect = new PDO($pdo, DB_USER, DB_PASS);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión " . $e->getMessage();
        }
    }

    public function conect()
    {
        return $this->conect;
    }
}
