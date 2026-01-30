<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de Proveedores -->

<section class="main">
    <div class="main-title">
        <h1>Proveedores</h1>
    </div>

    <!-- Tabla de Proveedores -->
    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <button class="button" type="button" id="registrarProveedor" title="Registrar"><i class="fas fa-plus"></i></button>
                    <button class="primary" type="button" onclick="descargarPDF()" title="Descargar PDF"><i class="fas fa-file-pdf"></i></button>
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
                <table id="TablaProveedores">
                    <thead>
                        <tr>
                            <th data-column="rif" data-order="desc">Rif</th>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="telefono" data-order="desc">Teléfono</th>
                            <th data-column="persona_contacto" data-order="desc">Persona Contacto</th>
                            <th data-column="direccion" data-order="desc">Dirección</th>
                            <th data-column="estado" data-order="desc">Estado</th>
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
</section>

<div id="modalProveedor" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>
        <div class="modal-header">
            <h2 id="title">Registrar Proveedor</h2>
        </div>
        <form id="formularioProveedor" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-file">
                <div class="form-group">
                    <label for="rif">Rif/CI:<span class="required">*</span></label>
                    <input type="text" id="rif" name="rif" placeholder="Ej: J-123456789" maxlength="11">
                </div>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:<span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Empresa o Nombre Completo" maxlength="50">
            </div>

            <div class="form-file">
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ej: 0412-1234567" maxlength="12">
                </div>
                <div class="form-group">
                    <label for="persona_contacto">Persona de Contacto:</label>
                    <input type="text" id="persona_contacto" name="persona_contacto" placeholder="Ej: Juan Pérez" maxlength="40">
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:<span class="required">*</span></label>
                <input type="text" id="direccion" name="direccion" style="padding-bottom: 60px;" placeholder="Ej: Calle Acosta casa Nº..." maxlength="255">
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnAccion" class="btn-submit">
                    <i class="fas fa-save"></i> Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts de los javaScripts del modal y el módulo -->
<script src="<?php echo APP_URL; ?>assets/js/modulos/proveedores/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/proveedores/modal_script.js"></script>
<?php
include "vista/componentes/footer.php";
?>