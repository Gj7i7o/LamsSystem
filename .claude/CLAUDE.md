# PATRONES DE CÓDIGO - LAMSSYSTEM

Este documento describe los patrones y convenciones de código utilizados en el proyecto LamsSystem para mantener consistencia en futuras modificaciones.

## Arquitectura

- **Patrón MVC clásico**: Controlador → Modelo → Vista
- **Estructura de carpetas**:
  ```
  LamsSystem/
  ├── Config/           # Configuración y clases base
  │   ├── Config.php    # Constantes (DB, APP_URL)
  │   └── App/          # Clases: Conexion, Query, Controlador, Vista
  ├── Controlador/      # Controladores del sistema
  ├── Modelo/           # Modelos (consultas SQL)
  ├── Vista/            # Vistas PHP + componentes
  │   └── Componentes/  # header.php, footer.php
  ├── Assets/
  │   ├── css/
  │   ├── js/
  │   │   └── modulos/  # JS por módulo (script.js, modal_script.js)
  │   └── img/
  └── DB/               # Dump de base de datos
  ```

## Naming Conventions

| Elemento | Convención | Ejemplo |
|----------|------------|---------|
| Clases base | lowercase | `conexion`, `query`, `controlador`, `vista` |
| Controladores | PascalCase (archivo), lowercase (clase) | `Productos.php` → `class productos` |
| Modelos | PascalCase + "Model" | `ProductosModel.php` → `class productosModel` |
| Variables | camelCase | `$nombreVariable`, `$idCategoria` |
| Funciones/Métodos | camelCase | `regisProducto()`, `tomarProductos()` |
| Constantes | UPPERCASE con underscore | `APP_URL`, `DB_NAME`, `DB_SERVER` |
| Propiedades privadas | camelCase | `private $codigo`, `private $nombre` |

## Indentación y Formato

- **PHP**: 4 espacios
- **JavaScript**: 4 espacios
- **CSS**: 2 espacios
- **Llaves**: En la misma línea (estilo K&R)
  ```php
  if ($condicion) {
      // código
  }
  ```

## Base de Datos

### Conexión
- Usa **PDO** con `PDO::ERRMODE_EXCEPTION`
- Configuración en `Config/Config.php`

### Clase Query (métodos disponibles)
```php
$this->select($sql);      // Retorna un registro (fetch)
$this->selectAll($sql);   // Retorna múltiples registros (fetchAll)
$this->save($sql, $datos); // INSERT/UPDATE con prepared statements
$this->insertar($sql, $datos); // INSERT que retorna lastInsertId()
```

### Patrón de consultas
```php
// SELECT con JOIN
$sql = "SELECT p.id, p.nombre, c.nombre AS categoria
        FROM producto p
        LEFT JOIN categoria c ON p.idcategoria = c.id";

// INSERT con prepared statements
$sql = "INSERT INTO producto (codigo, nombre, precio) VALUES (?,?,?)";
$datos = array($this->codigo, $this->nombre, $this->precio);
$data = $this->save($sql, $datos);
```

### Estados
- Usar strings: `'activo'` / `'inactivo'`
- Métodos separados: `desProducto($id)`, `actProducto($id)`

## Controladores

### Estructura base
```php
<?php
/*Controlador del Módulo: Descripción breve*/

class nombremodulo extends controlador {
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . APP_URL);
        }
        parent::__construct();
    }

    public function index() {
        $this->vista->getView($this, "index");
    }

    public function registrar() {
        // Validaciones y lógica
        $msg = array('msg' => 'Mensaje', 'icono' => 'success');
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
```

### Respuestas JSON
```php
// Éxito
$msg = array('msg' => 'Registro exitoso', 'icono' => 'success');

// Advertencia
$msg = array('msg' => 'El registro ya existe', 'icono' => 'warning');

// Error
$msg = array('msg' => 'Error al procesar', 'icono' => 'error');

echo json_encode($msg, JSON_UNESCAPED_UNICODE);
die();
```

## Modelos

### Estructura base
```php
<?php
/*Modelo del Módulo: Aquí se encuentran las consultas SQL*/

class nombremoduloModel extends query {
    private $id, $nombre, $estado;

    public function regisElemento(string $nombre) {
        $this->nombre = $nombre;

        // Verificar si existe
        $verificar = "SELECT * FROM tabla WHERE nombre = '$this->nombre'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "INSERT INTO tabla (nombre, estado) VALUES (?,'activo')";
            $datos = array($this->nombre);
            $data = $this->save($sql, $datos);
            $res = ($data == 1) ? "ok" : "error";
        } else {
            $res = "existe";
        }
        return $res;
    }
}
```

## Vistas

### Estructura de archivo
```php
<?php include "vista/componentes/header.php"; ?>

<section class="main">
    <div class="main-title">
        <h1>Título del Módulo</h1>
    </div>

    <section class="main-course">
        <div class="course-box">
            <!-- Contenido: botones, tablas, etc. -->
        </div>
    </section>
</section>

<!-- Modal -->
<div id="modalNombre" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="title">Título</h2>
            <span class="close">&times;</span>
        </div>
        <form id="formularioNombre" class="form" method="POST">
            <!-- Campos del formulario -->
            <div class="modal-footer">
                <button type="submit" id="btnAccion" class="btn-submit">Registrar</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>assets/js/modulos/nombremodulo/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/nombremodulo/modal_script.js"></script>

<?php include "vista/componentes/footer.php"; ?>
```

## JavaScript

### Peticiones AJAX (XMLHttpRequest)
```javascript
const url = APP_URL + "modulo/metodo";
const http = new XMLHttpRequest();
http.open("POST", url, true);
http.send(new FormData(formulario));
http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        alertas(res.msg, res.icono);
        if (res.icono == "success") {
            modal.style.display = "none";
            recargarVista();
        }
    }
};
```

### Peticiones GET
```javascript
function btnEditElemento(id) {
    const url = APP_URL + "modulo/editar/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            // Rellenar campos del modal
            modal.style.display = "block";
        }
    };
}
```

### Funciones globales (app.js)
```javascript
// Mostrar alertas con SweetAlert2
alertas(mensaje, icono);  // icono: 'success', 'error', 'warning', 'info'

// Recargar la vista
recargarVista(tiempo);    // tiempo en ms, default 1000
```

### Control de modales
```javascript
const modal = document.getElementById("modalNombre");
const btn = document.getElementById("btnAbrir");
const span = document.getElementsByClassName("close")[0];

btn.onclick = function () {
    modal.style.display = "block";
};

span.onclick = function () {
    modal.style.display = "none";
};
```

### Validación con Regex
```javascript
// Letras con acentos
const letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;

// Números decimales (precios)
const numeros = /^\d+(\.\d{1,2})?$/;

// Email
const email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

// Teléfono (formato venezolano)
const phone = /^04(12|14|16|24|26)-\d{7}$/;
```

## CSS

### Clases principales
```css
/* Contenedor principal */
.main { margin-left: 220px; padding: 20px; }

/* Títulos */
.main-title h1 { font-size: 25px; }

/* Botones */
.button { background: rgb(22, 126, 72); padding: 7px 15px; border-radius: 10px; }
.primary { background: rgb(103, 103, 255); }
.warning { background: rgb(238, 98, 98); }
.secure { background: rgb(21, 139, 44); }

/* Tablas */
table { width: 100%; border-collapse: collapse; }
th { background-color: #f2f2f2; }

/* Modales */
.modal { display: none; position: fixed; z-index: 1; }
.modal-content { background-color: #fefefe; padding: 20px; }
```

### Colores del proyecto
- Navbar: `#314158` (azul oscuro)
- Activo: `#155DFC` (azul brillante)
- Primario: `#16a64d` (verde)
- Warning: `#ee6262` (rojo)
- Fondo: `#f0f0f0` (gris claro)

## Comentarios

### PHP
```php
/*Controlador del Producto: Aquí se llaman a los métodos del modelo*/

/*tomarProductos: Toma todos los productos con paginación*/
public function tomarProductos() { }
```

### JavaScript
```javascript
/*
Script de Producto: Funciones del módulo de producto
*/

// Función para obtener datos del servidor
async function fetchData() { }
```

### HTML
```html
<!-- Tabla Productos -->
<!-- Modal -->
<!-- Scripts del módulo -->
```

## Librerías Utilizadas

- **jQuery** (jquery.min.js)
- **SweetAlert2** (sweetalert2.all.min.js) - Alertas
- **FontAwesome** (all.min.css) - Iconos (`fa-plus`, `fa-pen-to-square`, `fa-xmark`)

## Flujo de Datos Típico

1. Usuario interactúa → JavaScript captura evento
2. Validación client-side con regex
3. AJAX envía datos al controlador
4. Controlador valida server-side
5. Modelo ejecuta consulta SQL
6. Controlador retorna JSON
7. JavaScript muestra alerta y actualiza vista

## Métodos Estándar por Módulo

Cada módulo CRUD tiene estos métodos en su controlador:

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `index()` | Carga la vista principal | Vista |
| `listar($page)` | Lista registros activos con paginación | JSON |
| `listarInactivos($page)` | Lista registros inactivos | JSON |
| `registrar()` | Crea/modifica registro | JSON |
| `editar($id)` | Obtiene datos para edición | JSON |
| `desactivar($id)` | Cambia estado a inactivo | JSON |
| `activar($id)` | Cambia estado a activo | JSON |
