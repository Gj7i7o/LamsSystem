<?php

/*Modelo de la marca: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class marcasModel extends query
{
    private $nombre, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM marca WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarMarcaIn: Toma todas las marcas de la base de datos que tengan el estado inactivo*/
    public function tomarMarcaIn(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM marca WHERE estado = 'inactivo'" : "SELECT * FROM marca WHERE estado = 'inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarMarcaAc: Toma todas las marcas de la base de datos que tengan el estado activo*/
    public function tomarMarcaAc(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM marca WHERE estado = 'activo'" : "SELECT * FROM marca WHERE estado = 'activo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisProveedor: Guarda la marca, y además verifica si la marca existe,
    en base al nombre ingresado, comparando con la base de datos*/
    public function regisMarca(string $nombre)
    {
        $this->nombre = $nombre;
        $verificar = "SELECT * FROM marca WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO marca (nombre, estado) VALUES (?,'activo')";
            $datos = array($this->nombre);
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

    /*modifMarca: Modifica la marca seleccionada acorde al id*/
    public function modifMarca(string $nombre, int $id)
    {
        $this->nombre = $nombre;
        $this->id = $id;
        $sql = "UPDATE marca SET nombre = ? WHERE id = ?";
        $datos = array($this->nombre, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editarMarca: Hace la consulta SQL que traerá la marca que posteriormente se modificará*/
    public function editarMarca(int $id)
    {
        $sql = "SELECT * FROM marca WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*desMarca: Hace la consulta SQL que traerá la marca que posteriormente se cambiará su estado a inactivo*/
    public function desMarca(int $id)
    {
        $sql = "UPDATE marca SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }


    /*actMarca: Hace la consulta SQL que traerá la marca que posteriormente se cambiará su estado a activo*/
    public function actMarca(int $id)
    {
        $sql = "UPDATE marca SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
