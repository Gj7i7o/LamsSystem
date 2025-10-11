<?php

/*Modelo de la Entrada*/

class EntradasModel extends Query
{
    private $cantidad, $id;
    public function __construct()
    {
        parent::__construct();
    }

    /*getCategory: Toma todas las categorías de la base de datos*/
    public function getProduct()
    {
        $sql = "SELECT * FROM producto";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getEntrada()
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
