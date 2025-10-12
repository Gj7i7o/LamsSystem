<?php

/*Controlador del Producto*/

class Productos extends Controlador
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

    /*Listado: Se encarga de colocar los productos existentes en la base de datos 
    y a su vez coloca en cada uno los botones de editar y eliminar*/
    public function list()
    {
       try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getProduct($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditProductos(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDelProductos(' . $data[$i]['id'] . ');" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Almacenaje: Se encarga de almacenar los datos de un nuevo producto en la base de datos*/
    public function store()
    {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $idcat = $_POST['idcat'];
        $idmar = $_POST['idmar'];
        $id = $_POST['id'];
        $numeros = "/^\d+(\.\d{1,2})?$/";
        if (
            empty($code) ||
            empty($name) ||
            empty($price) ||
            empty($idcat) ||
            empty($idmar)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($numeros, $price)) {
                    $msg = array('msg' => 'Solo números en el precio', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el producto no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función storeProduct en el Models/ProductosModel.php*/
                    $data = $this->model->storeProduct($code, $name, $price, $idcat, $idmar);
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
                por ende lleva los datos a la función modifyProduct en el Models/ProductosModel.php*/
                $data = $this->model->modifyProduct($code, $name, $price, $idcat, $idmar, $id);
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

    /*Editar: Envía a la función editProduct del Models/ProductosModel.php con el id correspondiente*/
    public function edit(int $id)
    {
        $data = $this->model->editProduct($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteProduct del Models/ProductosModel.php con el id correspondiente*/
    public function destroy(int $id)
    {
        $data = $this->model->deleteProduct($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar el producto', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Producto desactivado', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
