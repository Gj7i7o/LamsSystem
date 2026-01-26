<?php

/*Controlador del Usuario: Aquí se llaman a los métodos del modelo y validan datos*/

require_once "modelo/HistorialModel.php";

class usuarios extends controlador
{
    private $historialModel;

    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        // Verificar que el usuario sea administrador
        if ($_SESSION['rango'] != 'administrador') {
            header("location: " . APP_URL . "dashboard");
            exit();
        }
        parent::__construct();
        $this->historialModel = new historialModel();
    }

    /*Vista: Trae la vista correspóndiente*/
    public function index()
    {
        $this->vista->getView($this, "index");
    }

    /*listar: Se encarga de colocar los usuarios existentes en la base de datos
    filtrando por estado. Y a su vez coloca en cada uno los botones de modificar y cambiar estado*/
    public function listar()
    {
        try {
            $page = $_GET["page"] ?? 1;
            $estado = $_GET["estado"] ?? "activo";
            $query = $_GET["query"] ?? "";
            $params = ['page' => $page, 'query' => $query, 'estado' => $estado];
            $data = $this->model->tomarUsuarios($params);
            $total = $this->model->getCount($params);
            $idUsuarioActivo = $_SESSION['id_usuario'];
            for ($i = 0; $i < count($data); $i++) {
                $btnEditar = '<button class="primary" type="button" onclick="btnEditUsuario(' . $data[$i]['id'] . ');" title="Modificar"><i class="fa-regular fa-pen-to-square"></i></button>';

                if ($data[$i]['estado'] == 'activo') {
                    // No mostrar botón de desactivar si es el usuario con sesión activa
                    if ($data[$i]['id'] == $idUsuarioActivo) {
                        $data[$i]['acciones'] = '<div>' . $btnEditar . '</div>';
                    } else {
                        $data[$i]['acciones'] = '<div>' . $btnEditar . '
                <button class="warning" type="button" onclick="btnDesUsuario(' . $data[$i]['id'] . ');" title="Desactivar"><i class="fa-solid fa-xmark"></i></button>
                </div>';
                    }
                } else {
                    $data[$i]['acciones'] = '<div>' . $btnEditar . '
                <button class="secure" type="button" onclick="btnActUsuario(' . $data[$i]['id'] . ');" title="Activar"><i class="fa-solid fa-check"></i></button>
                </div>';
                }
            }
            echo json_encode(["data" => $data, "total" => $total], JSON_UNESCAPED_UNICODE);
            die();
        } catch (\Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    /*registrar: Se encarga de validar y registrar los datos de un nuevo usuario en la base de datos*/
    public function registrar()
    {
        $ci = $_POST['ci'];
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
            empty($ci) ||
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
                    $error = false;
                    /*Tras las validaciones, si el usuario no existe, se interpreta como uno nuevo, por ende
                    lleva los datos a la función regisUsuario en el modelo/usuariosModel.php*/
                    $id_usuario = $this->model->regisUsuario($usuario, $hash);
                    if ($id_usuario > 0) {
                        //     $msg = array('msg' => 'Usuario Registrado', 'icono' => 'success');
                        // } else if ($data == "existe") {
                        //     $msg = array('msg' => 'El Usuario ya está registrado', 'icono' => 'warning');
                        // } else {
                        //     $msg = array('msg' => 'Error al registrar el Usuario', 'icono' => 'error');
                        $persona = $this->model->regisPersona($ci, $nombre, $apellido, $correo, $telef, $id_usuario);
                        if ($persona != "ok") {
                            $error = true;
                        }
                    }
                    if (!$error) {
                        $msg = array('msg' => 'Usuario registrado', 'icono' => 'success');
                        $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Usuarios', 'registrar', "Registró usuario: $usuario");
                    } else {
                        $msg = array('msg' => 'Error al registrar el usuario', 'icono' => 'error');
                    }
                }
            } else {
                /*Caso contrario, si el usuario existe, se interpreta que se desea modificar ese usuario,
                por ende lleva los datos a la función modifUsuario en el modelo/usuariosModel.php*/
                $data = $this->model->modifUsuario($usuario, $nombre, $apellido, $correo, $telef, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Usuario actualizado', 'icono' => 'success');
                    $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Usuarios', 'modificar', "Modificó usuario ID: $id - $usuario");
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
                $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Usuarios', 'desactivar', "Desactivó usuario ID: $id");
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
            $this->historialModel->registrarAccion($_SESSION['id_usuario'], 'Usuarios', 'activar', "Activó usuario ID: $id");
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
