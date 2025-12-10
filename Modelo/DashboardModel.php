<?php

/*Modelo del Dashboard*/

class dashboardModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*getDate: Selecciona una tabla y obtiene el total de objetos/items que poseé*/
    public function getDate(string $table)
    {
        $sql = "SELECT COUNT(*) AS total FROM $table WHERE estado = 'activo'";
        $data = $this->select($sql);
        return $data;
    }
}
