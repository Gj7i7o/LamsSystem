<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Marcas -->

<section class="main">
    <div class="main-title">
        <h1>Marcas</h1>
    </div>

    <!-- Tabla Marcas -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarMarca" title="Registrar"><i class="fas fa-plus"></i></button>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="" id="">
                </div>
            </div>
            <div>
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

<!-- Modal -->
<div id="modalMarca" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>
        <div class="modal-header">
            <h2 id="title">Registrar Marca</h2>
        </div>

        <form id="formularioMarca" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ford" title="Nombre de la marca" required>
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
<script src="<?php echo APP_URL; ?>assets/js/modulos/marcas/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/marcas/modal_script.js"></script>
<?php
include "vista/componentes/footer.php";
?>