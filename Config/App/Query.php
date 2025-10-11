<?php

/*Es la clase encargada de recibir y mandar las consultas SQL a la base de datos*/

class Query extends Conexion
{
    private $pdo, $con, $sql, $datos;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }

    /*select: Selecciona un objeto/item de la base de datos*/
    public function select(string $sql)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        $data = $resul->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    /*selectAll: Selecciona todos los objetos/items de la base de datos*/
    public function selectAll(string $sql)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute();
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /*save: Función que guarda un objeto/item de la base de datos*/
    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if ($data) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }
}
