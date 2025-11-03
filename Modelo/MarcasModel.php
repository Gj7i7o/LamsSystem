<?php

/*Modelo de la Marca*/

class MarcasModel extends Query
{
    private $name, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM marca";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getMarca: Toma todas las marcas de la base de datos*/
    public function getInMarca(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM marca WHERE estado = 'inactivo'" : "SELECT * FROM marca WHERE estado = 'inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*getMarca: Toma todas las marcas de la base de datos*/
    public function getAcMarca(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM marca WHERE estado = 'activo'" : "SELECT * FROM marca WHERE estado = 'activo' LIMIT 5 OFFSET $offset";
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
        $sql = "UPDATE marca SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }


    /*activarMarca: Hace la consulta SQL que traerá la marca que posteriormente se eliminará*/
    public function activarMarca(int $id)
    {
        $sql = "UPDATE marca SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
