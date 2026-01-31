<?php

/*Controlador del Producto: Aquí se llaman a los métodos del modelo y validan datos*/

require_once "modelo/HistorialModel.php";

class productos extends controlador
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

    public function getSelect()
    {
        $result = [];
        $data = $this->model->tomarProductosAc();
        $maxLength = 25; // Longitud máxima para mostrar en el select
        foreach ($data as $producto) {
            $etiquetaCompleta = $producto['codigo'] . ' - ' . $producto['nombre'];
            $etiquetaTruncada = mb_strlen($etiquetaCompleta) > $maxLength
                ? mb_substr($etiquetaCompleta, 0, $maxLength) . '...'
                : $etiquetaCompleta;
            $result[] = [
                'id' => $producto['id'],
                'etiqueta' => $etiquetaTruncada,
                'etiquetaCompleta' => $etiquetaCompleta,
                'precio' => $producto['precioVenta'],
                'stock' => $producto['cantidad']
            ];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*buscarPorCodigo: Busca un producto activo por su código exacto*/
    public function buscarPorCodigo()
    {
        $codigo = $_GET["codigo"] ?? "";
        if (empty($codigo)) {
            echo json_encode(["encontrado" => false, "msg" => "Código vacío"], JSON_UNESCAPED_UNICODE);
            die();
        }
        $data = $this->model->buscarProductoPorCodigo($codigo);
        if (!empty($data)) {
            echo json_encode([
                "encontrado" => true,
                "id" => $data['id'],
                "codigo" => $data['codigo'],
                "nombre" => $data['nombre'],
                "precio" => $data['precioVenta'],
                "stock" => $data['cantidad']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["encontrado" => false, "msg" => "El producto no existe"], JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /*listar: Se encarga de colocar los productos existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $fecha_desde = $_GET["fecha_desde"] ?? "";
            $fecha_hasta = $_GET["fecha_hasta"] ?? "";
            $stock_bajo = $_GET["stock_bajo"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'stock_bajo' => $stock_bajo];
            $data = $this->model->tomarProductos($params);
            $total = $this->model->getCount($params);
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['estado'] == 'activo') {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditProducto(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="warning" type="button" onclick="btnDesProducto(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                } else {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditProducto(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="secure" type="button" onclick="btnActProducto(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                }
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de un nuevo producto en la base de datos*/
    public function registrar()
    {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $precioVenta = $_POST['precioVenta'];
        $precioCosto = $_POST['precioCosto'] ?? 0;
        $categoria = $_POST['categoria'];
        $marca = $_POST['marca'];
        $cantidad = isset($_POST['cantidad']) && $_POST['cantidad'] !== '' ? intval($_POST['cantidad']) : 0;
        $cantidadMinima = isset($_POST['cantidadMinima']) && $_POST['cantidadMinima'] !== '' ? intval($_POST['cantidadMinima']) : 1;
        $id = $_POST['id'];
        $numeros = "/^\d+(\.\d{1,2})?$/";
        if (
            empty($codigo) ||
            empty($nombre) ||
            empty($precioVenta) ||
            empty($categoria) ||
            empty($marca)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($numeros, $precioVenta)) {
                    $msg = array('msg' => 'Solo números en el precio', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el producto no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisProducto en el modelo/productosModel.php*/
                    $data = $this->model->regisProducto($codigo, $nombre, $precioVenta, $precioCosto, $categoria, $marca, $cantidad, $cantidadMinima);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Producto Registrado', 'icono' => 'success');
                        $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'registrar', "Registró producto: $nombre (Código: $codigo)");
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El producto ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el producto', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si el producto existe, se interpreta que se desea modificar ese producto,
                por ende lleva los datos a la función modifProducto en el modelo/productosModel.php*/
                $data = $this->model->modifProducto($codigo, $nombre, $precioVenta, $precioCosto, $categoria, $marca, $id, $cantidad, $cantidadMinima);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Producto modificado', 'icono' => 'success');
                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'modificar', "Modificó producto ID: $id - $nombre");
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El producto ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al modificar el producto', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*editar: Envía a la función editarProducto del modelo/productosModel.php con el id correspondiente*/
    public function editar(int $id)
    {
        $data = $this->model->editarProducto($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desProducto del modelo/productosModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        $data = $this->model->desProducto($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar el producto', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Producto desactivado', 'icono' => 'success');
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'desactivar', "Desactivó producto ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actProducto del modelo/productosModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actProducto($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar el producto', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Producto activado', 'icono' => 'success');
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Productos', 'activar', "Activó producto ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF con todos los productos*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $estado = $_GET["estado"] ?? "todo";
        $query = $_GET["query"] ?? "";
        $fecha_desde = $_GET["fecha_desde"] ?? "";
        $fecha_hasta = $_GET["fecha_hasta"] ?? "";
        $stock_bajo = $_GET["stock_bajo"] ?? "";
        $params = ['page' => 1, 'query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'stock_bajo' => $stock_bajo];

        // Obtener todos los productos sin paginación para el reporte
        $productos = $this->model->tomarProductosTodos($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('productos_pdf', [
            'productos' => $productos,
            'filtro_estado' => $estado,
            'filtro_fecha_desde' => $fecha_desde,
            'filtro_fecha_hasta' => $fecha_hasta
        ])->generar('Inventario_Productos_' . date('Y-m-d') . '.pdf');
    }
}
