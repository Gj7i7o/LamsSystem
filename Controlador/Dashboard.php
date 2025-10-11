<?php

/*Controlador Dashboard*/

class Dashboard extends Controlador
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        parent::__construct();
    }

    /*Ésta función está encargada de extraer el total de objetos/items de cada campo de la base de datos*/
    public function index()
    {
        $data['usuario'] = $this->model->getDate('usuario');
        $data['categoria'] = $this->model->getDate('categoria');
        $data['producto'] = $this->model->getDate('producto');
        $data['proveedor'] = $this->model->getDate('proveedor');
        $data['marca'] = $this->model->getDate('marca');
        $this->vista->getView($this, "index", $data);
    }
}
