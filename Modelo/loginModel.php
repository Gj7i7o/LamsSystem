<?php

/*Modelo del Login: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class loginModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*tomarUsuario: En base a la contraseña y nombre de usuario ingresados, traerá un usuario que coincida con estos*/
    public function tomarUsuario(string $usuario, string $contrasena)
    {
        $sql = "SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$contrasena' AND estado = 'activo'";
        $data = $this->select($sql);
        return $data;
    }
}
