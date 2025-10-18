<?php

/*Controlador de la Categoría*/

class Categorias extends Controlador
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
        $data = $this->model->getCategory();
        foreach ($data as $categoria) {
            $result[] = ['id' => $categoria['id'], 'etiqueta' => $categoria['nombre']];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Listado: Se encarga de colocar las categorías existentes en la base de datos 
    y a su vez coloca en cada una los botones de editar y eliminar*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getCategory($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditCategoria(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDesCategoria(' . $data[$i]['id'] . ');" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Almacenaje: Se encarga de almacenar los datos de una nueva categoría en la base de datos*/
    public function store()
    {
        $name = $_POST['nombre'];
        $des = $_POST['des'];
        $id = $_POST['id'];
        if (empty($name) || empty($des)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                $data = $this->model->storeCategory($name, $des);
                if ($data == "ok") {
                    $msg = array('msg' => 'Categoria Registrada', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La categoría ya está registrada', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar la categoría', 'icono' => 'error');
                }
            } else {
                /*Caso contrario, si la categoría existe, se interpreta que se desea modificar esa categoría,
                por ende lleva los datos a la función modifyCategory en el Models/CategoriasModel.php*/
                $data = $this->model->modifyCategory($name, $des, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Categoría modificada', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al modificar la categoría', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Editar: Envía a la función editCategory del Models/CategoriasModel.php con el id correspondiente*/
    public function edit(int $id)
    {
        $data = $this->model->editCategory($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteCategory del Models/CategoriasModel.php con el id correspondiente*/
    public function destroy(int $id)
    {
        $data = $this->model->deleteCategory($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar la categoría', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Categoría desactivada', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
