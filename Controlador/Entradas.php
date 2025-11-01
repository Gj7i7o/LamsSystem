<?php

/*Controlador de la Entrada*/

class Entradas extends Controlador
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

    /*Listado: Se encarga de colocar las categorías existentes en la base de datos 
    y a su vez coloca en cada una los botones de editar y eliminar*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getEntrada($page);
            $total = $this->model->getCount();
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Almacenaje: Se encarga de almacenar los datos de un nuevo producto en la base de datos*/
    public function store()
    {
        $cantidad = $_POST['cantidad'];
        $id = $_POST['id'];
        $numeros = "/^\d+(\.\d{1,2})?$/";
        if (
            empty($cantidad)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($numeros, $cantidad)) {
                    $msg = array('msg' => 'Solo números en la cantidad', 'icono' => 'warning');
                } else {
                    /*Caso contrario, si el producto existe, se interpreta que se desea modificar ese producto,
                por ende lleva los datos a la función modifyProduct en el Models/ProductosModel.php*/
                    $data = $this->model->modifyEntrada($cantidad, $id);
                    if ($data == "modificado") {
                        $msg = array('msg' => 'Entrada modificado', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al modificar el entrada', 'icono' => 'error');
                    }
                }
            }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
}
