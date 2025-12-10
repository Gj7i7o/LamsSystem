<?php

/*Modelo del Proveedor*/

class proveedoresModel extends query
{
    private $name, $ape, $rif, $dir, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM proveedor";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getProveedores: Toma todos los proveedores de la base de datos*/
    public function getInaProveedores(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM proveedor WHERE estado = 'Inactivo'" : "SELECT * FROM proveedor WHERE estado = 'Inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*getProveedores: Toma todos los proveedores de la base de datos*/
    public function getActProveedores(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM proveedor WHERE estado = 'activo'" : "SELECT * FROM proveedor WHERE estado = 'activo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*storeProveedores: Guarda el proveedor, y además verifica si el proveedor existe, en base al nombre, apellido y rif ingresados*/
    public function storeProveedores(string $name, string $ape, string $rif, string $dir)
    {
        $this->name = $name;
        $this->ape = $ape;
        $this->rif = $rif;
        $this->dir = $dir;
        $verificar = "SELECT * FROM proveedor WHERE nombre = '$this->name' AND apellido = '$this->ape' AND rif = '$this->rif'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO proveedor (nombre, apellido, rif, direccion, estado) VALUES (?,?,?,?,'activo')";
            $datos = array($this->name, $this->ape, $this->rif, $this->dir);
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

    /*modifyProveedores: Modifica el proveedor*/
    public function modifyProveedores(string $name, string $ape, string $rif, string $dir, int $id)
    {
        $this->name = $name;
        $this->ape = $ape;
        $this->rif = $rif;
        $this->dir = $dir;
        $this->id = $id;
        $sql = "UPDATE proveedor SET nombre = ?, apellido = ?, rif = ?, direccion = ? WHERE id = ?";
        $datos = array($this->name, $this->ape, $this->rif, $this->dir, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editProveedores: Hace la consulta SQL que traerá al proveedor que posteriormente se modificará*/
    public function editProveedores(int $id)
    {
        $sql = "SELECT * FROM proveedor WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteProveedor: Hace la consulta SQL que traerá al proveedor que posteriormente se eliminará*/
    public function deleteProveedores(int $id)
    {
        $sql = "UPDATE proveedor SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteProveedor: Hace la consulta SQL que traerá al proveedor que posteriormente se eliminará*/
    public function activarProveedores(int $id)
    {
        $sql = "UPDATE proveedor SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
