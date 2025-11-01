<?php

/*Modelo de la Salida*/

class SalidasModel extends Query
{
    private $cantidad, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM salidaproducto";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getEntrada: Toma todas las entradas de la base de datos*/
    public function getSalida(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT sp.id, p.nombre AS producto, sp.cantidad, sp.precio, s.fecha, s.hora, u.nombre AS usuario FROM salidaproducto sp LEFT JOIN producto p ON sp.idproducto = p.id LEFT JOIN salida s ON sp.idsalida = s.id LEFT JOIN usuario u ON s.idusuario = u.id" :
            "SELECT sp.id, p.nombre AS producto, sp.cantidad, sp.precio, s.fecha, s.hora, u.nombre AS usuario FROM salidaproducto sp LEFT JOIN producto p ON sp.idproducto = p.id LEFT JOIN salida s ON sp.idsalida = s.id LEFT JOIN usuario u ON s.idusuario = u.id LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }
}