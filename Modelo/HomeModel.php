<?php

/*Modelo del Home*/

class homeModel extends query
{

    public function __construct()
    {
        // echo "Hola desde el modelo!";
    }

    public function consultas()
    {
        $sql = "SELECT * FROM usuario";
        $data = $this->select($sql);
        return $data;
    }
}
