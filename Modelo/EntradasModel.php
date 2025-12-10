<?php

/*Modelo de la Entrada*/

class entradasModel extends query
{
    private $cantidad, $id, $lineas;
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

    /*storeEntrada: Guarda la marca, y además verifica si la marca existe, en base al nombre ingresado*/
    public function storeEntrada(string $lineas)
    {
        // $this->name = $name;
        $verificar = "SELECT * FROM entrada WHERE nombre = ''";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO entradaProducto (cantidad, precio, idproducto, identrada) VALUES (?,'activo')";
            $datos = array();
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
}
