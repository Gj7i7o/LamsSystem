<?php

/*Modelo de la Marca*/

class MarcasModel extends Query
{
    private $name, $id;
    public function __construct()
    {
        parent::__construct();
    }

    /*getMarca: Toma todas las marcas de la base de datos*/
    public function getMarca()
    {
        $sql = "SELECT * FROM marca";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*storeMarca: Guarda la marca, y además verifica si la marca existe, en base al nombre ingresado*/
    public function storeMarca(string $name)
    {
        $this->name = $name;
        $verificar = "SELECT * FROM marca WHERE nombre = '$this->name'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO marca (nombre, estado) VALUES (?,'activo')";
            $datos = array($this->name);
            $data = $this->save($sql, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    /*modifyMarca: Modifica la marca*/
    public function modifyMarca(string $name, int $id)
    {
        $this->name = $name;
        $this->id = $id;
        $sql = "UPDATE marca SET nombre = ? WHERE id = ?";
        $datos = array($this->name, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editMarca: Hace la consulta SQL que traerá la marca que posteriormente se modificará*/
    public function editMarca(int $id)
    {
        $sql = "SELECT * FROM marca WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteMarca: Hace la consulta SQL que traerá la marca que posteriormente se eliminará*/
    public function deleteMarca(int $id)
    {
        $sql = "UPDATE marca SET estado = 'Inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
