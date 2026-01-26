<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-title">
        <h1>Historial de Salidas</h1>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarSalida" title="Crear nueva salida"><i class="fas fa-plus"></i></button>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
                </div>
            </div>
            <div>
                <table id="tablaSalidas">
                    <thead>
                        <tr>
                            <th data-column="cod_docum" data-order="desc">Documento</th>
                            <th data-column="tipo_despacho" data-order="desc">Tipo Despacho</th>
                            <th data-column="total" data-order="desc">Precio Total</th>
                            <th data-column="fecha" data-order="desc">Fecha</th>
                            <th data-column="hora" data-order="desc">Hora</th>
                            <th data-column="acciones" data-order="desc">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="pagination">
                    <button id="prevBtn">Anterior</button>
                    <span id="pageInfo"></span>
                    <button id="nextBtn">Siguiente</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="modalSalida" class="modal">
        <div class="modal-content">
            <span class="close" title="Cerrar">&times;</span>

            <div class="modal-header">
                <h2 id="title">Registrar Salida</h2>
            </div>

            <div class="modal-header-info">
                <div class="datos-proveedor">
                    <h3>Tipo de Despacho:<span class="required">*</span></h3>
                    <select name="tipo_despacho" id="tipo_despacho">
                        <option value="venta" selected>Venta</option>
                        <option value="uso_interno">Uso Interno</option>
                        <option value="danado">Dañado</option>
                        <option value="devolucion">Devolución</option>
                    </select>
                </div>
                <div class="fecha">
                    <h3>Fecha: <span id="fecha"></span></h3>
<<<<<<< HEAD
                    <input type="text" id="codigo" name="codigo" placeholder="Código Documento" required>
=======
                    <h3>Documento:<span class="required">*</span></h3>
                    <input type="text" id="codigo" name="codigo" placeholder="Número de Documento" maxlength="50" required>
>>>>>>> b8dfd1bcd42a5e5726a07bf966931d705379ad81
                </div>
            </div>

            <form id="formularioSalidas" class="form" method="POST">
                <input type="number" id="id" name="id" hidden="true">
                <div id="formularioSalida">
                    <div class="buttonToLine">
                        <button class="button" type="button" data-idx="0" id="deleteLine"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="modal-footer-info">
                    <div class="datos-proveedor">
                        <h3>Total: </h3>
                    </div>
                    <div class="total" title="Precio total">
                        <input type="number" step="0.01" min="0.00" id="total" value="0.00" name="total" disabled onchange="getTotal()">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="button" type="button" id="addLine" title="Añadir línea de salida"><i class="fas fa-plus"></i></button>
                    <button type="submit" id="btnAccion" class="btn-submit">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Modal Ver Detalle -->
    <div id="modalDetalleSalida" class="modal">
        <div class="modal-content">
            <span class="close-detalle" title="Cerrar">&times;</span>
            <div class="modal-header">
                <h2>Detalle de Salida</h2>
            </div>
            <div class="modal-header-info">
                <div class="datos-proveedor">
                    <h3>Usuario: <span id="detalle_usuario"></span></h3>
                </div>
                <div class="datos-proveedor">
                    <h3>Tipo Despacho: <span id="detalle_tipo_despacho"></span></h3>
                </div>
                <div class="fecha">
                    <h3>Documento: <span id="detalle_codigo"></span></h3>
                    <h3>Fecha: <span id="detalle_fecha"></span> - <span id="detalle_hora"></span></h3>
                </div>
            </div>
            <div class="detalle-productos">
                <table id="tablaDetalleSalida">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detalleSalidaBody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer-info">
                <div class="datos-proveedor">
                    <h3>Total: <span id="detalle_total"></span></h3>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>assets/js/modulos/salidas/script.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/modulos/salidas/modal_script.js"></script>
    <script>
        const idusuario = '<?php echo $_SESSION['id_usuario']; ?>';
    </script>

    <script>
        const elementoFecha = document.getElementById('fecha');
        const hoy = new Date();
        elementoFecha.textContent = hoy.toLocaleDateString();
    </script>
    <?php
    include "vista/componentes/footer.php";
    ?>