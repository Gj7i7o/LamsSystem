<?php

/*Modelo de la Entrada*/

class entradasModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value, string $estado, string $fecha_desde = '', string $fecha_hasta = ''): string
    {
        $conditions = [];
        if ($estado != "todo") {
            $conditions[] = "e.estado = '$estado'";
        }
        if (!empty($value)) {
            $conditions[] = "(e.cod_docum LIKE '%$value%' OR e.total LIKE '%$value%' OR p.nombre LIKE '%$value%' OR e.tipo_pago LIKE '%$value%' OR EXISTS (SELECT 1 FROM entradaproducto ep2 INNER JOIN producto pr2 ON ep2.idproducto = pr2.id WHERE ep2.identrada = e.id AND (pr2.nombre LIKE '%$value%' OR pr2.codigo LIKE '%$value%')))";
        }
        if (!empty($fecha_desde)) {
            $conditions[] = "DATE(e.creadoEl) >= '$fecha_desde'";
        }
        if (!empty($fecha_hasta)) {
            $conditions[] = "DATE(e.creadoEl) <= '$fecha_hasta'";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    public function getCount(array $params)
    {
        $fecha_desde = $params["fecha_desde"] ?? '';
        $fecha_hasta = $params["fecha_hasta"] ?? '';
        $filters = $this->filtersSQL($params["query"], $params["estado"], $fecha_desde, $fecha_hasta);
        $sql = "SELECT e.id FROM entrada e LEFT JOIN proveedor p ON e.idproveedor = p.id $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarEntrada: Toma todas las entradas de la base de datos*/
    public function tomarEntrada(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $fecha_desde = $params["fecha_desde"] ?? '';
        $fecha_hasta = $params["fecha_hasta"] ?? '';
        $filters = $this->filtersSQL($params["query"], $params["estado"], $fecha_desde, $fecha_hasta);
        $sql = $params["page"] <= 0 ? "SELECT e.id, e.cod_docum, e.total, e.fecha, e.hora, p.nombre as proveedor, e.tipo_pago, e.estado FROM entrada e LEFT JOIN proveedor p ON e.idproveedor = p.id $filters ORDER BY e.id DESC" :
            "SELECT e.id, e.cod_docum, e.total, e.fecha, e.hora, p.nombre as proveedor, e.tipo_pago, e.estado FROM entrada e LEFT JOIN proveedor p ON e.idproveedor = p.id $filters ORDER BY e.id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisEntrada: Obtiene los datos para validarlos y realizar las opciones de entrada*/
    public function regisEntrada(string $fecha, string $hora, int $id_proveedor, float $total, string $codigo, string $tipo_pago = 'contado', int $id_usuario)
    {
        // 1. Verificar si el código de factura/entrada ya existe
        $verificar = "SELECT * FROM entrada WHERE cod_docum = '$codigo'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            // 2. Insertar cabecera
            $sql = "INSERT INTO entrada (fecha, hora, idproveedor, total, cod_docum, tipo_pago, idusuario) VALUES (?,?,?,?,?,?,?)";
            $datos = array($fecha, $hora, $id_proveedor, $total, $codigo, $tipo_pago, $id_usuario);
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

    public function detalleEntrada(int $id_entrada, int $id_producto, int $cantidad, float $precioCosto, float $precioVenta, float $subTotal)
    {
        // Insertar cada fila del detalle
        $sql = "INSERT INTO entradaProducto (cantidad, precio, precioVenta, idproducto, identrada, iva, sub_total) VALUES (?,?,?,?,?,30,?)";
        $datos = array($cantidad, $precioCosto, $precioVenta, $id_producto, $id_entrada, $subTotal);
        $data = $this->save($sql, $datos);

        if ($data == 1) {
            // Actualizar precios del producto
            $sqlUpdate = "UPDATE producto SET precioVenta = ?, precioCosto = ? WHERE id = ?";
            $this->save($sqlUpdate, array($precioVenta, $precioCosto, $id_producto));
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

    /*editarEntrada: Obtiene la cabecera de una entrada con datos del proveedor*/
    public function editarEntrada(int $id)
    {
        $sql = "SELECT e.*, p.nombre as proveedor_nombre, u.usuario as usuario_nombre
                FROM entrada e
                LEFT JOIN proveedor p ON e.idproveedor = p.id
                LEFT JOIN usuario u ON e.idusuario = u.id
                WHERE e.id = $id";
        return $this->select($sql);
    }

    /*obtenerDetalleEntrada: Obtiene las líneas de detalle de una entrada*/
    public function obtenerDetalleEntrada(int $id_entrada)
    {
        $sql = "SELECT ep.*, ep.precio as precioCosto, ep.precioVenta, pr.nombre as producto_nombre, pr.codigo as producto_codigo
                FROM entradaproducto ep
                LEFT JOIN producto pr ON ep.idproducto = pr.id
                WHERE ep.identrada = $id_entrada";
        return $this->selectAll($sql);
    }

    /*entradaStock: Obtiene la cantidad de cada linea de producto*/
    public function entradaStock(int $id)
    {
        $sql = "SELECT cantidad, idproducto FROM entradaproducto WHERE identrada = $id";
        return $this->selectAll($sql);
    }

    /*revertirStockEntradaSumar: Suma del stock las cantidades de una entrada*/
    public function revertirStockEntradaSumar(int $id)
    {
        $detalles = $this->entradaStock($id);
        foreach ($detalles as $detalle) {
            $sql = "UPDATE producto SET cantidad = cantidad + ? WHERE id = ?";
            $datos = array($detalle['cantidad'], $detalle['idproducto']);
            $this->save($sql, $datos);
        }
        return true;
    }

    /*revertirStockEntradaRestar: Resta del stock las cantidades de una entrada (para edición)*/
    public function revertirStockEntradaRestar(int $id)
    {
        $detalles = $this->entradaStock($id);
        foreach ($detalles as $detalle) {
            $sql = "UPDATE producto SET cantidad = cantidad - ? WHERE id = ?";
            $datos = array($detalle['cantidad'], $detalle['idproducto']);
            $this->save($sql, $datos);
        }
        return true;
    }

    /*eliminarDetallesEntrada: Elimina los detalles de una entrada*/
    public function eliminarDetallesEntrada(int $id_entrada)
    {
        $sql = "DELETE FROM entradaproducto WHERE identrada = ?";
        return $this->save($sql, array($id_entrada));
    }

    /*modifEntrada: Actualiza la cabecera de una entrada*/
    public function modifEntrada(int $id, string $codigo, int $idproveedor, float $total, string $tipo_pago)
    {
        // Verificar código único (excepto para el mismo registro)
        $verificar = "SELECT * FROM entrada WHERE cod_docum = '$codigo' AND id != $id";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "UPDATE entrada SET cod_docum = ?, idproveedor = ?, total = ?, tipo_pago = ? WHERE id = ?";
            $datos = array($codigo, $idproveedor, $total, $tipo_pago, $id);
            $data = $this->save($sql, $datos);
            return $data == 1 ? "modificado" : "error";
        } else {
            return "existe";
        }
    }

    /*desEntrada: Hace la consulta SQL que traerá la entrada acorde al id, y le cambia su estado a inactivo*/
    public function desEntrada(int $id)
    {
        $sql = "UPDATE entrada SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*actEntrada: Hace la consulta SQL que traerá la entrada que posteriormente se cambiará su estado a activo*/
    public function actEntrada(int $id)
    {
        $sql = "UPDATE entrada SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*tomarEntradasReporte: Obtiene todas las líneas de entradas con datos de cabecera para reportes*/
    public function tomarEntradasReporte(array $params)
    {
        $conditions = [];
        if (!empty($params["query"])) {
            $value = $params["query"];
            $conditions[] = "(e.cod_docum LIKE '%$value%' OR p.nombre LIKE '%$value%' OR pr.nombre LIKE '%$value%' OR pr.codigo LIKE '%$value%' OR e.tipo_pago LIKE '%$value%')";
        }
        if (!empty($params["fecha_desde"])) {
            $fecha_desde = $params["fecha_desde"];
            $conditions[] = "DATE(e.creadoEl) >= '$fecha_desde'";
        }
        if (!empty($params["fecha_hasta"])) {
            $fecha_hasta = $params["fecha_hasta"];
            $conditions[] = "DATE(e.creadoEl) <= '$fecha_hasta'";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT e.cod_docum, e.tipo_pago, e.fecha, e.hora,
                       p.nombre as proveedor,
                       pr.nombre as producto,
                       ep.cantidad, ep.precio as precioCosto, ep.precioVenta, ep.sub_total
                FROM entradaproducto ep
                INNER JOIN entrada e ON ep.identrada = e.id
                LEFT JOIN proveedor p ON e.idproveedor = p.id
                LEFT JOIN producto pr ON ep.idproducto = pr.id
                $filter
                ORDER BY e.id DESC, ep.id ASC";
        return $this->selectAll($sql);
    }
}
