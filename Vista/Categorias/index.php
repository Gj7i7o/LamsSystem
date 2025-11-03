<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Categorías -->

<section class="main">
    <div class="main-top">
        <h1>Categorías</h1>
    </div>

    <!-- Tabla Categorías -->

    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarCategoria" title="Crear categoria"><i class="fas fa-plus"></i></button>
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

<div id="modalCategoria" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>

        <h2 id="title">Registrar Categoría</h2>

        <form id="formularioCategoria" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Accesorios" required>

            <label for="des">Descripción:</label>
            <input type="text" id="des" name="des" placeholder="Accesorios decorativos para vehículos" required>

            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>Assets/js/modulos/Categorias/script.js"></script>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Categorias/modal_script.js"></script>
<?php
include "Vista/Componentes/footer.php";
?>