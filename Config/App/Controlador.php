<?php

/*Clase controlador se encarga de cargar el modelo y la vista que se usará*/

class Controlador
{
    protected $model, $vista;
    public function __construct()
    {
        // Declara la vista
        $this->vista = new Vista();
        // Declara el metodo cargarModel
        $this->cargarModel();
    }

    public function cargarModel()
    {
        // Declarar el archivo modeloModel
        $model = get_class($this) . "Model";
        // Declarar la ruta de la carpeta Models
        $ruta = "Modelo/" . $model . ".php";
        // Verificar si existe ese modelo
        if (file_exists($ruta)) {
            require_once $ruta;
            $this->model = new $model();
        }
    }
}
