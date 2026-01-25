<?php

/*Modelo de la Salida*/

class salidasModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value): string
    {
        $conditions = [];
        if (!empty($value)) {
            $conditions[] = "(cod_docum LIKE '%$value%' OR total LIKE '%$value%')";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"]);
        $sql = "SELECT * FROM salida $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarSalida: Toma todas las salidas de la base de datos*/
    public function tomarSalida(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"]);
        $sql = $params["page"] <= 0 ? "SELECT id, cod_docum, total, fecha, hora, tipo_despacho FROM salida $filters ORDER BY id DESC" :
            "SELECT id, cod_docum, total, fecha, hora, tipo_despacho FROM salida $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisSalida: Obtiene los datos para validarlos y realizar las opciones de salida*/
    public function regisSalida(string $fecha, string $hora, int $idusuario, float $total, string $codigo, string $tipo_despacho = 'venta')
    {
        // 1. Verificar si el código de factura/salida ya existe
        $verificar = "SELECT * FROM salida WHERE cod_docum = '$codigo'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            // 2. Insertar cabecera
            $sql = "INSERT INTO salida (fecha, hora, idusuario, cod_docum, total, tipo_despacho) VALUES (?,?,?,?,?,?)";
            $datos = array($fecha, $hora, $idusuario, $codigo, $total, $tipo_despacho);
            $data = $this->insertar($sql, $datos); // Asumiendo que 'insertar' devuelve el ID generado

            if ($data > 0) {
                return $data; // Retorna el ID de la salida para usarlo en los detalles
            } else {
                return 0;
            }
        } else {
            return "existe";
        }
    }

    public function detalleSalida(int $id_salida, int $id_producto, int $cantidad, float $precio, float $subTotal)
    {
        // Insertar cada fila del detalle
        $sql = "INSERT INTO salidaProducto (cantidad, precio, idproducto, idsalida, sub_total) VALUES (?,?,?,?,?)";
        $datos = array($cantidad, $precio, $id_producto, $id_salida, $subTotal);
        $data = $this->save($sql, $datos);

        if ($data == 1) {
            return "ok";
        } else {
            return "error";
        }
    }

    public function actualizarStock(int $id_producto, int $cantidad)
    {
        // Restar la cantidad recibida al stock actual
        $sql = "UPDATE producto SET cantidad = cantidad - ? WHERE id = ?";
        $datos = array($cantidad, $id_producto);
        return $this->save($sql, $datos);
    }

    /*obtenerPrecioProducto: Obtiene el precio de un producto por su id*/
    public function obtenerPrecioProducto(int $id_producto)
    {
        $sql = "SELECT precio FROM producto WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data ? floatval($data['precio']) : 0;
    }
}
