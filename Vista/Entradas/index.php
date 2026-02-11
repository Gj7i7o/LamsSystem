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
                    <label for="estado">Estado: </label>
                    <select name="estado" id="estado" onchange="setfilter()">
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="todo">Todos</option>
                    </select>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
                </div>
            </div>
            <div class="filtro-fechas">
                <label for="fecha_desde">Desde:</label>
                <input type="date" id="fecha_desde" name="fecha_desde" onchange="setfilter()">
                <label for="fecha_hasta">Hasta:</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" onchange="setfilter()">
                <button class="btn-limpiar-fechas" type="button" onclick="limpiarFechas()">Limpiar fechas</button>
            </div>
            <div>
                <table id="tablaEntradas">
                    <thead>
                        <tr>
                            <th data-column="cod_docum" data-order="desc">Documento</th>
                            <th data-column="proveedor" data-order="desc">Proveedor</th>
                            <th data-column="tipo_pago" data-order="desc">Condición</th>
                            <th data-column="total" data-order="desc" style="text-align: center;">Precio Total</th>
                            <th data-column="fecha" data-order="desc">Fecha</th>
                            <th data-column="hora" data-order="desc">Hora</th>
                            <th data-column="acciones" data-order="desc" style="text-align: center;">Acciones</th>
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
        <div class="modal-content" style="width: 1300px;">
            <span class="close" title="Cerrar">&times;</span>

            <div class="modal-header">
                <h2 id="title">Registrar Entrada</h2>
            </div>

            <div class="modal-header-info">
                <div class="datos-proveedor">
                    <h3>Proveedor:<span class="required">*</span></h3>
                    <div class="autocomplete-container" style="position: relative; display: inline-block;">
                        <input type="text" id="proveedor_rif" style="width: 150px;" placeholder="Escriba RIF..." autocomplete="off">
                        <div id="lista_busqueda_proveedor" class="autocomplete-items"></div>
                    </div>
                    <input type="hidden" id="proveedor_id" name="proveedor_id">
                    <br>
                    <input type="text" id="proveedor_nombre" style="width: 200px; margin-top: 5px;" placeholder="Nombre del proveedor" disabled>
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
                    <input type="text" id="codigo" name="codigo" placeholder="Código Documento" maxlength="15" autocomplete="off">
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
                <p class="ejemplo">*: Campos obligatorios</p>
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
        <div class="modal-content" style="width: 1300px;">
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
                            <th>P. Costo</th>
                            <th>P. Venta</th>
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

    <!-- Modal Crear Producto desde Entradas -->
    <div id="modalNuevoProducto" class="modal">
        <div class="modal-content">
            <span class="close-nuevo-producto" title="Cerrar">&times;</span>
            <div class="modal-header">
                <h2>Registrar Producto</h2>
            </div>
            <form id="formularioNuevoProducto" class="form" method="POST">
                <input type="hidden" name="id" value="">
                <div class="form-file">
                    <div class="form-group">
                        <label for="np_codigo">Código:<span class="required">*</span></label>
                        <input type="text" id="np_codigo" name="codigo" placeholder="Ej: 001-22-777" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label for="np_nombre">Nombre:<span class="required">*</span></label>
                        <input type="text" id="np_nombre" name="nombre" placeholder="Ej: Bujía" maxlength="30">
                    </div>
                </div>

                <div class="form-file">
                    <div class="form-group">
                        <label for="np_precioVenta">Precio Venta $:<span class="required">*</span></label>
                        <input type="number" id="np_precioVenta" name="precioVenta" placeholder="Ej: 15" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="np_precioCosto">Precio Costo $:</label>
                        <input type="number" id="np_precioCosto" name="precioCosto" placeholder="Ej: 10" step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="np_cantidad">Cantidad:</label>
                        <input type="number" id="np_cantidad" name="cantidad" value="0" min="0" step="1">
                    </div>
                    <div class="form-group">
                        <label for="np_cantidadMinima">Stock Mínimo:<span class="required">*</span></label>
                        <input type="number" id="np_cantidadMinima" name="cantidadMinima" value="1" min="1" step="1">
                    </div>
                </div>

                <div class="form-file">
                    <div class="form-group">
                        <label for="np_categoria">Categoría:<span class="required">*</span></label>
                        <select name="categoria" id="np_categoria">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="np_marca">Marca:<span class="required">*</span></label>
                        <select name="marca" id="np_marca">
                        </select>
                    </div>
                </div>
                <p class="ejemplo">*: Campos obligatorios</p>
                <div class="modal-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>
            </form>
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