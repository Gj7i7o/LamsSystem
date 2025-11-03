<?php
include "Vista/Componentes/header.php";
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

        <h2 id="title">Registrar Usuario</h2>

        <form id="formularioUsuario" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="UserMax123" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Pedro" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" placeholder="López" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" placeholder="correoreal@gmail.com" required>

            <label for="telef">Teléfono:</label>
            <input type="text" id="telef" name="telef" placeholder="0414-1234567" required>

            <div id="passwords">
                <div>
                    <div>
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Pedro*15">
                    </div>
                </div>
                <div>
                    <div>
                        <label for="confirm">Confirmar Contraseña:</label>
                        <input type="password" id="confirm" name="confirm" placeholder="Pedro*15">
                    </div>
                </div>
            </div>
            <button type="submit" id="btnAccion">Registrar</button>
        </form>
    </div>
</div>

<script src="<?php echo APP_URL; ?>Assets/js/modulos/Usuarios/script.js"></script>
<script src="<?php echo APP_URL; ?>Assets/js/modulos/Usuarios/modal_script.js"></script>
<?php
include "Vista/Componentes/footer.php";
?>