<?php

/*Controlador del Proveedor: Aquí se llaman a los métodos del modelo y validan datos*/

require_once "modelo/HistorialModel.php";

class proveedores extends controlador
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
        $data = $this->model->tomarProveedoresAc();
        $maxLength = 25;
        foreach ($data as $proveedor) {
            $etiquetaCompleta = $proveedor['nombre'];
            $etiquetaTruncada = mb_strlen($etiquetaCompleta) > $maxLength
                ? mb_substr($etiquetaCompleta, 0, $maxLength) . '...'
                : $etiquetaCompleta;
            $result[] = [
                'id' => $proveedor['id'],
                'etiqueta' => $etiquetaTruncada,
                'etiquetaCompleta' => $etiquetaCompleta
            ];
        }
        echo json_encode(["data" => $result], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*buscarPorRif: Busca un proveedor activo por su rif exacto*/
    public function buscarPorRif()
    {
        // Validamos que haya al menos un caracter
        $rif = $_GET["rif"] ?? "";
        // Opcional: Solo buscar si tiene más de 2 caracteres para optimizar
        if (strlen($rif) < 1) {
            echo json_encode([]);
            die();
        }
        $data = $this->model->buscarProveedorPorRif($rif);
        // Devolvemos el array de objetos directamente (JSON Array)
        if (!empty($data)) {
            // Devolvemos la lista de coincidencias
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            // Array vacío si no hay coincidencias
            echo json_encode([]);
        }
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
            $fecha_desde = $_GET["fecha_desde"] ?? "";
            $fecha_hasta = $_GET["fecha_hasta"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta];
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
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'] ?? '';
        $persona_contacto = $_POST['persona_contacto'] ?? '';
        $id = $_POST['id'];
        $codigo = "/^[JGVEP][-][0-9]{7,9}+$/";
        if (empty($nombre) || empty($rif) || empty($direccion)) {
            $msg = array('msg' => 'Todos los campos obligatorios deben ser completados', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el proveedor no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisProveedor en el modelo/proveedoresModel.php*/
                    $data = $this->model->regisProveedor($nombre, $rif, $direccion, $telefono, $persona_contacto);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Proveedor Registrado', 'icono' => 'success');
                        $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Proveedores', 'registrar', "Registró proveedor: $nombre (RIF: $rif)");
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El proveedor ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el proveedor', 'icono' => 'error');
                    }
                }
            } else {
                if (!preg_match($codigo, $rif)) {
                    $msg = array('msg' => 'Introduzca el rif correctamente', 'icono' => 'warning');
                } else {
                    /*Caso contrario, si el proveedor existe, se interpreta que se desea modificar ese proveedor,
                por ende lleva los datos a la función modifProveedor en el modelo/proveedoresModel.php*/
                    $data = $this->model->modifProveedor($nombre, $rif, $direccion, $telefono, $persona_contacto, $id);
                    if ($data == "modificado") {
                        $msg = array('msg' => 'Proveedor modificado', 'icono' => 'success');
                        $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Proveedores', 'modificar', "Modificó proveedor ID: $id - $nombre");
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El proveedor ya existe', 'icono' => 'warning');
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
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Proveedores', 'desactivar', "Desactivó proveedor ID: $id");
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
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Proveedores', 'activar', "Activó proveedor ID: $id");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF con todos los proveedores*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $estado = $_GET["estado"] ?? "todo";
        $query = $_GET["query"] ?? "";
        $fecha_desde = $_GET["fecha_desde"] ?? "";
        $fecha_hasta = $_GET["fecha_hasta"] ?? "";
        $params = ['page' => 1, 'query' => $query, 'estado' => $estado, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta];

        $proveedores = $this->model->tomarProveedoresTodos($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('proveedores_pdf', [
            'proveedores' => $proveedores,
            'filtro_fecha_desde' => $fecha_desde,
            'filtro_fecha_hasta' => $fecha_hasta
        ])->generar('Reporte_Proveedores_' . date('Y-m-d') . '.pdf');
    }
}
