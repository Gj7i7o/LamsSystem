<?php

/*Controlador de la Salida*/

class salidas extends controlador
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

    /*Listado: Se encarga de obtener el listado de las salidas realizadas 
    y almacenadas en la base de datos*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->tomarSalida($page);
            $total = $this->model->getCount();
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }


    /*registrar: Se encarga de validar y registrar los datos de la salida en la base de datos*/
    public function registrar()
    {
        // 1. Recibimos el JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $fecha = $data['fecha'];
            $hora = $data['hora'];
            $codigo = $data['codigo'];
            $id_usuario = $data['usuario'];
            $total = $data['total'];
            $lineas = $data['lineas']; // Array de productos

            // Validaciones básicas de cabecera
            if (empty($codigo) || empty($lineas)) {
                $msg = array('msg' => 'Todos los campos y al menos un producto son obligatorios', 'icono' => 'warning');
            } else {
                // 2. Registrar la Cabecera de la Entrada
                // Debes crear esta función en tu modelo para insertar y retornar el ID insertado
                $id_salida = $this->model->regisSalida($fecha, $hora, $id_usuario, $total, $codigo);

                if ($id_salida > 0) {
                    $error_detalle = false;

                    // 3. Registrar el Detalle (Recorrer las líneas)
                    foreach ($lineas as $linea) {
                        $id_producto = $linea['producto'];
                        $cantidad = $linea['cantidad'];
                        $precio = $linea['precio'];
                        $subTotal = $linea['subTotal'];

                        // Insertar cada producto vinculado al ID de la entrada
                        $detalle = $this->model->detalleSalida($id_salida, $id_producto, $cantidad, $precio, $subTotal);

                        if ($detalle != "ok") {
                            $error_detalle = true;
                            break;
                        }

                        // 4. OPCIONAL: Actualizar stock en la tabla productos
                        $this->model->actualizarStock($id_producto, $cantidad);
                    }

                    if (!$error_detalle) {
                        $msg = array('msg' => 'Salida registrada y stock actualizado', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al registrar el detalle de la salida', 'icono' => 'error');
                    }
                } else if ($id_salida == "existe") {
                    $msg = array('msg' => 'El código de salida ya existe', 'icono' => 'warning');
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
