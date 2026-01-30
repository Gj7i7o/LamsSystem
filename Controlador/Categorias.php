<?php

/*Controlador de la Categoría: Aquí se llaman a los métodos del modelo y validan datos*/

require_once "modelo/HistorialModel.php";

class categorias extends controlador
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
        $data = $this->model->tomarCategoriasAc();
        $maxLength = 25;
        foreach ($data as $categoria) {
            $etiquetaCompleta = $categoria['nombre'];
            $etiquetaTruncada = mb_strlen($etiquetaCompleta) > $maxLength
                ? mb_substr($etiquetaCompleta, 0, $maxLength) . '...'
                : $etiquetaCompleta;
            $result[] = [
                'id' => $categoria['id'],
                'etiqueta' => $etiquetaTruncada,
                'etiquetaCompleta' => $etiquetaCompleta
            ];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*listar: Se encarga de colocar las categorías existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado];
            $data = $this->model->tomarCategorias($params);
            $total = $this->model->getCount($params);
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['estado'] == 'activo') {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditCategoria(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="warning" type="button" onclick="btnDesCategoria(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                } else {
                    $data[$i]['acciones'] = '<div>
                <button class="primary" type="button" onclick="btnEditCategoria(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="secure" type="button" onclick="btnActCategoria(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                }
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de una nueva categoría en la base de datos*/
    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $id = $_POST['id'];
        if (empty($nombre)) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                /*Tras las validaciones, si la categoría no existe, se interpreta como una nueva, por ende
                lleva los datos a la función regisCategoria en el modelo/categoriaModel.php*/
                $data = $this->model->regisCategoria($nombre, $descripcion);
                if ($data == "ok") {
                    $msg = array('msg' => 'Categoría Registrada', 'icono' => 'success');
                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Categorías', 'registrar', "Registró categoría: $nombre");
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La categoría ya está registrada', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al registrar la categoría', 'icono' => 'error');
                }
            } else {
                /*Caso contrario, si la categoría existe, se interpreta que se desea modificar esa categoría,
                por ende lleva los datos a la función modifCategoria en el modelo/categoriasModel.php*/
                $data = $this->model->modifCategoria($nombre, $descripcion, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Categoría modificada', 'icono' => 'success');
                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Categorías', 'modificar', "Modificó categoría ID: $id - $nombre");
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La categoría ya existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Error al modificar la categoría', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*editar: Envía a la función editarCategoria del modelo/categoriasModel.php con el id correspondiente*/
    public function editar(int $id)
    {
        $data = $this->model->editarCategoria($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desCategoria del modelo/categoriasModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        if ($this->model->tieneProductos($id)) {
            $msg = array('msg' => 'No se puede desactivar la categoría porque tiene productos activos asociados', 'icono' => 'warning');
        } else {
            $data = $this->model->desCategoria($id);
            if ($data == 1) {
                $msg = array('msg' => 'Error al desactivar la categoría', 'icono' => 'error');
            } else {
                $msg = array('msg' => 'Categoría desactivada', 'icono' => 'success');
                $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Categorías', 'desactivar', "Desactivó categoría ID: $id");
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*activar: Envía a la función actCategoria del modelo/categoriasModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actCategoria($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar la categoría', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Categoría activada', 'icono' => 'success');
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Categorías', 'activar', "Activó categoría ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF con todas las categorías*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $estado = $_GET["estado"] ?? "todo";
        $query = $_GET["query"] ?? "";
        $params = ['page' => 1, 'query' => $query, 'estado' => $estado];

        $categorias = $this->model->tomarCategoriasTodas($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('categorias_pdf', [
            'categorias' => $categorias
        ])->generar('Reporte_Categorias_' . date('Y-m-d') . '.pdf');
    }
}
