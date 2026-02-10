<?php

/*Controlador de la Salida*/

require_once "modelo/HistorialModel.php";

class salidas extends controlador
{
    private $historialModel;

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        parent::__construct();
        $this->historialModel = new historialModel();
    }

    /*Vista: Trae la vista correspóndiente*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*Listado: Se encarga de obtener el listado de las salidas realizadas
    y almacenadas en la base de datos*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $fecha_desde = $_GET["fecha_desde"] ?? "";
            $fecha_hasta = $_GET["fecha_hasta"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta];
            $data = $this->model->tomarSalida($params);
            $total = $this->model->getCount($params);
            if ($_SESSION['rango'] == "administrador") {
                // Agregar botones de acción de visualizar y cambiar estado si cumple con el rango
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['estado'] == 'activo') {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleSalida(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                <button class="warning" type="button" onclick="btnDesSalida(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                    } else {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleSalida(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                <button class="secure" type="button" onclick="btnActSalida(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                    }
                }
            } else {
                // Vista si el usuario no es administrador. No podrá activar ni desactivar entradas, pero si verlas
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['estado'] == 'activo') {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleSalida(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                </div>';
                    } else {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleSalida(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                </div>';
                    }
                }
            }

            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*editar: Obtiene los datos de una salida para edición*/
    public function editar(int $id)
    {
        $cabecera = $this->model->editarSalida($id);
        $detalle = $this->model->obtenerDetalleSalida($id);
        echo json_encode(['cabecera' => $cabecera, 'detalle' => $detalle], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*verDetalle: Obtiene los datos completos de una salida (solo lectura)*/
    public function verDetalle(int $id)
    {
        $cabecera = $this->model->editarSalida($id);
        $detalle = $this->model->obtenerDetalleSalida($id);
        echo json_encode(['cabecera' => $cabecera, 'detalle' => $detalle], JSON_UNESCAPED_UNICODE);
        die();
    }


    /*registrar: Se encarga de validar y registrar los datos de la salida en la base de datos*/
    public function registrar()
    {
        // 1. Recibimos el JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $id = !empty($data['id']) ? $data['id'] : "";
            $fecha = $data['fecha'];
            $hora = $data['hora'];
            $codigo = $data['codigo'];
            $id_usuario = $data['usuario'];
            $total = $data['total'];
            $tipo_despacho = !empty($data['tipo_despacho']) ? $data['tipo_despacho'] : 'venta';
            $lineas = $data['lineas']; // Array de productos

            // Validar que el precio de venta no sea menor al precio del producto
            $precioInvalido = false;
            foreach ($lineas as $linea) {
                $precioProducto = $this->model->obtenerPrecioProducto($linea['producto']);
                $precioVenta = floatval($linea['precio']);
                if ($precioVenta < $precioProducto) {
                    $precioInvalido = true;
                    break;
                }
            }

            // Validar que la cantidad no supere el stock disponible
            $stockInsuficiente = false;
            $productoSinStock = "";

            // Si es edición, primero simular la reversión del stock para calcular disponibilidad real
            $stockRevertido = [];
            if ($id != "") {
                $detallesAnteriores = $this->model->obtenerDetalleSalida($id);
                foreach ($detallesAnteriores as $detalle) {
                    $idProd = $detalle['idproducto'];
                    if (!isset($stockRevertido[$idProd])) {
                        $stockRevertido[$idProd] = 0;
                    }
                    $stockRevertido[$idProd] += intval($detalle['cantidad']);
                }
            }

            // Agrupar cantidades solicitadas por producto (para múltiples líneas del mismo producto)
            $cantidadesPorProducto = [];
            foreach ($lineas as $linea) {
                $id_producto = intval($linea['producto']);
                $cantidad = intval($linea['cantidad']);
                if (!isset($cantidadesPorProducto[$id_producto])) {
                    $cantidadesPorProducto[$id_producto] = 0;
                }
                $cantidadesPorProducto[$id_producto] += $cantidad;
            }

            // Validar stock para cada producto (considerando todas las líneas sumadas)
            foreach ($cantidadesPorProducto as $id_producto => $cantidadTotal) {
                $stockActual = $this->model->obtenerStockProducto($id_producto);

                // Sumar stock revertido si es edición
                if (isset($stockRevertido[$id_producto])) {
                    $stockActual += $stockRevertido[$id_producto];
                }

                if ($cantidadTotal > $stockActual) {
                    $stockInsuficiente = true;
                    $productoSinStock = $id_producto;
                    break;
                }
            }

            // Validaciones básicas de cabecera
            if (empty($codigo) || empty($lineas)) {
                $msg = array('msg' => 'Todos los campos y al menos un producto son obligatorios', 'icono' => 'warning');
            } else if ($precioInvalido) {
                $msg = array('msg' => 'El precio de venta no puede ser menor al precio del producto', 'icono' => 'warning');
            } else if ($stockInsuficiente) {
                $msg = array('msg' => 'Stock insuficiente para uno o más productos', 'icono' => 'warning');
            } else {
                // Determinar si es CREAR o ACTUALIZAR
                if ($id == "") {
                    // CREAR NUEVA SALIDA
                    $id_salida = $this->model->regisSalida($fecha, $hora, $id_usuario, $total, $codigo, $tipo_despacho);

                    if ($id_salida > 0) {
                        $error_detalle = false;

                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precio = $linea['precio'];
                            $subTotal = $linea['subTotal'];

                            $detalle = $this->model->detalleSalida($id_salida, $id_producto, $cantidad, $precio, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Salida registrada y stock actualizado', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Salidas', 'registrar', "Registró salida #$codigo - Total: $$total");
                        } else {
                            $msg = array('msg' => 'Error al registrar el detalle de la salida', 'icono' => 'error');
                        }
                    } else if ($id_salida == "existe") {
                        $msg = array('msg' => 'El código de salida ya existe', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar la cabecera', 'icono' => 'error');
                    }
                } else {
                    // ACTUALIZAR SALIDA EXISTENTE
                    // 1. Revertir el stock anterior (devolver al inventario)
                    $this->model->revertirStockSalida($id);

                    // 2. Eliminar detalles anteriores
                    $this->model->eliminarDetallesSalida($id);

                    // 3. Actualizar cabecera
                    $resultado = $this->model->modifSalida($id, $codigo, $total, $tipo_despacho);

                    if ($resultado == "modificado") {
                        $error_detalle = false;

                        // 4. Insertar nuevos detalles y actualizar stock
                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precio = $linea['precio'];
                            $subTotal = $linea['subTotal'];

                            $detalle = $this->model->detalleSalida($id, $id_producto, $cantidad, $precio, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Salida actualizada correctamente', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Salidas', 'modificar', "Modificó salida ID: $id - #$codigo");
                        } else {
                            $msg = array('msg' => 'Error al actualizar el detalle de la salida', 'icono' => 'error');
                        }
                    } else if ($resultado == "existe") {
                        $msg = array('msg' => 'El código de salida ya existe', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al actualizar la salida', 'icono' => 'error');
                    }
                }
            }
        } else {
            $msg = array('msg' => 'Error en el formato de datos', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desSalida del modelo/salidasModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        $data = $this->model->desSalida($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar la salida', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Salida desactivada y stock ajustado', 'icono' => 'success');
            $this->model->revertirStockSalidaSumar($id);
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Salidas', 'desactivar', "Desactivó salida ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actSalida del modelo/salidasModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actSalida($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar la salida', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Salida activada y stock ajustado', 'icono' => 'success');
            $this->model->revertirStockSalidaRestar($id);
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Salidas', 'activar', "Activó salida ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF listado de todas las salidas con sus líneas*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $estado = $_GET["estado"] ?? "todo";
        $query = $_GET["query"] ?? "";
        $fecha_desde = $_GET["fecha_desde"] ?? "";
        $fecha_hasta = $_GET["fecha_hasta"] ?? "";
        $params = ['query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta];
        $salidas = $this->model->tomarSalidasReporte($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('salida_pdf', [
            'salidas' => $salidas,
            'filtro_estado' => $estado,
            'filtro_fecha_desde' => $fecha_desde,
            'filtro_fecha_hasta' => $fecha_hasta
        ])->generar('Reporte_Salidas.pdf');
    }
}
