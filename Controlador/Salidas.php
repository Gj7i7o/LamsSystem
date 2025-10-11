<?php

/*Controlador de la Categoría*/

class Salidas extends Controlador
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
    public function listProductSalida()
    {
        $data = $this->model->getProduct();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
                <button class="warning" type="button" onclick="btn(' . $data[$i]['id'] . ');" title="Cantidad de salida"><i class="fa-solid fa-minus"></i></button>
                </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listSalida()
    {
        $data = $this->model->getSalida();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
                <button class="info" type="button" onclick="btnShowInfo(' . $data[$i]['id'] . ');" title="Información extra"><i class="fa-solid fa-info"></i></button>
                </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

}
