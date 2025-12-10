<?php

/*Modelo de la Categoría*/

class categoriasModel extends query
{
    private $name, $des, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM categoria";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getCategory: Toma todas las categorías de la base de datos*/
    public function getActCategoria(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM categoria WHERE estado = 'activo'" : "SELECT * FROM categoria WHERE estado = 'activo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*getCategory: Toma todas las categorías de la base de datos*/
    public function getInaCategoria(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM categoria WHERE estado = 'Inactivo'" : "SELECT * FROM categoria WHERE estado = 'Inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*storeCategory: Guarda la categoría, y además verifica si la categoría existe, en base al nombre ingresado*/
    public function storeCategory(string $name, string $des)
    {
        $this->name = $name;
        $this->des = $des;
        $verificar = "SELECT * FROM categoria WHERE nombre = '$this->name'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO categoria (nombre, descrip, estado) VALUES (?,?,'activo')";
            $datos = array($this->name, $this->des);
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

    /*modifyCategory: Modifica la categoría*/
    public function modifyCategory(string $name, string $des, int $id)
    {
        $this->name = $name;
        $this->des = $des;
        $this->id = $id;
        $sql = "UPDATE categoria SET nombre = ?, descrip = ? WHERE id = ?";
        $datos = array($this->name, $this->des, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editCategory: Hace la consulta SQL que traerá la categoría que posteriormente se modificará*/
    public function editCategory(int $id)
    {
        $sql = "SELECT * FROM categoria WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteCategory: Hace la consulta SQL que traerá la categoría que posteriormente se eliminará*/
    public function deleteCategory(int $id)
    {
        $sql = "UPDATE categoria SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteCategory: Hace la consulta SQL que traerá la categoría que posteriormente se eliminará*/
    public function activarCategoria(int $id)
    {
        $sql = "UPDATE categoria SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
