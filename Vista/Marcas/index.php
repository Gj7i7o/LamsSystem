<?php
include "Vista/Componentes/header.php";
?>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Marcas/script.js"></script>

<!-- Index.php encargado de la vista de las Marcas -->

<section class="main">
    <div class="main-top">
        <h1>Marcas</h1>
    </div>
    <div class="main-top-text">
        <p>Marcas y opciones disponibles:</p>
    </div>

    <!-- Tabla Marcas -->

    <section class="main-course">
        <button class="button" type="button" onclick="frmMarca();" title="Crear categoria"><i class="fas fa-plus"></i></button>
        <div class="course-box">
            <div class="recent-orders tabla">
                <table id="TablaMarcas">
                    <thead>
                        <tr>
                            <th data-column="nombre" data-order="desc">Nombre</th>
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

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>

        <h2>Registrar Marca</h2>

        <form id="" class="form">
            <input type="number" id="id" hidden="true">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<?php
include "Vista/Componentes/footer.php";
?>