<?php

/*Modelo de la Entrada*/

class EntradasModel extends Query
{
    private $cantidad, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM entradaproducto";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getEntrada: Toma todas las entradas de la base de datos*/
    public function getEntradaProducto(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT ep.id, ep.cantidad, ep.precio, p.nombre AS nombre
        FROM entradaproducto ep LEFT JOIN producto p ON ep.idproducto = p.id " :
            "SELECT ep.id, ep.cantidad, ep.precio, p.nombre AS nombre
        FROM entradaproducto ep LEFT JOIN producto p ON ep.idproducto = p.id WHERE p.estado = 'activo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getEn()
    {
        $sql = "SELECT ep.id, ep.cantidad, ep.precio, p.nombre AS nombre, e.fecha AS fecha, e.hora AS hora
        FROM entradaproducto ep LEFT JOIN producto p ON ep.idproducto = p.id LEFT JOIN entrada e ON ep.identrada = e.id ";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*modifyProduct: Modifica el producto*/
    public function modifyEntrada(string $cantidad, int $id)
    {
        $this->cantidad = $cantidad;
        $this->id = $id;
        $sql = "UPDATE producto SET cantidad = ? WHERE id = ?";
        $datos = array($this->cantidad, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editProduct: Hace la consulta SQL que traerá el producto que posteriormente se modificará*/
    public function editEntrada(int $id)
    {
        $sql = "SELECT * FROM producto WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
