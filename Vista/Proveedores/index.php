<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de Proveedores -->

<section class="main">
    <div class="main-top">
        <h1>Proveedores</h1>
    </div>

    <!-- Tabla de Proveedores -->
    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarProveedor" title="Crear Producto"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
            </div>
            <div>
                <table id="TablaProveedores">
                    <thead>
                        <tr>
                            <th data-column="rif" data-order="desc">Rif</th>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="apellido" data-order="desc">Apellido</th>
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

        <h2 id="title">Registrar Proveedor</h2>

        <form id="formularioProveedor" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <label for="rif">Rif/CI:</label>
            <input type="text" id="rif" name="rif" placeholder="J-123456789" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Juan" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" placeholder="Farias" required>

            <label for="dir">Dirección:</label>
            <input type="text" id="dir" name="dir" placeholder="Calle Acosta casa Nº..." required>

            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>Assets/js/modulos/Proveedores/script.js"></script>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Proveedores/modal_script.js"></script>
<?php
include "Vista/Componentes/footer.php";
?>