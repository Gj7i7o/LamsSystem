<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Categorías -->

<section class="main">
    <div class="main-top">
        <h1>Categorías</h1>
    </div>

    <!-- Tabla Categorías -->

    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarCategoria" title="Registrar"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
            </div>
            <div>
                <table id="TablaCategorias">
                    <thead>
                        <tr>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="descrip" data-order="desc">Descripción</th>
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
<div id="modalCategoria" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>
        <div class="modal-header">
            <h2 id="title">Registrar Categoría</h2>
        </div>
        <form id="formularioCategoria" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Accesorios" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Accesorios decorativos para vehículos" required>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn-cancel" onclick="document.getElementById('modalMarca').style.display='none'">Cancelar</button> -->

                <button type="submit" id="btnAccion" class="btn-submit">
                    <i class="fas fa-save"></i> Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts de los javaScripts del modal y el módulo -->
<script src="<?php echo APP_URL; ?>assets/js/modulos/categorias/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/categorias/modal_script.js"></script>
<?php
include "vista/componentes/footer.php";
?>