<?php

/*Controlador del Usuario: Aquí se llaman a los métodos del modelo y validan datos*/

class usuarios extends controlador
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

    /*listarInactivos: Se encarga de colocar los usuarios existentes en la base de datos 
    en base a su estado inactivo. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listarInactivos()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->tomarUsuariosIn($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditUsuario(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="secure" type="button" onclick="btnActUsuario(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*listarActivos: Se encarga de colocar los usuarios existentes en la base de datos 
    en base a su estado activo. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listarActivos()
    {
        try {
            $page = $_GET["page"] ?? 0;
            $data = $this->model->tomarUsuariosAc($page);
            $total = $this->model->getCount();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['acciones'] = '<div>
            <button class="primary" type="button" onclick="btnEditUsuario(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="warning" type="button" onclick="btnDesUsuario(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
            </div>';
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*validar: Comprueba si el usuario y contraseña ingresados corresponden a algún usuario 
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
            $data = $this->model->tomarUsuario($usuario, $hash);
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

    /*registrar: Se encarga de validar y registrar los datos de un nuevo usuario en la base de datos*/
    public function registrar()
    {
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telef = $_POST['telef'];
        $contrasena = $_POST['contrasena'];
        $id = $_POST['id'];
        $hash = hash("SHA256", $contrasena);
        /*Patrones de validación*/
        $letras = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/";
        $pass = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/";
        $email = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $phone = "/^04(12|14|16|24|26)-\d{7}$/";
        if (
            empty($usuario) ||
            empty($nombre) ||
            empty($apellido) ||
            empty($correo) ||
            empty($telef)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!preg_match($letras, $nombre)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en su nombre', 'icono' => 'warning');
                } else if (!preg_match($letras, $apellido)) {
                    $msg = array('msg' => 'No agregue caracteres indevidos en su apellido', 'icono' => 'warning');
                } else if (!preg_match($pass, $contrasena)) {
                    $msg = array('msg' => 'La contraseña NO cumple con las especificaciones', 'icono' => 'warning');
                } else if (!preg_match($email, $correo)) {
                    $msg = array('msg' => 'Escriba correctamente el correo', 'icono' => 'warning');
                } else if (!preg_match($phone, $telef)) {
                    $msg = array('msg' => 'Escriba correctamente el teléfono', 'icono' => 'warning');
                } else {
                    /*Tras las validaciones, si el usuario no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisUsuario en el modelo/usuariosModel.php*/
                    $data = $this->model->regisUsuario($usuario, $nombre, $apellido, $correo, $telef, $hash);
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
                por ende lleva los datos a la función modifUsuario en el modelo/usuariosModel.php*/
                $data = $this->model->modifUsuario($usuario, $nombre, $apellido, $correo, $telef, $id);
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

    /*editar: Envía a la función editarUsuario del modelo/usuariosModel.php con el id correspondiente*/
    public function editar(int $id)
    {
        $data = $this->model->editarUsuario($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*desactivar: Envía a la función desUsuario del modelo/usuariosModel.php con el id correspondiente
    además compara si ese id es igual al id del usuario activo, para evitar borrar su propio usuario*/
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

    /*activar: Envía a la función actUsuario del modelo/usuariosModel.php con el id correspondiente*/
    public function activar(int $id)
    {
        $data = $this->model->actUsuario($id);
        if ($data == 1) {
            $msg = array('msg' => 'Error al activar el Usuario', 'icono' => 'error');
        } else {
            $msg = array('msg' => 'Usuario activado', 'icono' => 'success');
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*logout: Cierra la sesión si el usuario preciona el botón de salir en el Dashboard*/
    public function logout()
    {
        session_destroy();
        header("location: " . APP_URL);
    }
}
