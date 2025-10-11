<?php

/*Modelo del Home*/

class HomeModel extends Query
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
