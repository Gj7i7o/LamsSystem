<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-title">
        <h1>Historial de Entradas</h1>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarEntrada" title="Registrar nueva entrada"><i class="fas fa-plus"></i></button>
                    <button class="primary" type="button" onclick="descargarPDF();" title="Generar Reporte PDF"><i class="fas fa-file-pdf"></i></button>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
                </div>
            </div>
            <div>
                <table id="tablaEntradas">
                    <thead>
                        <tr>
                            <th data-column="cod_docum" data-order="desc">Documento</th>
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
    <div id="modalEntrada" class="modal">
        <div class="modal-content" style="width: 900px;">
            <span class="close" title="Cerrar">&times;</span>

            <div class="modal-header">
                <h2 id="title">Registrar Entrada</h2>
            </div>

            <div class="modal-header-info">
                <div class="datos-proveedor">
                    <h3>Proveedor:<span class="required">*</span></h3>
                    <select name="proveedor" id="proveedor"></select>
                </div>
                <div class="datos-proveedor">
                    <h3>Condición:<span class="required">*</span></h3>
                    <select name="tipo_pago" id="tipo_pago">
                        <option value="contado" selected>Contado</option>
                        <option value="credito">Crédito</option>
                    </select>
                </div>
                <div class="fecha">
                    <h3>Fecha: <span id="fecha"></span></h3>
                    <h3>Documento:<span class="required">*</span></h3>
                    <input type="text" id="codigo" name="codigo" placeholder="Código Documento" maxlength="50">
                </div>
            </div>

            <form id="formularioEntradas" class="form" method="POST">
                <input type="number" id="id" name="id" hidden="true">
                <div id="formularioEntrada">
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
                    <button class="button" type="button" id="addLine" title="Añadir línea de entrada"><i class="fas fa-plus"></i></button>
                    <button type="submit" id="btnAccion" class="btn-submit">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Modal Ver Detalle -->
    <div id="modalDetalleEntrada" class="modal">
        <div class="modal-content" style="width: 900px;">
            <span class="close-detalle" title="Cerrar">&times;</span>
            <div class="modal-header">
                <h2>Detalle de Entrada</h2>
            </div>
            <div class="modal-header-info">
                <div class="datos-proveedor">
                    <h3>Proveedor: <span id="detalle_proveedor"></span></h3>
                    <h3>Usuario: <span id="detalle_usuario"></span></h3>
                </div>
                <div class="datos-proveedor">
                    <h3>Tipo de Pago: <span id="detalle_tipo_pago"></span></h3>
                </div>
                <div class="fecha">
                    <h3>Documento: <span id="detalle_codigo"></span></h3>
                    <h3>Fecha: <span id="detalle_fecha"></span> - <span id="detalle_hora"></span></h3>
                </div>
            </div>
            <div class="detalle-productos">
                <table id="tablaDetalleEntrada">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detalleEntradaBody">
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

    <script src="<?php echo APP_URL; ?>assets/js/modulos/entradas/script.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/modulos/entradas/modal_script.js"></script>
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