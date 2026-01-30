<?php

/*Modelo del Historial: Aquí se encuentran las consultas SQL
para registrar y consultar el historial de acciones de los usuarios*/

class historialModel extends query
{
    private $idusuario, $modulo, $accion, $descripcion, $fecha, $hora;

    public function __construct()
    {
        parent::__construct();
    }

    /*registrarAccion: Inserta un nuevo registro en el historial*/
    public function registrarAccion(int $idusuario, string $modulo, string $accion, string $descripcion)
    {
        $this->idusuario = $idusuario;
        $this->modulo = $modulo;
        $this->accion = $accion;
        $this->descripcion = $descripcion;
        date_default_timezone_set('America/Caracas');
        $this->fecha = date('d/m/Y');
        $this->hora = date('h:i:s a');

        $sql = "INSERT INTO historial_usuario (idusuario, modulo, accion, descripcion, fecha, hora) VALUES (?,?,?,?,?,?)";
        $datos = array($this->idusuario, $this->modulo, $this->accion, $this->descripcion, $this->fecha, $this->hora);
        $data = $this->save($sql, $datos);

        return ($data == 1) ? "ok" : "error";
    }

    /*getCount: Cuenta los registros según los filtros aplicados*/
    public function getCount(array $params)
    {
        $filters = $this->filtersSQL($params);
        $sql = "SELECT COUNT(*) as total FROM historial_usuario h
                INNER JOIN usuario u ON h.idusuario = u.id
                $filters";
        $data = $this->select($sql);
        return $data['total'];
    }

    /*filtersSQL: Genera el WHERE de la consulta según los filtros*/
    public function filtersSQL(array $params): string
    {
        $conditions = [];

        if ($params['modulo'] != "todo") {
            $modulo = $params['modulo'];
            $conditions[] = "h.modulo = '$modulo'";
        }

        if ($params['usuario'] != "todo") {
            $usuario = $params['usuario'];
            $conditions[] = "h.idusuario = '$usuario'";
        }

        if (!empty($params['query'])) {
            $query = $params['query'];
            $conditions[] = "(h.descripcion LIKE '%$query%' OR h.accion LIKE '%$query%' OR u.usuario LIKE '%$query%')";
        }

        if (!empty($params['fecha_desde'])) {
            $fecha_desde = $params['fecha_desde'];
            $conditions[] = "DATE(h.creadoEl) >= '$fecha_desde'";
        }

        if (!empty($params['fecha_hasta'])) {
            $fecha_hasta = $params['fecha_hasta'];
            $conditions[] = "DATE(h.creadoEl) <= '$fecha_hasta'";
        }

        $filter = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
        return $filter;
    }

    /*tomarHistorial: Toma todos los registros del historial con paginación y filtros*/
    public function tomarHistorial(array $params)
    {
        $offset = ($params["page"] - 1) * 10;
        $filters = $this->filtersSQL($params);

        $sql = "SELECT h.id, u.usuario, h.modulo, h.accion, h.descripcion, h.fecha, h.hora
                FROM historial_usuario h
                INNER JOIN usuario u ON h.idusuario = u.id
                $filters
                ORDER BY h.id DESC
                LIMIT 10 OFFSET $offset";

        $data = $this->selectAll($sql);
        return $data;
    }

    /*obtenerModulos: Obtiene la lista de módulos únicos para el select de filtro*/
    public function obtenerModulos()
    {
        $sql = "SELECT DISTINCT modulo FROM historial_usuario ORDER BY modulo ASC";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*obtenerUsuarios: Obtiene la lista de usuarios que tienen registros en el historial*/
    public function obtenerUsuarios()
    {
        $sql = "SELECT DISTINCT u.id, u.usuario
                FROM usuario u
                INNER JOIN historial_usuario h ON u.id = h.idusuario
                ORDER BY u.usuario ASC";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*tomarHistorialTodo: Toma todos los registros del historial sin paginación (para reportes PDF)*/
    public function tomarHistorialTodo(array $params)
    {
        $filters = $this->filtersSQL($params);

        $sql = "SELECT h.id, u.usuario, h.modulo, h.accion, h.descripcion, h.fecha, h.hora
                FROM historial_usuario h
                INNER JOIN usuario u ON h.idusuario = u.id
                $filters
                ORDER BY h.id DESC";

        $data = $this->selectAll($sql);
        return $data;
    }
}
