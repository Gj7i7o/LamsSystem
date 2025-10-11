<?php

/*La clase Views redirecciona dependiendo del controlador y la vista en el momento*/

class Vista
{
    public function getView($controlador, $vista, $data = "")
    {
        $controlador = get_class($controlador);
        if ($controlador == "Home") {
            $vista = "Vista/" . $vista . ".php";
        } else {
            $vista = "Vista/" . $controlador . "/" . $vista . ".php";
        }
        require $vista;
    }
}
