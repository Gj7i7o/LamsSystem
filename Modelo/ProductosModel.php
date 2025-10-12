<?php

/*Modelo del Producto*/

class ProductosModel extends Query
{
    private $code, $name, $price, $idcat, $idmar, $id;
    public function __construct()
    {
        parent::__construct();
    }

     public function getCount()
    {
        $sql = "SELECT * FROM producto";
        $data = $this->selectAll($sql);
        return count($data);
    }

    /*getProduct: Toma todos los productos de la base de datos*/
    public function getProduct(int $page = 0)
    {
        $offset = ($page - 1) * 5;
        $sql = $page <= 0 ? "SELECT * FROM producto" : "SELECT * FROM producto LIMIT 5 OFFSET $offset";
        $data = $this->selectAll($sql);
        return $data;
    }

    /*storeProduct: Guarda el producto, y además verifica si el producto existe, en base al nombre, precio y stock ingresados*/
    public function storeProduct(string $code, string $name, float $price, int $idcat, int $idmar)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->idcat = $idcat;
        $this->idmar = $idmar;
        $verificar = "SELECT * FROM producto WHERE nombre = '$this->name' AND codigo = '$this->code'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO producto (codigo, nombre, precio, cantidad, idcategoria, idmarca, estado) VALUES (?,?,?,?,?,?,'activo')";
            $datos = array($this->code, $this->name, $this->price, $this->idcat, $this->idmar);
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

    /*modifyProduct: Modifica el producto*/
    public function modifyProduct(string $code, string $name, float $price, int $idcat, int $idmar, int $id)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->idcat = $idcat;
        $this->idmar = $idmar;
        $this->id = $id;
        $sql = "UPDATE producto SET codigo = ?, nombre = ?, precio = ?, idcategoria = ?, idmarca = ? WHERE id = ?";
        $datos = array($this->code, $this->name, $this->price, $this->idcat, $this->idmar, $this->id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    /*editProduct: Hace la consulta SQL que traerá el producto que posteriormente se modificará*/
    public function editProduct(int $id)
    {
        $sql = "SELECT * FROM producto WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    /*deleteProduct: Hace la consulta SQL que traerá el producto que posteriormente se eliminará*/
    public function deleteProduct(int $id)
    {
        $sql = "UPDATE producto SET estado = 'Inactivo' WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
}
