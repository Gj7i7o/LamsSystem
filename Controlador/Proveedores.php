<?php

/*Controlador del Proveedor: Aquí se llaman a los métodos del modelo y validan datos*/

class proveedores extends controlador
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
        $data = $this->model->tomarProveedoresAc();
        foreach ($data as $proveedor) {
            $result[] = ['id' => $proveedor['id'], 'etiqueta' => $proveedor['nombre']];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*listar: Se encarga de colocar los proveedores existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado];
            $data = $this->model->tomarProveedores($params);
            $total = $this->model->getCount($params);
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['estado'] == 'activo') {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditProveedor(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="warning" type="button" onclick="btnDesProveedor(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                } else {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditProveedor(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="secure" type="button" onclick="btnActProveedor(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                }
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de un nuevo proveedor en la base de datos*/
    public function registrar()
    {
        $rif = $_POST['rif'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $id = $_POST['id'];
        $letras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/";
        $codigo = "/^[JGVEP][-][0-9]{7,9}+$/";
        if (empty($nombre) || empty($apellido) || empty($rif) || empty($direccion)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letras, $nombre)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $apellido)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el apellido', 'icono' => 'warning');
                } else if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el proveedor no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisProveedor en el modelo/proveedoresModel.php*/
                    $data = $this->model->regisProveedor($nombre, $apellido, $rif, $direccion);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Proveedor Registrado', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El proveedor ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el proveedor', 'icono' => 'error');
                    }
                }
            } else {
                if (!preg_match($letras, $nombre)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $apellido)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en el apellido', 'icono' => 'warning');
                } else if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Caso contrario, si el proveedor existe, se interpreta que se desea modificar ese proveedor,
                por ende lleva los datos a la función modifProveedor en el modelo/proveedoresModel.php*/
                    $data = $this->model->modifProveedor($nombre, $apellido, $rif, $direccion, $id);
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

    /*editar: Envía a la función editarProveedor del modelo/proveedoresModel.php con el id correspondiente*/
    public function editar(int $id)
    {
        $data = $this->model->editarProveedor($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desProveedor del modelo/proveedoresModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        $data = $this->model->desProveedor($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al desactivar el proveedor', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Proveedor desactivado', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actProveedor del modelo/proveedoresModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actProveedor($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar el proveedor', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Proveedor activado', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
