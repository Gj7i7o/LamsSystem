<?php

/*La clase Views redirecciona dependiendo del controlador y la vista en el momento*/

class vista
{
    public function getView($controlador, $vista, $data = "")
    {
        $controlador = get_class($controlador);
        if ($controlador == "home") {
            $vista = "vista/" . $vista . ".php";
        } else {
            $vista = "vista/" . $controlador . "/" . $vista . ".php";
        }
        require $vista;
    }
}
