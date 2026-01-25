<?php

/*Modelo del Proveedor: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class proveedoresModel extends query
{
    private $nombre, $rif, $direccion, $telefono, $persona_contacto, $id;
    public function __construct()
    {
        parent::__construct();
    }

    /*getCount: Cuenta los proveedores según el estado y búsqueda*/
    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM proveedor $filters";
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
            $conditions[] = "(rif LIKE '%$value%' OR nombre LIKE '%$value%' OR direccion LIKE '%$value%' OR telefono LIKE '%$value%' OR persona_contacto LIKE '%$value%')";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarProveedores: Toma todos los proveedores de la base de datos filtrando por estado y búsqueda*/
    public function tomarProveedores(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM proveedor $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarProveedoresAc: Toma todos los proveedores activos (para selects)*/
    public function tomarProveedoresAc()
    {
        $sql = "SELECT * FROM proveedor WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisProveedor: Guarda el proveedor, y además verifica si el proveedor existe,
    en base al rif ingresado, comparando con la base de datos*/
    public function regisProveedor(string $nombre, string $rif, string $direccion, string $telefono = '', string $persona_contacto = '')
    {
        $this->nombre = $nombre;
        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->persona_contacto = $persona_contacto;
        $verificar = "SELECT * FROM proveedor WHERE rif = '$this->rif'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO proveedor (nombre, rif, direccion, telefono, persona_contacto, estado) VALUES (?,?,?,?,?,'activo')";
            $datos = array($this->nombre, $this->rif, $this->direccion, $this->telefono, $this->persona_contacto);
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

    /*modifProveedor: Modifica el proveedor seleccionado acorde al id*/
    public function modifProveedor(string $nombre, string $rif, string $direccion, string $telefono, string $persona_contacto, int $id)
    {
        $this->nombre = $nombre;
        $this->rif = $rif;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->persona_contacto = $persona_contacto;
        $this->id = $id;
        $sql = "UPDATE proveedor SET nombre = ?, rif = ?, direccion = ?, telefono = ?, persona_contacto = ? WHERE id = ?";
        $datos = array($this->nombre, $this->rif, $this->direccion, $this->telefono, $this->persona_contacto, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editarProveedor: Hace la consulta SQL que traerá al proveedor que posteriormente se modificará*/
    public function editarProveedor(int $id)
    {
        $sql = "SELECT * FROM proveedor WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*desProveedor: Hace la consulta SQL que traerá al proveedor que posteriormente cambiará su estado a inactivo*/
    public function desProveedor(int $id)
    {
        $sql = "UPDATE proveedor SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*actProveedor: Hace la consulta SQL que traerá al proveedor que posteriormente se cambiará su estado a activo*/
    public function actProveedores(int $id)
    {
        $sql = "UPDATE proveedor SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
