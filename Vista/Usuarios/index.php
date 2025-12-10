<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de los Usuarios -->

<section class="main">
    <div class="main-top">
        <h1>Usuarios</h1>
    </div>

    <!-- Tabla Usuarios -->

    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarUsuario" title="Crear Usuario"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
            </div>
            <div>
                <table id="TablaUsuarios">
                    <thead>
                        <tr>
                            <th data-column="usuario" data-order="desc">Usuario</th>
                            <th data-column="nombre" data-order="desc">Nombre</th>
                            <th data-column="apellido" data-order="desc">Apellido</th>
                            <th data-column="correo" data-order="desc">Correo</th>
                            <th data-column="telef" data-order="desc">teléfono</th>
                            <th data-column="rango" data-order="desc">Rango</th>
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

<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>
        <div class="modal-header">
            <h2 id="title">Registrar Usuario</h2>
        </div>
        <form id="formularioUsuario" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" placeholder="UserMax123" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Pedro" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" placeholder="López" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" placeholder="correoreal@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="telef">Teléfono:</label>
                <input type="text" id="telef" name="telef" placeholder="0414-1234567" required>
            </div>
            <div id="passwords">
                <div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Pedro*15">
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="confirm">Confirmar Contraseña:</label>
                        <input type="password" id="confirm" name="confirm" placeholder="Pedro*15">
                    </div>
                </div>
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
<script src="<?php echo APP_URL; ?>assets/js/modulos/usuarios/script.js"></script>
<script src="<?php echo APP_URL; ?>assets/js/modulos/usuarios/modal_script.js"></script>
<?php
include "vista/componentes/footer.php";
?>