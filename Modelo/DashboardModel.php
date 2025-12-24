<?php

/*Modelo del Dashboard: Aquí se encuentran las consultas SQL 
que se preparan para ser enviadas al controlador*/

class dashboardModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*getDate: Selecciona una tabla y obtiene el total de objetos/items que poseé
    cuyo estado sea activo*/
    public function getDate(string $table)
    {
        $sql = "SELECT COUNT(*) AS total FROM $table WHERE estado = 'activo'";
        $data = $this->select($sql);
        return $data;
    }
}
