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
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query];
            $data = $this->model->tomarEntrada($params);
            $total = $this->model->getCount($params);

            // Agregar botones de acción a cada registro
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
                    <button class="secure" type="button" onclick="btnVerDetalleEntrada(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                </div>';
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
                if (floatval($linea['precio']) <= 0) {
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

                    if ($id_entrada > 0) {
                        $error_detalle = false;

                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precio = $linea['precio'];
                            $subTotal = $linea['subTotal'];

                            $detalle = $this->model->detalleEntrada($id_entrada, $id_producto, $cantidad, $precio, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Entrada registrada y stock actualizado', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'registrar', "Registró entrada #$codigo - Total: $$total");
                        } else {
                            $msg = array('msg' => 'Error al registrar el detalle de la entrada', 'icono' => 'error');
                        }
                    } else if ($id_entrada == "existe") {
                        $msg = array('msg' => 'El código de entrada ya existe', 'icono' => 'warning');
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

                        // 4. Insertar nuevos detalles y actualizar stock
                        foreach ($lineas as $linea) {
                            $id_producto = $linea['producto'];
                            $cantidad = $linea['cantidad'];
                            $precio = $linea['precio'];
                            $subTotal = $linea['subTotal'];

                            $detalle = $this->model->detalleEntrada($id, $id_producto, $cantidad, $precio, $subTotal);

                            if ($detalle != "ok") {
                                $error_detalle = true;
                                break;
                            }

                            $this->model->actualizarStock($id_producto, $cantidad);
                        }

                        if (!$error_detalle) {
                            $msg = array('msg' => 'Entrada actualizada correctamente', 'icono' => 'success');
                            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Entradas', 'modificar', "Modificó entrada ID: $id - #$codigo");
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

    /*reportePDF: Genera un reporte PDF listado de todas las entradas con sus líneas*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";

        $query = $_GET["query"] ?? "";
        $params = ['query' => $query];
        $entradas = $this->model->tomarEntradasReporte($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('entrada_pdf', [
            'entradas' => $entradas
        ])->generar('Reporte_Entradas.pdf');
    }
}
