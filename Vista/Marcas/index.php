<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Marcas -->

<section class="main">
    <div class="main-top">
        <!-- <i class="fas fa-copyright"></i> -->
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

<?php
include "Vista/Componentes/footer.php";
?>