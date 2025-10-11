<?php

/*Modelo del Dashboard*/

class DashboardModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    /*getDate: Selecciona una tabla y obtiene el total de objetos/items que poseé*/
    public function getDate(string $table)
    {
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $data = $this->select($sql);
        return $data;
    }
}
