<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista del Historial de Acciones -->

<section class="main">
    <div class="main-title">
        <h1>Historial de Acciones</h1>
    </div>

    <!-- Tabla Historial -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <button class="primary" type="button" onclick="descargarPDF()" title="Descargar PDF"><i class="fas fa-file-pdf"></i></button>
                    <label for="modulo">Módulo: </label>
                    <select name="modulo" id="modulo" onchange="setfilter()">
                        <option value="todo">Todos</option>
                    </select>
                    <label for="usuario">Usuario: </label>
                    <select name="usuario" id="usuario" onchange="setfilter()">
                        <option value="todo">Todos</option>
                    </select>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
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
                <table id="TablaHistorial">
                    <thead>
                        <tr>
                            <th data-column="usuario" data-order="desc">Usuario</th>
                            <th data-column="modulo" data-order="desc">Módulo</th>
                            <th data-column="accion" data-order="desc">Acción</th>
                            <th data-column="descripcion" data-order="desc">Descripción</th>
                            <th data-column="fecha" data-order="desc">Fecha</th>
                            <th data-column="hora" data-order="desc">Hora</th>
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

<!-- Scripts del módulo -->
<script src="<?php echo APP_URL; ?>assets/js/modulos/historial/script.js"></script>

<?php
include "vista/componentes/footer.php";
?>
