<?php

/*Controlador del Producto: Aquí se llaman a los métodos del modelo y validan datos*/

class productos extends controlador
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

    public function getSelect()
    {
        $result = [];
        $data = $this->model->tomarProductosAc();
        foreach ($data as $producto) {
            $result[] = ['id' => $producto['id'], 'etiqueta' => $producto['nombre']];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*listar: Se encarga de colocar los productos existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $data = $this->model->tomarProductos($page, $estado);
            $total = $this->model->getCount($estado);
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
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        $marca = $_POST['marca'];
        $id = $_POST['id'];
        $numeros = "/^\d+(\.\d{1,2})?$/";
        if (
            empty($codigo) ||
            empty($nombre) ||
            empty($precio) ||
            empty($categoria) ||
            empty($marca)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($numeros, $precio)) {
                    $msg = array('msg' => 'Solo números en el precio', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el producto no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisProducto en el modelo/productosModel.php*/
                    $data = $this->model->regisProducto($codigo, $nombre, $precio, $categoria, $marca);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Producto Registrado', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El producto ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el producto', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si el producto existe, se interpreta que se desea modificar ese producto,
                por ende lleva los datos a la función modifProducto en el modelo/productosModel.php*/
                $data = $this->model->modifProducto($codigo, $nombre, $precio, $categoria, $marca, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Producto modificado', 'icono' => 'success');
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
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
