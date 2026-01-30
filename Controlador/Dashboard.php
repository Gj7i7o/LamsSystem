<?php

/*Controlador Dashboard: Aquí se llaman a los métodos del modelo*/

class dashboard extends controlador
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
        $data['stock_bajo'] = $this->model->getProductosBajoStock();
        $this->vista->getView($this, "index", $data);
    }
}
