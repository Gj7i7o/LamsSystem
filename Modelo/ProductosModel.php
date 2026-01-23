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

    public function getCountIn()
    {
        $sql = "SELECT * FROM producto WHERE estado = 'inactivo'";
        $data = $this->selectAll($sql);
        return count($data);
    }

    public function getCountAc()
    {
        $sql = "SELECT * FROM producto WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarProductosAc: Toma todos los productos de la base de datos y contiene la paginación*/
    public function tomarProductos(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id" :
            "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getFilters($filters)
    {
        $filterConnect = "";
        if (empty($filters)) {
            return "";
        }
        if (isset($filters["estado"]) && $filters["estado"] != "") {
            $filterConnect += " AND p.estado = $filters ";
        }
        return $filterConnect;
    }

    /*tomarProductosIn: Toma todos los productos de la base de datos que tengan el estado inactivo y contiene la paginación*/
    public function tomarProductosIn(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id WHERE p.estado = 'inactivo' " :
            "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id WHERE p.estado = 'inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarProductosAc: Toma todos los productos de la base de datos que tengan el estado activo y contiene la paginación*/
    public function tomarProductosAc(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id WHERE p.estado = 'activo' " :
            "SELECT p.id, p.codigo, p.nombre, p.precio, p.cantidad, c.nombre AS categoria, m.nombre AS marca, p.estado FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id LEFT JOIN marca m ON p.idmarca = m.id WHERE p.estado = 'activo' LIMIT 5 OFFSET $offset";
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
        $sql = "UPDATE producto SET codigo = ?, nombre = ?, precio = ?, idcategoria = ?, idmarca = ? WHERE id = ?";
        $datos = array($this->codigo, $this->nombre, $this->precio, $this->categoria, $this->marca, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
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
