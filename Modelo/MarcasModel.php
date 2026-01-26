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

    /*getCount: Cuenta las marcas según el estado y búsqueda*/
    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM marca $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value, string $estado): string
    {
        $conditions = [];
        if ($estado != "todo") {
            $conditions[] = "estado = '$estado'";
        }
        if (!empty($value)) {
            $conditions[] = "id LIKE '%$value%' OR nombre LIKE '%$value%'";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarMarcas: Toma todas las marcas de la base de datos filtrando por estado y búsqueda*/
    public function tomarMarcas(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM marca $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarMarcasAc: Toma todas las marcas activas (para selects)*/
    public function tomarMarcasAc()
    {
        $sql = "SELECT * FROM marca WHERE estado = 'activo'";
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
        $verificar = "SELECT * FROM marca WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "UPDATE marca SET nombre = ? WHERE id = ?";
            $datos = array($this->nombre, $this->id);
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

    /*editarMarca: Hace la consulta SQL que traerá la marca que posteriormente se modificará*/
    public function editarMarca(int $id)
    {
        $sql = "SELECT * FROM marca WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*tieneProductos: Verifica si la marca tiene productos activos relacionados*/
    public function tieneProductos(int $id)
    {
        $sql = "SELECT COUNT(*) as total FROM producto WHERE idmarca = $id AND estado = 'activo'";
        $data = $this->select($sql);
        return $data['total'] > 0;
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
