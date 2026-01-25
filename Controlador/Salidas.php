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
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query];
            $data = $this->model->tomarSalida($params);
            $total = $this->model->getCount($params);

            // Agregar botones de acción a cada registro
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
                    <button class="secure" type="button" onclick="btnVerDetalleSalida(' . $data[$i]['id'] . ');" title="Ver Detalle"><i class="fa-solid fa-eye"></i></button>
                    <button class="primary" type="button" onclick="btnEditSalida(' . $data[$i]['id'] . ');" title="Editar"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>';
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

            // Validaciones básicas de cabecera
            if (empty($codigo) || empty($lineas)) {
                $msg = array('msg' => 'Todos los campos y al menos un producto son obligatorios', 'icono' => 'warning');
            } else if ($precioInvalido) {
                $msg = array('msg' => 'El precio de venta no puede ser menor al precio del producto', 'icono' => 'warning');
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
}
