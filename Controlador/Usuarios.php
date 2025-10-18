<?php

/*Controlador del Usuario*/

class Usuarios extends Controlador
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        parent::__construct();
    }

    /*Vista: Trae la vista correspóndiente*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*Listado: Se encarga de colocar los usuarios existentes en la base de datos 
    y a su vez coloca en cada uno los botones de editar y eliminar*/
    public function list()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->getUsers($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditUsuario(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDesUsuario(' . $data[$i]['id'] . ');" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*Validación: Comprueba si el usuario y contraseña ingresados corresponde a algún usuario 
    existente en la base de datos, si no existe, no accede al sistema. Caso contrario accede*/
    public function validar()
    {
        if (
            empty($_POST['usuario']) ||
            empty($_POST['contrasena']) ||
            !isset($_POST['usuario']) ||
            !isset($_POST['contrasena'])
        ) {
            $msg = "Los campos están vacios";
        } else {
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $hash = hash("SHA256", $contrasena);
            $data = $this->model->getUser($usuario, $hash);
            if ($data) {
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['rango'] = $data['rango'];
                $_SESSION['activo'] = true;
                $msg = "ok";
            } else {
                // $msg = array('msg' => 'Usuario o Contraseña incorrecta', 'icono' => 'warning');
                $msg = "Usuario o Contraseña incorrecta";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Almacenaje: Se encarga de almacenar los datos de un nuevo usuario en la base de datos*/
    public function store()
    {
        $user = $_POST['usuario'];
        $name = $_POST['nombre'];
        $ape = $_POST['apellido'];
        $email = $_POST['correo'];
        $telef = $_POST['telef'];
        $password = $_POST['password'];
        $id = $_POST['id'];
        $hash = hash("SHA256", $password);
        /*Patrones de validación*/
        $letras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/";
        $pass = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/";
        $correo = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $phone = "/^04(12|14|16|24|26)-\d{7}$/";
        if (
            empty($user) ||
            empty($name) ||
            empty($ape) ||
            empty($email) ||
            empty($telef)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letras, $name)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en su nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $ape)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en su apellido', 'icono' => 'warning');
                } else if (!preg_match($pass, $password)) {
                    $msg = array('msg' => 'La contraseña NO cumple con las especificaciones', 'icono' => 'warning');
                } else if (!preg_match($correo, $email)) {
                    $msg = array('msg' => 'Escriba correctamente el correo', 'icono' => 'warning');
                } else if (!preg_match($phone, $telef)) {
                    $msg = array('msg' => 'Escriba correctamente el teléfono', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el usuario no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función storeUser en el Models/UsuariosModel.php*/
                    $data = $this->model->storeUser($user, $name, $ape, $email, $telef, $hash);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Usuario Registrado', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El Usuario ya está registrado', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar el Usuario', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si el usuario existe, se interpreta que se desea modificar ese usuario,
                por ende lleva los datos a la función modifyUsers en el Models/UsuariosModel.php*/
                $data = $this->model->modifyUsers($user, $name, $ape, $email, $telef, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Usuario actualizado', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al actualizar el Usuario', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Editar: Envía a la función editUser del Models/UsuariosModel.php con el id correspondiente*/
    public function edit(int $id)
    {
        $data = $this->model->editUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Eliminar: Envía a la función deleteUser del Models/UsuariosModel.php con el id correspondiente*/
    public function desactivar(int $id)
    {
        if ($_SESSION['id_usuario'] == $id) {
            $msg = array('msg' => 'No puede desactivar su propio Usuario', 'icono' => 'error');
        } else if ($_SESSION['rango'] != "administrador") {
            $msg = array('msg' => 'No tiene el rango necesario para desactivar un Usuario', 'icono' => 'error');
        } else {
            $data = $this->model->desUsuario($id);
            if ($data == 1) {
                $msg = array('msg' => 'Error al desactivar el Usuario', 'icono' => 'error');
            } else {
                $msg = array('msg' => 'Usuario desactivado', 'icono' => 'success');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*Salida: Cierra la sesión si el usuario preciona el botón de salir en el Dashboard*/
    public function logout()
    {
        session_destroy();
        header("location: " . APP_URL);
    }
}
