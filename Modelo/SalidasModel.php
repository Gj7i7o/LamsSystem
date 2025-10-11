<?php

/*Modelo de la Salida*/

class SalidasModel extends Query
{
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

    public function getSalida()
    {
        $sql = "SELECT sp.id, sp.cantidad, sp.precio, p.nombre AS nombre, s.fecha AS fecha, s.hora AS hora
        FROM salidaproducto sp LEFT JOIN producto p ON sp.idproducto = p.id LEFT JOIN salida s ON sp.idsalida = s.id";
        $data = $this->selectAll($sql);
        return $data;
    }
}