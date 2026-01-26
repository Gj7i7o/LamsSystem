<?php

/*Controlador Home*/

class home extends controlador
{

    public function __construct()
    {
        session_start();
        if (!empty($_SESSION['activo'])) {
            header("location: " . APP_URL . "Dashboard/index");
        }
        parent::__construct();
    }

    /*Si la sesión está activa, redirecciona al Dashboard/index. Caso contrario
    redirecciona al login*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*logout: Cierra la sesión si el usuario preciona el botón de salir en el Dashboard*/
    public function logout()
    {
        session_destroy();
        header("location: " . APP_URL);
    }
}
