<?php

/*Controlador del Proveedor*/

class Proveedores extends Controlador
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
        $data = $this->model->getActProveedores();
        foreach ($data as $proveedor) {
            $result[] = ['id' => $proveedor['id'], 'etiqueta' => $proveedor['nombre']];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Listado: Se encarga de colocar los proveedores existentes en la base de datos 
    y a su vez coloca en cada uno los botones de editar y eliminar*/
    public function listarInactivos()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getInaProveedores($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditProveedor(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="secure" type="button" onclick="btnActProveedor(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Listado: Se encarga de colocar los proveedores existentes en la base de datos 
    y a su vez coloca en cada uno los botones de editar y eliminar*/
    public function listarActivos()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getActProveedores($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditProveedor(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDesProveedor(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Almacenaje: Se encarga de almacenar los datos de un nuevo proveedor en la base de datos*/
    public function store()
    {
        $rif = $_POST['rif'];
        $name = $_POST['nombre'];
        $ape = $_POST['apellido'];
        $dir = $_POST['dir'];
        $id = $_POST['id'];
        $letras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/";
        $codigo = "/^[JGVEP][-][0-9]{7,9}+$/";
        if (empty($name) || empty($ape) || empty($rif) || empty($dir)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letras, $name)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $ape)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el apellido', 'icono' => 'warning');
                } else if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el proveedor no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función storeProveedores en el Models/ProveedoresModel.php*/
                    $data = $this->model->storeProveedores($name, $ape, $rif, $dir);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Proveedor Registrado', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El proveedor ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el proveedor', 'icono' => 'error');
                    }
                }
            } else {
                if (!preg_match($letras, $name)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $ape)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el apellido', 'icono' => 'warning');
                } else if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Caso contrario, si el proveedor existe, se interpreta que se desea modificar ese proveedor,
                por ende lleva los datos a la función modifyProveedores en el Models/ProveedoresModel.php*/
                    $data = $this->model->modifyProveedores($name, $ape, $rif, $dir, $id);
                    if ($data == "modificado") {
                        $msg = array('msg' => 'Proveedor modificado', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al modificar el proveedor', 'icono' => 'error');
                    }
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Editar: Envía a la función editProveedores del Models/ProveedoresModel.php con el id correspondiente*/
    public function edit(int $id)
    {
        $data = $this->model->editProveedores($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteProveedores del Models/ProveedoresModel.php con el id correspondiente*/
    public function destroy(int $id)
    {
        $data = $this->model->deleteProveedores($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar el proveedor', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Proveedor desactivado', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteProveedores del Models/ProveedoresModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->activarProveedores($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar el proveedor', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Proveedor activado', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
