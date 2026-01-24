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
        $offset = ($params["page"] - 1) * 5;
        $filters = $this->filtersSQL($params["query"]);
        $sql = $params["page"] <= 0 ? "SELECT id, cod_docum, total, fecha, hora FROM salida $filters" :
            "SELECT id, cod_docum, total, fecha, hora FROM salida $filters LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisSalida: Obtiene los datos para validarlos y realizar las opciones de salida*/
    public function regisSalida(string $fecha, string $hora, int $idusuario, float $total, string $codigo)
    {
        // 1. Verificar si el código de factura/salida ya existe
        $verificar = "SELECT * FROM salida WHERE cod_docum = '$codigo'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            // 2. Insertar cabecera
            $sql = "INSERT INTO salida (fecha, hora, idusuario, cod_docum, total) VALUES (?,?,?,?,?)";
            $datos = array($fecha, $hora, $idusuario, $codigo, $total);
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
}
