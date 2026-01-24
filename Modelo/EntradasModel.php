<?php

/*Modelo de la Entrada*/

class entradasModel extends query
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
        $sql = "SELECT * FROM entrada $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarEntrada: Toma todas las entradas de la base de datos*/
    public function tomarEntrada(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"]);
        $sql = $params["page"] <= 0 ? "SELECT id, cod_docum, total, fecha, hora FROM entrada $filters ORDER BY id DESC" :
            "SELECT id, cod_docum, total, fecha, hora FROM entrada $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisEntrada: Obtiene los datos para validarlos y realizar las opciones de entrada*/
    public function regisEntrada(string $fecha, string $hora, int $id_proveedor, float $total, string $codigo, string $tipo_pago = 'contado')
    {
        // 1. Verificar si el código de factura/entrada ya existe
        $verificar = "SELECT * FROM entrada WHERE cod_docum = '$codigo'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            // 2. Insertar cabecera
            $sql = "INSERT INTO entrada (fecha, hora, idproveedor, total, cod_docum, tipo_pago) VALUES (?,?,?,?,?,?)";
            $datos = array($fecha, $hora, $id_proveedor, $total, $codigo, $tipo_pago);
            $data = $this->insertar($sql, $datos); // Asumiendo que 'insertar' devuelve el ID generado

            if ($data > 0) {
                return $data; // Retorna el ID de la entrada para usarlo en los detalles
            } else {
                return 0;
            }
        } else {
            return "existe";
        }
    }

    public function detalleEntrada(int $id_entrada, int $id_producto, int $cantidad, float $precio, float $subTotal)
    {
        // Insertar cada fila del detalle
        $sql = "INSERT INTO entradaProducto (cantidad, precio, idproducto, identrada, iva, sub_total) VALUES (?,?,?,?,30,?)";
        $datos = array($cantidad, $precio, $id_producto, $id_entrada, $subTotal);
        $data = $this->save($sql, $datos);

        if ($data == 1) {
            return "ok";
        } else {
            return "error";
        }
    }

    public function actualizarStock(int $id_producto, int $cantidad)
    {
        // Sumar la cantidad recibida al stock actual
        $sql = "UPDATE producto SET cantidad = cantidad + ? WHERE id = ?";
        $datos = array($cantidad, $id_producto);
        return $this->save($sql, $datos);
    }
}
