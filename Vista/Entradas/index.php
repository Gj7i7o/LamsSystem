<?php
include "Vista/Componentes/header.php";
?>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Entradas/script.js"></script>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-top">
        <h1>Entradas disponibles</h1>
    </div>
    <div class="main-top-text">
        <p>Entradas y opciones disponibles:</p>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="recent-orders tabla">
                <table id="tablaEntradas">
                    <thead>
                        <tr>
                            <th data-column="nombre" data-order="desc">Producto</th>
                            <th data-column="cantidad" data-order="desc">Cantidad</th>
                            <th data-column="precio" data-order="desc">Precio</th>
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

    <div class="main-top">
        <h1>Historial de Entradas</h1>
    </div>

    <!-- Tabla de historial de entradas -->

    <section class="main-course">
        <div class="course-box">
            <div class="recent-orders tabla">
                <table id="tablaHisEntradas">
                    <thead>
                        <tr>
                            <th data-column="producto" data-order="desc">Producto</th>
                            <th data-column="cantidad" data-order="desc">Cantidad</th>
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

    <?php
    include "Vista/Componentes/footer.php";
    ?>