<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Marcas -->

<section class="main">
    <div class="main-top">
        <h1>Marcas</h1>
    </div>

    <!-- Tabla Marcas -->

    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarMarca" title="Crear categoria"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
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

<div id="modalMarca" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>

        <h2 id="title">Registrar Marca</h2>

        <form id="formularioMarca" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ford" required>

            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>Assets/js/modulos/Marcas/script.js"></script>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Marcas/modal_script.js"></script>
<?php
include "Vista/Componentes/footer.php";
?>