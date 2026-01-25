<?php

/*Controlador de la Entrada*/

class entradas extends controlador
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        parent::__construct();
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
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de la entrada en la base de datos*/
    public function registrar()
    {
        // 1. Recibimos el JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $fecha = $data['fecha'];
            $hora = $data['hora'];
            $codigo = $data['codigo'];
            $id_proveedor = $data['proveedor'];
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
                // 2. Registrar la Cabecera de la Entrada
                // Debes crear esta función en tu modelo para insertar y retornar el ID insertado
                $id_entrada = $this->model->regisEntrada($fecha, $hora, $id_proveedor, $total, $codigo, $tipo_pago);

                if ($id_entrada > 0) {
                    $error_detalle = false;

                    // 3. Registrar el Detalle (Recorrer las líneas)
                    foreach ($lineas as $linea) {
                        $id_producto = $linea['producto'];
                        $cantidad = $linea['cantidad'];
                        $precio = $linea['precio'];
                        $subTotal = $linea['subTotal'];

                        // Insertar cada producto vinculado al ID de la entrada
                        $detalle = $this->model->detalleEntrada($id_entrada, $id_producto, $cantidad, $precio, $subTotal);

                        if ($detalle != "ok") {
                            $error_detalle = true;
                            break;
                        }

                        // 4. OPCIONAL: Actualizar stock en la tabla productos
                        $this->model->actualizarStock($id_producto, $cantidad);
                    }

                    if (!$error_detalle) {
                        $msg = array('msg' => 'Entrada registrada y stock actualizado', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al registrar el detalle de la entrada', 'icono' => 'error');
                    }
                } else if ($id_entrada == "existe") {
                    $msg = array('msg' => 'El código de entrada ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar la cabecera', 'icono' => 'error');
                }
            }
        } else {
            $msg = array('msg' => 'Error en el formato de datos', 'icono' => 'error');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
