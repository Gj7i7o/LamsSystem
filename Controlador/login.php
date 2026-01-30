<?php

/*Controlador del Login: Aquí se llaman a los métodos del modelo y validan datos*/

class login extends controlador
{

    public function __construct()
    {
        session_start();
        if (!empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
            exit;
        }
        parent::__construct();
    }

    /*Vista: Trae la vista correspóndiente*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*validar: Comprueba si el usuario y contraseña ingresados corresponden a algún usuario 
    existente en la base de datos, si no existe, no accede al sistema. Caso contrario accede*/
    public function validar()
    {
        if (
            empty($_POST['usuario']) ||
            empty($_POST['contrasena']) ||
            !isset($_POST['usuario']) ||
            !isset($_POST['contrasena'])
        ) {
            $msg = "Los campos están vacios";
        } else {
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $hash = hash("SHA256", $contrasena);
            $data = $this->model->tomarUsuario($usuario, $hash);
            if ($data) {
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['rango'] = $data['rango'];
                $_SESSION['activo'] = true;
                $msg = "ok";
            } else {
                $msg = array('msg' => 'Usuario o Contraseña incorrecta / Su usuario ha sido desactivado', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
