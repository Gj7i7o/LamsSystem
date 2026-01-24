<?php

/*Modelo de la categoría: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class categoriasModel extends query
{
    private $nombre, $descripcion, $id;
    public function __construct()
    {
        parent::__construct();
    }

    // public function getCountIn()
    // {
    //     $sql = "SELECT * FROM categoria WHERE estado = 'inactivo'";
    //     $data = $this->selectAll($sql);
    //     return count($data);
    // }

    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM categoria $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    public function filtersSQL(string $value, string $estado): string
    {
        $conditions = [];
        if ($estado != "todo") {
            $conditions[] = "estado = '$estado'";
        }
        if (!empty($value)) {
            $conditions[] = "(nombre LIKE '%$value%' OR descrip LIKE '%$value%')";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarCategoriaIn: Toma todas las categorías de la base de datos que tengan el estado inactivo*/
    // public function tomarCategoriasIn(int $page = 0)
    // {
    //     $offset = ($page - 1) * 5;
    //     $sql = $page <= 0 ? "SELECT * FROM categoria WHERE estado = 'inactivo'" : "SELECT * FROM categoria WHERE estado = 'inactivo' LIMIT 5 OFFSET $offset";
    //     $data = $this->selectAll($sql);
    //     return $data;
    // }

    /*tomarCategoriasAc: Toma todas las categorías activas (para selects)*/
    public function tomarCategoriasAc()
    {
        $sql = "SELECT * FROM categoria WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarCategorias: Toma todas las categorías de la base de datos filtrando por estado*/
    public function tomarCategorias($params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = $params["page"] <= 0 ? "SELECT * FROM categoria $filters ORDER BY id DESC" : "SELECT * FROM categoria $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisProveedor: Guarda la categoría, y además verifica si la categoría existe,
    en base al nombre ingresado, comparando con la base de datos*/
    public function regisCategoria(string $nombre, string $descripcion)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $verificar = "SELECT * FROM categoria WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO categoria (nombre, descrip, estado) VALUES (?,?,'activo')";
            $datos = array($this->nombre, $this->descripcion);
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

    /*modifCategoria: Modifica la categoría seleccionada acorde al id*/
    public function modifCategoria(string $nombre, string $descripcion, int $id)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id = $id;
        $verificar = "SELECT * FROM categoria WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "UPDATE categoria SET nombre = ?, descrip = ? WHERE id = ?";
            $datos = array($this->nombre, $this->descripcion, $this->id);
            $data = $this->save($sql, $datos);
            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    /*editarCategoria: Hace la consulta SQL que traerá la categoría que posteriormente se modificará*/
    public function editarCategoria(int $id)
    {
        $sql = "SELECT * FROM categoria WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*tieneProductos: Verifica si la categoría tiene productos activos relacionados*/
    public function tieneProductos(int $id)
    {
        $sql = "SELECT COUNT(*) as total FROM producto WHERE idcategoria = $id AND estado = 'activo'";
        $data = $this->select($sql);
        return $data['total'] > 0;
    }

    /*desCategoria: Hace la consulta SQL que traerá la categoría que posteriormente se cambiará su estado a inactivo*/
    public function desCategoria(int $id)
    {
        $sql = "UPDATE categoria SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*actCategoria: Hace la consulta SQL que traerá la categoría que posteriormente se cambiará su estado a activo*/
    public function actCategoria(int $id)
    {
        $sql = "UPDATE categoria SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
