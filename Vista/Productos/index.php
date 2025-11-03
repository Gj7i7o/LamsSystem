<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de los Productos -->

<section class="main">
    <div class="main-top">
        <h1>Productos</h1>
    </div>

    <!-- Tabla Productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="filtros">
                <button class="button" type="button" id="registrarProducto" title="Crear Producto"><i class="fas fa-plus"></i></button>
                <button type="button" class="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <select id="estado" name="estado">
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                </select>
            </div>
            <div>
                <table id="TablaProductos">
                    <thead>
                        <tr>
                            <th data-column="codigo" data-order="desc">Código</th>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="precio" data-order="desc">Precio $</th>
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

<div id="modalProducto" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>

        <h2 id="title">Registrar Producto</h2>

        <form id="formularioProducto" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo" placeholder="001-22-777" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Bujía" required>

            <label for="precio">Precio $:</label>
            <input type="text" id="precio" name="precio" placeholder="15" required>

            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria">
            </select>

            <label for="marca">Marca:</label>
            <select name="marca" id="marca">
            </select>
            <div style="margin-top: 15px">
                <button type="submit" id="btnAccion">Registrar</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>Assets/js/modulos/Productos/script.js"></script>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Productos/modal_script.js"></script>
<?php
include "Vista/Componentes/footer.php";
?>