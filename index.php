<?php
/* URL de la configuración para iniciar desde el controlador Home */
require_once "config/config.php";
$ruta = !empty($_GET['url']) ? $_GET['url'] : "home/index";
$array = explode("/", $ruta);
$controller = $array[0];
$metodo = "index";
$parametro = "";
if (!empty($array[1])) {
    if (!empty($array[1] != "")) {
        $metodo = $array[1];
    }
}

/* Enrutamiento si el controlador existe */
require_once "config/app/autoload.php";
$dirControllers = "controlador/" . $controller . ".php";
if (file_exists($dirControllers)) {
    require_once $dirControllers;
    $controller = new $controller();
    if (method_exists($controller, $metodo)) {
        $controller->$metodo($parametro);
    } else {
        echo "No existe el metodo";
    }
} else {
    echo "No existe el controlador";
}
