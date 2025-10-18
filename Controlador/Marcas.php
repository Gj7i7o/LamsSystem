<?php

/*Controlador de la Marca*/

class Marcas extends Controlador
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
        $data = $this->model->getMarca();
        foreach ($data as $marca) {
            $result[] = ['id' => $marca['id'], 'etiqueta' => $marca['nombre']];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Listado: Se encarga de colocar las marcas existentes en la base de datos 
    y a su vez coloca en cada una los botones de editar y eliminar*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getMarca($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditMarca(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDesMarca(' . $data[$i]['id'] . ');" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Almacenaje: Se encarga de almacenar los datos de una nueva marca en la base de datos*/
    public function store()
    {
        $name = $_POST['name'];
        $id = $_POST['id'];
        /*Patrones de validación*/
        $letrasNumeros = "/^[a-zA-Z0-9\s'-]+$/";
        if (empty($name)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letrasNumeros, $name)) {
                    $msg = array('msg' => 'No agrege caracteres indevidos en el nombre', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el usuario no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función storeUser en el Models/UsuariosModel.php*/
                    $data = $this->model->storeMarca($name);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Marca Registrada', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'La marca ya está registrada', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar la marca', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si la marca existe, se interpreta que se desea modificar esa marca,
                por ende lleva los datos a la función modifyMarca en el Models/MarcasModel.php*/
                $data = $this->model->modifyMarca($name, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Marca modificada', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al modificar la marca', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Editar: Envía a la función editMarca del Models/MarcasModel.php con el id correspondiente*/
    public function edit(int $id)
    {
        $data = $this->model->editMarca($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteMarca del Models/MarcasModel.php con el id correspondiente*/
    public function destroy(int $id)
    {
        $data = $this->model->deleteMarca($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar la marca', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Marca desactivada', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
