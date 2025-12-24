<?php

/*Modelo del Usuario: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class usuariosModel extends query
{
    private $usuario, $nombre, $apellido, $correo, $telef, $contrasena, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM usuario WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*tomarUsuario: En base a la contraseña y nombre de usuario ingresados, traerá un usuario que coincida con estos*/
    public function tomarUsuario(string $usuario, string $contrasena)
    {
        $sql = "SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$contrasena'";
        $data = $this->select($sql);
        return $data;
    }

    /*tomarUsuariosIn: Toma todos los usuarios de la base de datos que tengan el estado inactivo*/
    public function tomarUsuariosIn(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM usuario WHERE estado = 'inactivo'" : "SELECT * FROM usuario WHERE estado = 'inactivo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarUsuariosAc: Toma todos los usuarios de la base de datos que tengan el estado activo*/
    public function tomarUsuariosAc(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM usuario WHERE estado = 'activo'" : "SELECT * FROM usuario WHERE estado = 'activo' LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*regisUsuario: Guarda el usuario, y además verifica si el usuario existe,
    en base al usuario ingresado, comparando con la base de datos*/
    public function regisUsuario(string $usuario, string $nombre, string $apellido, string $correo, string $telef, string $contrasena)
    {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telef = $telef;
        $this->contrasena = $contrasena;
        $verificar = "SELECT * FROM usuario WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO usuario (usuario, nombre, apellido, correo, telef, clave, rango, estado) VALUES (?,?,?,?,?,?,'empleado','activo')";
            $datos = array($this->usuario, $this->nombre, $this->apellido, $this->correo, $this->telef, $this->contrasena);
            $data = $this->save($sql, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    /*modifUsuario: Modifica el usuario seleccionado acorde al id*/
    public function modifUsuario(string $usuario, string $nombre, string $apellido, string $correo, string $telef, int $id)
    {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telef = $telef;
        $this->id = $id;
        $sql = "UPDATE usuario SET usuario = ?, nombre = ?, apellido = ?, correo = ?, telef = ? WHERE id = ?";
        $datos = array($this->usuario, $this->nombre, $this->apellido, $this->correo, $this->telef, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editarUsuario: Hace la consulta SQL que traerá al usuario que posteriormente se modificará*/
    public function editarUsuario(int $id)
    {
        $sql = "SELECT * FROM usuario WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*desUsuario: Hace la consulta SQL que traerá al usuario que posteriormente se cambiará su estado a inactivo*/
    public function desUsuario(int $id)
    {
        $sql = "UPDATE usuario SET estado = 'inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*actUsuario: Hace la consulta SQL que traerá al usuario que posteriormente se cambiará su estado a activo*/
    public function actUsuario(int $id)
    {
        $sql = "UPDATE usuario SET estado = 'activo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
