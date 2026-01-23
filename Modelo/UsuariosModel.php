<?php

/*Modelo del Usuario: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class usuariosModel extends query
{
    private $usuario, $ci, $nombre, $apellido, $correo, $telef, $contrasena, $id_usuario, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCountIn()
    {
        $sql = "SELECT * FROM usuario WHERE estado = 'inactivo'";
        $data = $this->selectAll($sql);
        return count($data);
    }

    public function getCountAc()
    {
        $sql = "SELECT * FROM usuario WHERE estado = 'activo'";
        $data = $this->selectAll($sql);
        return count($data);
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
    public function regisUsuario(string $usuario, string $contrasena)
    {
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
        $verificar = "SELECT * FROM usuario WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO usuario (usuario, clave, rango, estado) VALUES (?,?,'empleado','activo')";
            $datos = array($this->usuario, $this->contrasena);
            $data = $this->insertar($sql, $datos);
            if ($data > 0) {
                return $data; // Retorna el ID de la entrada para usarlo en los detalles
            } else {
                return 0;
            }
        } else {
            return "existe";
        }

        return $res;
    }

    public function regisPersona(string $ci, string $nombre, string $apellido, string $telef, string $correo, int $id_usuario)
    {
        $this->ci = $ci;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telef = $telef;
        $this->correo = $correo;
        $this->id_usuario = $id_usuario;
        $verificar = "SELECT * FROM persona WHERE ci = '$this->ci'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO persona (ci, nombre, apellido, telef, correo, idusuario) VALUES (?,?,?,?,?,?)";
            $datos = array($this->ci, $this->nombre, $this->apellido, $this->telef, $this->correo, $this->id_usuario);
            $data = $this->save($sql, $datos);
            if ($data == 1) {
                return "ok";
            } else {
                return "error";
            }
        } else {
            return "existe";
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
