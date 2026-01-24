<?php

/*Modelo del Producto: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class productosModel extends query
{
    private $codigo, $nombre, $precio, $categoria, $marca, $id;
    public function __construct()
    {
        parent::__construct();
    }

    /*getCount: Cuenta los productos según el estado y búsqueda*/
    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT p.id FROM producto p
            LEFT JOIN categoria c ON p.idcategoria = c.id
            LEFT JOIN marca m ON p.idmarca = m.id $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value, string $estado): string
    {
        $conditions = [];
        if ($estado != "todo") {
            $conditions[] = "p.estado = '$estado'";
        }
        if (!empty($value)) {
            $conditions[] = "(p.codigo LIKE '%$value%' OR p.nombre LIKE '%$value%' OR c.nombre LIKE '%$value%' OR m.nombre LIKE '%$value%')";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarProductos: Toma todos los productos de la base de datos filtrando por estado y búsqueda*/
    public function tomarProductos(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
            LEFT JOIN categoria c ON p.idcategoria = c.id
            LEFT JOIN marca m ON p.idmarca = m.id $filters ORDER BY p.id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarProductosAc: Toma todos los productos activos (para selects)*/
    public function tomarProductosAc()
    {
        $sql = "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id WHERE p.estado = 'activo'";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisProducto: Registra el producto, y además verifica si existe, en base al nombre y código ingresados, comparando
    con la base de datos*/
    public function regisProducto(string $codigo, string $nombre, float $precio, int $categoria, int $marca)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->marca = $marca;
        $verificar = "SELECT * FROM producto WHERE nombre = '$this->nombre' AND codigo = '$this->codigo'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO producto (codigo, nombre, precio, idcategoria, idmarca, cantidad, estado) VALUES (?,?,?,?,?,0,'activo')";
            $datos = array($this->codigo, $this->nombre, $this->precio, $this->categoria, $this->marca);
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

    /*modifProducto: Modifica el producto seleccionado acorde al id*/
    public function modifProducto(string $codigo, string $nombre, float $precio, int $categoria, int $marca, int $id)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->marca = $marca;
        $this->id = $id;
        $verificar = "SELECT * FROM producto WHERE codigo = '$this->codigo'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "UPDATE producto SET codigo = ?, nombre = ?, precio = ?, idcategoria = ?, idmarca = ? WHERE id = ?";
            $datos = array($this->codigo, $this->nombre, $this->precio, $this->categoria, $this->marca, $this->id);
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

    /*editarProducto: Hace la consulta SQL que traerá el producto que posteriormente se modificará*/
    public function editarProducto(int $id)
    {
        $sql = "SELECT * FROM producto WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*desProducto: Hace la consulta SQL que traerá el producto acorde al id, y le cambia su estado a inactivo*/
    public function desProducto(int $id)
    {
        $sql = "UPDATE producto SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*actProducto: Hace la consulta SQL que traerá al producto que posteriormente se cambiará su estado a activo*/
    public function actProducto(int $id)
    {
        $sql = "UPDATE producto SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
