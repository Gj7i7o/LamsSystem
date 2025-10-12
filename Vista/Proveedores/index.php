<?php
include "Vista/Componentes/header.php";
?>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Proveedores/script.js"></script>

<!-- Index.php encargado de la vista de Proveedores -->

<section class="main">
    <div class="main-top">
        <h1>Proveedores</h1>
    </div>
    <div class="main-top-text">
        <p>Proveedores y opciones disponibles:</p>
    </div>

    <!-- Tabla de Proveedores -->
    <input type="hidden" id="abrirModalBtn">
    <span type="hidden" class="close"></span>
    <section class="main-course">
        <button class="button" type="button" onclick="frmProveedor();" title="Crear Producto"><i class="fas fa-plus"></i></button>
        <div class="course-box">
            <div class="recent-orders tabla">
                <table id="TablaProveedores">
                    <thead>
                        <tr>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="apellido" data-order="desc">Apellido</th>
                            <th data-column="rif" data-order="desc">Rif</th>
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

<?php
include "Vista/Componentes/footer.php";
?>