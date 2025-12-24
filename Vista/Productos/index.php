<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de los Productos -->

<section class="main">
    <div class="main-top">
        <h1>Productos</h1>
    </div>

    <!-- Tabla Productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="opciones">
                <div class="filtros">
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarProducto" title="Registrar"><i class="fas fa-plus"></i></button>
                    <!-- <button type="button" class="button"><i class="fa-solid fa-magnifying-glass"></i></button> -->
                    <select id="estado" name="estado">
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="" id="">
                </div>
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
        <div class="modal-header">
            <h2 id="title">Registrar Producto</h2>
        </div>
        <form id="formularioProducto" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-group">
                <label for="codigo">Código:</label>
                <input type="text" id="codigo" name="codigo" placeholder="001-22-777" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Bujía" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio $:</label>
                <input type="text" id="precio" name="precio" placeholder="15" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria">
                </select>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <select name="marca" id="marca">
                </select>
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
<script src="<?php echo APP_URL; ?>assets/js/modulos/productos/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/productos/modal_script.js"></script>
<?php
include "vista/componentes/footer.php";
?>