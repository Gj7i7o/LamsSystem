<?php

/*Controlador de la Marca: Aquí se llaman a los métodos del modelo y validan datos*/

require_once "modelo/HistorialModel.php";

class marcas extends controlador
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
        $data = $this->model->tomarMarcasAc();
        $maxLength = 25;
        foreach ($data as $marca) {
            $etiquetaCompleta = $marca['nombre'];
            $etiquetaTruncada = mb_strlen($etiquetaCompleta) > $maxLength
                ? mb_substr($etiquetaCompleta, 0, $maxLength) . '...'
                : $etiquetaCompleta;
            $result[] = [
                'id' => $marca['id'],
                'etiqueta' => $etiquetaTruncada,
                'etiquetaCompleta' => $etiquetaCompleta
            ];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*listar: Se encarga de colocar las marcas existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado];
            $data = $this->model->tomarMarcas($params);
            $total = $this->model->getCount($params);
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['estado'] == 'activo') {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditMarca(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="warning" type="button" onclick="btnDesMarca(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                } else {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditMarca(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="secure" type="button" onclick="btnActMarca(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                }
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de una nueva marca en la base de datos*/
    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $id = $_POST['id'];
        /*Patrones de validación*/
        $letrasNumeros = "/^[a-zA-Z0-9\s'-]+$/";
        if (empty($nombre)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letrasNumeros, $nombre)) {
                    $msg = array('msg' => 'No agrege caracteres indevidos en el nombre', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si la marca no existe, se interpreta como una nueva, por ende
                    lleva los datos a la función regisMarca en el modelo/marcasModel.php*/
                    $data = $this->model->regisMarca($nombre);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Marca Registrada', 'icono' => 'success');
                        $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Marcas', 'registrar', "Registró marca: $nombre");
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'La marca ya está registrada', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar la marca', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si la marca existe, se interpreta que se desea modificar esa marca,
                por ende lleva los datos a la función modifMarca en el modelo/marcasModel.php*/
                $data = $this->model->modifMarca($nombre, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Marca modificada', 'icono' => 'success');
                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Marcas', 'modificar', "Modificó marca ID: $id - $nombre");
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La marca ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al modificar la marca', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*editar: Envía a la función editarMarca del modelo/marcasModel.php con el id correspondiente*/
    public function editar(int $id)
    {
        $data = $this->model->editarMarca($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desMarca del modelo/marcasModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        if ($this->model->tieneProductos($id)) {
            $msg = array('msg' => 'No se puede desactivar la marca porque tiene productos activos asociados', 'icono' => 'warning');
        } else {
            $data = $this->model->desMarca($id);
            if ($data == 1) {
                $msg = array('msg' => 'Error al desactivar la marca', 'icono' => 'error');
            } else {
                $msg = array('msg' => 'Marca desactivada', 'icono' => 'success');
                $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Marcas', 'desactivar', "Desactivó marca ID: $id");
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actMarca del modelo/marcasModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actMarca($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar la marca', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Marca activada', 'icono' => 'success');
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Marcas', 'activar', "Activó marca ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
