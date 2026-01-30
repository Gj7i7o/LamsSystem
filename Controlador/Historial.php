<?php

/*Controlador del Historial: Aquí se llaman a los métodos del modelo para visualizar el historial de acciones*/

class historial extends controlador
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        // Verificar que el usuario sea administrador
        if ($_SESSION['rango'] != 'administrador') {
            header("location: " . APP_URL . "dashboard");
            exit();
        }
        parent::__construct();
    }

    /*Vista: Trae la vista correspondiente*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*listar: Se encarga de colocar los registros del historial con paginación y filtros*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $query = $_GET["query"] ?? "";
            $modulo = $_GET["modulo"] ?? "todo";
            $usuario = $_GET["usuario"] ?? "todo";

            $fecha_desde = $_GET["fecha_desde"] ?? "";
            $fecha_hasta = $_GET["fecha_hasta"] ?? "";

            $params = [
                'page' => $page,
                'query' => $query,
                'modulo' => $modulo,
                'usuario' => $usuario,
                'fecha_desde' => $fecha_desde,
                'fecha_hasta' => $fecha_hasta
            ];

            $data = $this->model->tomarHistorial($params);
            $total = $this->model->getCount($params);

            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*getModulos: Obtiene la lista de módulos para el filtro*/
    public function getModulos()
    {
        $data = $this->model->obtenerModulos();
        echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*getUsuarios: Obtiene la lista de usuarios para el filtro*/
    public function getUsuarios()
    {
        $data = $this->model->obtenerUsuarios();
        echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
        die();
    }

    /*reportePDF: Genera un reporte PDF del historial de acciones*/
    public function reportePDF()
    {
        require_once "config/app/PdfGenerator.php";
        $query = $_GET["query"] ?? "";
        $modulo = $_GET["modulo"] ?? "todo";
        $usuario = $_GET["usuario"] ?? "todo";

        $fecha_desde = $_GET["fecha_desde"] ?? "";
        $fecha_hasta = $_GET["fecha_hasta"] ?? "";

        $params = [
            'page' => 1,
            'query' => $query,
            'modulo' => $modulo,
            'usuario' => $usuario,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta
        ];

        $historial = $this->model->tomarHistorialTodo($params);

        $pdf = new pdfGenerator();
        $pdf->cargarVista('historial_pdf', [
            'historial' => $historial,
            'filtro_fecha_desde' => $fecha_desde,
            'filtro_fecha_hasta' => $fecha_hasta
        ])->generar('Historial_Acciones_' . date('Y-m-d') . '.pdf', 'landscape');
    }
}
