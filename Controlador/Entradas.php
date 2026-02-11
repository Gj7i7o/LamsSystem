<?php

/*Controlador de la Entrada*/

require_once "modelo/HistorialModel.php";

class entradas extends controlador
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

    /*Listado: Se encarga de obtener el listado de las entradas realizadas
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
            $data = $this->model->tomarEntrada($params);
            $total = $this->model->getCount($params);
            if ($_SESSION['rango'] == "administrador") {
                // Agregar botones de acción de visualizar y cambiar estado si cumple con el rango
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['estado'] == 'activo') {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleEntrada(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                <button class="warning" type="button" onclick="btnDesEntrada(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                    } else {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleEntrada(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                <button class="secure" type="button" onclick="btnActEntrada(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                    }
                }
            } else {
                // Vista si el usuario no es administrador. No podrá activar ni desactivar entradas, pero si verlas
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['estado'] == 'activo') {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleEntrada(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                </div>';
                    } else {
                        $data[$i]['acciones'] = '<div>
                <button class="ver" type="button" onclick="btnVerDetalleEntrada(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
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

    /*editar: Obtiene los datos de una entrada para edición*/
    public function editar(int $id)
    {
        $cabecera = $this->model->editarEntrada($id);
        $detalle = $this->model->obtenerDetalleEntrada($id);
        echo json_encode(['cabecera' => $cabecera, 'detalle' => $detalle], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*verDetalle: Obtiene los datos completos de una entrada (solo lectura)*/
    public function verDetalle(int $id)
    {
        $cabecera = $this->model->editarEntrada($id);
        $detalle = $this->model->obtenerDetalleEntrada($id);
        echo json_encode(['cabecera' => $cabecera, 'detalle' => $detalle], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*registrar: Se encarga de validar y registrar los datos de la entrada en la base de datos*/
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
            $id_proveedor = $data['proveedor'];
            $id_usuario = $data['usuario'];
            $total = $data['total'];
            $lineas = $data['lineas']; // Array de productos
            $tipo_pago = !empty($data['tipo_pago']) ? $data['tipo_pago'] : 'contado';

            // Validar que los precios sean mayores a 0
            $precioInvalido = false;
            foreach ($lineas as $linea) {
                if (floatval($linea['precioCosto']) <= 0 || floatval($linea['precioVenta']) <= 0) {
                    $precioInvalido = true;
                    break;
                }
            }

            // Validaciones básicas de cabecera
            if (empty($codigo) || empty($id_proveedor) || empty($lineas)) {
                $msg = array('msg' => 'Todos los campos y al menos un producto son obligatorios', 'icono' => 'warning');
            } else if ($precioInvalido) {
                $msg = array('msg' => 'El precio debe ser mayor a 0', 'icono' => 'warning');
            } else {
                // Determinar si es CREAR o ACTUALIZAR
                if ($id == "") {
                    // CREAR NUEVA ENTRADA
                    $id_entrada = $this->model->regisEntrada($fecha, $hora, $id_proveedor, $total, $codigo, $tipo_pago, $id_usuario);

                    if ($id_entrada == "existe") {
                        $msg = array('msg' => 'El código de entrada ya existe', 'icono' => 'warning');
                    } else if ($id_entrada > 0) {
                        $error_detalle = false;
                        $cambiosProductos = [];

                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precioCosto = $linea['precioCosto'];
                            $precioVenta = $linea['precioVenta'];
                            $subTotal = $linea['subTotal'];

                            // Obtener datos actuales del producto antes de actualizar
                            $productoAnterior = $this->model->obtenerProducto($id_producto);

                            $detalle = $this->model->detalleEntrada($id_entrada, $id_producto, $cantidad, $precioCosto, $precioVenta, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);

                            // Registrar cambios de precios si hubo modificaciones
                            if ($productoAnterior) {
                                $cambios = [];
                                $cantidadAnterior = $productoAnterior['cantidad'];
                                $cantidadNueva = $cantidadAnterior + $cantidad;
                                $cambios[] = "Cantidad: $cantidadAnterior → $cantidadNueva (+$cantidad)";

                                if ($productoAnterior['precioCosto'] != $precioCosto) {
                                    $cambios[] = "P.Costo: \${$productoAnterior['precioCosto']} → \$$precioCosto";
                                }
                                if ($productoAnterior['precioVenta'] != $precioVenta) {
                                    $cambios[] = "P.Venta: \${$productoAnterior['precioVenta']} → \$$precioVenta";
                                }

                                if (count($cambios) > 0) {
                                    $cambiosProductos[] = "{$productoAnterior['nombre']}: " . implode(", ", $cambios);
                                }
                            }
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Entrada registrada y stock actualizado', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'registrar', "Registró entrada #$codigo - Total: $$total");

                            // Registrar cambios de productos en el historial
                            if (!empty($cambiosProductos)) {
                                foreach ($cambiosProductos as $cambio) {
                                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'actualizar_entrada', "Entrada #$codigo | $cambio");
                                }
                            }
                        } else {
                            $msg = array('msg' => 'Error al registrar el detalle de la entrada', 'icono' => 'error');
                        }
                    } else {
                        $msg = array('msg' => 'Error al registrar la cabecera', 'icono' => 'error');
                    }
                } else {
                    // ACTUALIZAR ENTRADA EXISTENTE
                    // 1. Revertir el stock anterior
                    $this->model->revertirStockEntrada($id);

                    // 2. Eliminar detalles anteriores
                    $this->model->eliminarDetallesEntrada($id);

                    // 3. Actualizar cabecera
                    $resultado = $this->model->modifEntrada($id, $codigo, $id_proveedor, $total, $tipo_pago);

                    if ($resultado == "modificado") {
                        $error_detalle = false;
                        $cambiosProductos = [];

                        // 4. Insertar nuevos detalles y actualizar stock
                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precioCosto = $linea['precioCosto'];
                            $precioVenta = $linea['precioVenta'];
                            $subTotal = $linea['subTotal'];

                            // Obtener datos actuales del producto antes de actualizar
                            $productoAnterior = $this->model->obtenerProducto($id_producto);

                            $detalle = $this->model->detalleEntrada($id, $id_producto, $cantidad, $precioCosto, $precioVenta, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);

                            // Registrar cambios de precios si hubo modificaciones
                            if ($productoAnterior) {
                                $cambios = [];
                                $cantidadAnterior = $productoAnterior['cantidad'];
                                $cantidadNueva = $cantidadAnterior + $cantidad;
                                $cambios[] = "Cantidad: $cantidadAnterior → $cantidadNueva (+$cantidad)";

                                if ($productoAnterior['precioCosto'] != $precioCosto) {
                                    $cambios[] = "P.Costo: \${$productoAnterior['precioCosto']} → \$$precioCosto";
                                }
                                if ($productoAnterior['precioVenta'] != $precioVenta) {
                                    $cambios[] = "P.Venta: \${$productoAnterior['precioVenta']} → \$$precioVenta";
                                }

                                if (count($cambios) > 0) {
                                    $cambiosProductos[] = "{$productoAnterior['nombre']}: " . implode(", ", $cambios);
                                }
                            }
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Entrada actualizada correctamente', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'modificar', "Modificó entrada ID: $id - #$codigo");

                            // Registrar cambios de productos en el historial
                            if (!empty($cambiosProductos)) {
                                foreach ($cambiosProductos as $cambio) {
                                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'actualizar_entrada', "Entrada #$codigo | $cambio");
                                }
                            }
                        } else {
                            $msg = array('msg' => 'Error al actualizar el detalle de la entrada', 'icono' => 'error');
                        }
                    } else if ($resultado == "existe") {
                        $msg = array('msg' => 'El código de entrada ya existe', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al actualizar la entrada', 'icono' => 'error');
                    }
                }
            }
        } else {
            $msg = array('msg' => 'Error en el formato de datos', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desEntrada del modelo/entradasModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        $data = $this->model->desEntrada($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar la entrada', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Entrada desactivada y stock ajustado', 'icono' => 'success');
            $this->model->revertirStockEntradaRestar($id);
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'desactivar', "Desactivó entrada ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actEntrada del modelo/entradasModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actEntrada($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar la entrada', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Entrada activada y stock ajustado', 'icono' => 'success');
            $this->model->revertirStockEntradaSumar($id);
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'activar', "Activó entrada ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF listado de todas las entradas con sus líneas*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $estado = $_GET["estado"] ?? "todo";
        $query = $_GET["query"] ?? "";
        $fecha_desde = $_GET["fecha_desde"] ?? "";
        $fecha_hasta = $_GET["fecha_hasta"] ?? "";
        $params = ['query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta];
        $entradas = $this->model->tomarEntradasReporte($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('entrada_pdf', [
            'entradas' => $entradas,
            'filtro_estado' => $estado,
            'filtro_fecha_desde' => $fecha_desde,
            'filtro_fecha_hasta' => $fecha_hasta
        ])->generar('Reporte_Entradas.pdf');
    }
}
