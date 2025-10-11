<?php

/*Modelo de la Categoría*/

class CategoriasModel extends Query
{
    private $name, $des, $id;
    public function __construct()
    {
        parent::__construct();
    }

    /*getCategory: Toma todas las categorías de la base de datos*/
    public function getCategory()
    {
        $sql = "SELECT * FROM categoria";
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
        $sql = "UPDATE categoria SET estado = 'Inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
