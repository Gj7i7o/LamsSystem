<?php
include "Vista/Componentes/header.php";
?>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Productos/script.js"></script>

<!-- Index.php encargado de la vista de los Productos -->

<section class="main">
    <div class="main-top">
        <h1>Productos</h1>
    </div>
    <div class="main-top-text">
        <p>Productos y opciones disponibles:</p>
    </div>

    <!-- Tabla Productos -->

    <section class="main-course">
        <button class="button" type="button" onclick="frmProduct();" title="Crear Producto"><i class="fas fa-plus"></i></button>
        <div class="course-box">
            <div class="recent-orders tabla">
                <table id="TablaProductos">
                    <thead>
                        <tr>
                            <th data-column="codigo" data-order="desc">Código</th>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="precio" data-order="desc">Precio</th>
                            <th data-column="cantidad" data-order="desc">Cantidad</th>
                            <th data-column="categoria" data-order="desc">Categoría</th>
                            <th data-column="marca" data-order="desc">Marca</th>
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

        <h2>Registrar Producto</h2>

        <form id="" class="form">
            <input type="number" id="id" hidden="true">
            <label for="codigo">Código:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" required>

            <label for="categoria">Categoía:</label>
            <input type="email" id="categoria" name="categoria" required>

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" required>

            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<?php
include "Vista/Componentes/footer.php";
?>