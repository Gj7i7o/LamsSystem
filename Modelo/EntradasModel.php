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
    public function getEntrada(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT ep.id, p.nombre AS producto, pr.nombre AS proveedor, ep.cantidad, e.fecha, e.hora FROM entradaproducto ep LEFT JOIN producto p ON ep.idproducto = p.id LEFT JOIN entrada e ON ep.identrada = e.id LEFT JOIN proveedor pr ON e.idproveedor = pr.id" :
            "SELECT ep.id, p.nombre AS producto, pr.nombre AS proveedor, ep.cantidad, e.fecha, e.hora FROM entradaproducto ep LEFT JOIN producto p ON ep.idproducto = p.id LEFT JOIN entrada e ON ep.identrada = e.id LEFT JOIN proveedor pr ON e.idproveedor = pr.id LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }
}
