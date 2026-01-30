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

    /*getCount: Cuenta los usuarios según el estado y búsqueda*/
    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM usuario $filters";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(string $value, string $estado): string
    {
        $conditions = [];
        if ($estado != "todo") {
            $conditions[] = "estado = '$estado'";
        }
        if (!empty($value)) {
            $conditions[] = "(usuario LIKE '%$value%' OR rango LIKE '%$value%')";
        }
        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarUsuarios: Toma todos los usuarios de la base de datos filtrando por estado y búsqueda*/
    public function tomarUsuarios(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT * FROM usuario $filters ORDER BY id DESC LIMIT 10 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarUsuariosTodos: Toma todos los usuarios sin paginación (para reportes PDF)*/
    public function tomarUsuariosTodos(array $params)
    {
        $filters = $this->filtersSQL($params["query"], $params["estado"]);
        $sql = "SELECT u.*, p.nombre, p.apellido, p.correo, p.telef
                FROM usuario u
                LEFT JOIN persona p ON u.id = p.idusuario
                $filters ORDER BY u.id DESC";
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
    public function modifUsuario(string $ci, string $usuario, string $nombre, string $apellido, string $correo, string $telef, int $id)
    {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telef = $telef;
        $this->ci = $ci;
        $this->id = $id;
        $verificarUsuario = "SELECT * FROM usuario WHERE usuario = '$this->usuario' AND id != '$this->id'";
        $existeUsuario = $this->select($verificarUsuario);
        $verificarPersona = "SELECT * FROM persona WHERE ci = '$this->ci' AND id != '$this->id'";
        $existePersona = $this->select($verificarPersona);
        if (empty($existeUsuario) || empty($existePersona)) {
            // Actualizar tabla usuario
            $sqlUsuario = "UPDATE usuario SET usuario = ? WHERE id = ?";
            $datosUsuario = array($this->usuario, $this->id);
            $dataUsuario = $this->save($sqlUsuario, $datosUsuario);

            // Actualizar tabla persona
            $sqlPersona = "UPDATE persona SET nombre = ?, apellido = ?, correo = ?, telef = ? WHERE idusuario = ?";
            $datosPersona = array($this->nombre, $this->apellido, $this->correo, $this->telef, $this->id);
            $dataPersona = $this->save($sqlPersona, $datosPersona);

            if ($dataUsuario == 1 || $dataPersona == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            return "existe";
        }
        return $res;
    }

    /*editarUsuario: Hace la consulta SQL que traerá al usuario que posteriormente se modificará*/
    public function editarUsuario(int $id)
    {
        $sql = "SELECT u.*, p.ci, p.nombre, p.apellido, p.correo, p.telef
                FROM usuario u
                LEFT JOIN persona p ON u.id = p.idusuario
                WHERE u.id = $id";
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
