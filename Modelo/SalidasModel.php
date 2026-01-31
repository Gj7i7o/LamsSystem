<?php

/*Modelo de la Salida*/

class salidasModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value, string $fecha_desde = '', string $fecha_hasta = ''): string
    {
        $conditions = [];
        if (!empty($value)) {
            $conditions[] = "(s.cod_docum LIKE '%$value%' OR s.total LIKE '%$value%' OR s.tipo_despacho LIKE '%$value%' OR EXISTS (SELECT 1 FROM salidaproducto sp2 INNER JOIN producto pr2 ON sp2.idproducto = pr2.id WHERE sp2.idsalida = s.id AND (pr2.nombre LIKE '%$value%' OR pr2.codigo LIKE '%$value%')))";
        }
        if (!empty($fecha_desde)) {
            $conditions[] = "DATE(s.creadoEl) >= '$fecha_desde'";
        }
        if (!empty($fecha_hasta)) {
            $conditions[] = "DATE(s.creadoEl) <= '$fecha_hasta'";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    public function getCount(array $params)
    {
        $fecha_desde = $params["fecha_desde"] ?? '';
        $fecha_hasta = $params["fecha_hasta"] ?? '';
        $filters = $this->filtersSQL($params["query"], $fecha_desde, $fecha_hasta);
        $sql = "SELECT s.id FROM salida s $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarSalida: Toma todas las salidas de la base de datos*/
    public function tomarSalida(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $fecha_desde = $params["fecha_desde"] ?? '';
        $fecha_hasta = $params["fecha_hasta"] ?? '';
        $filters = $this->filtersSQL($params["query"], $fecha_desde, $fecha_hasta);
        $sql = $params["page"] <= 0 ? "SELECT s.id, s.cod_docum, s.total, s.fecha, s.hora, s.tipo_despacho FROM salida s $filters ORDER BY s.id DESC" :
            "SELECT s.id, s.cod_docum, s.total, s.fecha, s.hora, s.tipo_despacho FROM salida s $filters ORDER BY s.id DESC LIMIT 10 OFFSET $offset";
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
        $sql = "SELECT precioVenta FROM producto WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data ? floatval($data['precioVenta']) : 0;
    }

    /*obtenerStockProducto: Obtiene el stock actual de un producto por su id*/
    public function obtenerStockProducto(int $id_producto)
    {
        $sql = "SELECT cantidad FROM producto WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data ? intval($data['cantidad']) : 0;
    }

    /*editarSalida: Obtiene la cabecera de una salida con datos del usuario*/
    public function editarSalida(int $id)
    {
        $sql = "SELECT s.*, u.usuario as usuario_nombre
                FROM salida s
                LEFT JOIN usuario u ON s.idusuario = u.id
                WHERE s.id = $id";
        return $this->select($sql);
    }

    /*obtenerDetalleSalida: Obtiene las líneas de detalle de una salida*/
    public function obtenerDetalleSalida(int $id_salida)
    {
        $sql = "SELECT sp.*, pr.nombre as producto_nombre, pr.codigo as producto_codigo, pr.precioVenta as producto_precio
                FROM salidaproducto sp
                LEFT JOIN producto pr ON sp.idproducto = pr.id
                WHERE sp.idsalida = $id_salida";
        return $this->selectAll($sql);
    }

    /*revertirStockSalida: Suma al stock las cantidades de una salida (para edición - devolver al inventario)*/
    public function revertirStockSalida(int $id_salida)
    {
        $detalles = $this->obtenerDetalleSalida($id_salida);
        foreach ($detalles as $detalle) {
            $sql = "UPDATE producto SET cantidad = cantidad + ? WHERE id = ?";
            $datos = array($detalle['cantidad'], $detalle['idproducto']);
            $this->save($sql, $datos);
        }
        return true;
    }

    /*eliminarDetallesSalida: Elimina los detalles de una salida*/
    public function eliminarDetallesSalida(int $id_salida)
    {
        $sql = "DELETE FROM salidaproducto WHERE idsalida = ?";
        return $this->save($sql, array($id_salida));
    }

    /*modifSalida: Actualiza la cabecera de una salida*/
    public function modifSalida(int $id, string $codigo, float $total, string $tipo_despacho)
    {
        // Verificar código único (excepto para el mismo registro)
        $verificar = "SELECT * FROM salida WHERE cod_docum = '$codigo' AND id != $id";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "UPDATE salida SET cod_docum = ?, total = ?, tipo_despacho = ? WHERE id = ?";
            $datos = array($codigo, $total, $tipo_despacho, $id);
            $data = $this->save($sql, $datos);
            return $data == 1 ? "modificado" : "error";
        } else {
            return "existe";
        }
    }

    /*tomarSalidasReporte: Obtiene todas las líneas de salidas con datos de cabecera para reportes*/
    public function tomarSalidasReporte(array $params)
    {
        $conditions = [];
        if (!empty($params["query"])) {
            $value = $params["query"];
            $conditions[] = "(s.cod_docum LIKE '%$value%' OR pr.nombre LIKE '%$value%' OR pr.codigo LIKE '%$value%' OR s.tipo_despacho LIKE '%$value%')";
        }
        if (!empty($params["fecha_desde"])) {
            $fecha_desde = $params["fecha_desde"];
            $conditions[] = "DATE(s.creadoEl) >= '$fecha_desde'";
        }
        if (!empty($params["fecha_hasta"])) {
            $fecha_hasta = $params["fecha_hasta"];
            $conditions[] = "DATE(s.creadoEl) <= '$fecha_hasta'";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT s.cod_docum, s.tipo_despacho, s.fecha, s.hora,
                       pr.nombre as producto,
                       sp.cantidad, sp.precio, sp.sub_total
                FROM salidaproducto sp
                INNER JOIN salida s ON sp.idsalida = s.id
                LEFT JOIN producto pr ON sp.idproducto = pr.id
                $filter
                ORDER BY s.id DESC, sp.id ASC";
        return $this->selectAll($sql);
    }
}
