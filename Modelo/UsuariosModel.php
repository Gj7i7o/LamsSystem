<?php

/*Modelo del Usuario*/

class UsuariosModel extends Query
{
    private $user, $name, $ape, $email, $telef, $password, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getCount()
    {
        $sql = "SELECT * FROM usuario";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getUser: En base a la contraseña y nombre de usuario ingresados, traerá un usuario que coincida con estos*/
    public function getUser(string $user, string $password)
    {
        $sql = "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$password'";
        $data = $this->select($sql);
        return $data;
    }

    /*getUsers: Toma todos los usuarios de la base de datos*/
    public function getUsers(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM usuario" : "SELECT * FROM usuario LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*storeUser: Guarda el usuario, y además verifica si el usuario existe, en base al nombre de usuario ingresado*/
    public function storeUser(string $user, string $name, string $ape, string $email, string $telef, string $password)
    {
        $this->user = $user;
        $this->name = $name;
        $this->ape = $ape;
        $this->email = $email;
        $this->telef = $telef;
        $this->password = $password;
        $verificar = "SELECT * FROM usuario WHERE usuario = '$this->user'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO usuario (usuario, nombre, apellido, correo, telef, clave, rango, estado) VALUES (?,?,?,?,?,?,'empleado','activo')";
            $datos = array($this->user, $this->name, $this->ape, $this->email, $this->telef, $this->password);
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

    /*modifyUsers: Modifica el usuario*/
    public function modifyUsers(string $user, string $name, string $ape, string $email, string $telef, int $id)
    {
        $this->user = $user;
        $this->name = $name;
        $this->ape = $ape;
        $this->email = $email;
        $this->telef = $telef;
        // $this->password = $hash;
        $this->id = $id;
        $sql = "UPDATE usuario SET usuario = ?, nombre = ?, apellido = ?, correo = ?, telef = ? WHERE id = ?";
        $datos = array($this->user, $this->name, $this->ape, $this->email, $this->telef, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editUser: Hace la consulta SQL que traerá al usuario que posteriormente se modificará*/
    public function editUser(int $id)
    {
        $sql = "SELECT * FROM usuario WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteUser: Hace la consulta SQL que traerá al usuario que posteriormente se eliminará*/
    public function desUsuario(int $id)
    {
        $sql = "UPDATE usuario SET estado = 'Inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
