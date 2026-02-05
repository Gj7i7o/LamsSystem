<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de los Usuarios -->

<section class="main">
    <div class="main-title">
        <h1>Usuarios</h1>
    </div>

    <!-- Tabla Usuarios -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarUsuario" title="Registrar"><i class="fas fa-plus"></i></button>
                    <button class="primary" type="button" onclick="descargarPDF()" title="Descargar PDF"><i class="fas fa-file-pdf"></i></button>
                    <label for="estado">Estado: </label>
                    <select name="estado" id="estado" onchange="setfilter()">
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="todo">Todos</option>
                    </select>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
                </div>
            </div>
            <div class="filtro-fechas">
                <label for="fecha_desde">Desde:</label>
                <input type="date" id="fecha_desde" name="fecha_desde" onchange="setfilter()">
                <label for="fecha_hasta">Hasta:</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" onchange="setfilter()">
                <button class="btn-limpiar-fechas" type="button" onclick="limpiarFechas()">Limpiar fechas</button>
            </div>
            <div>
                <table id="TablaUsuarios">
                    <thead>
                        <tr>
                            <th data-column="usuario" data-order="desc">Usuario</th>
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

<!-- Modal -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>
        <div class="modal-header">
            <h2 id="title">Registrar Usuario</h2>
        </div>
        <form id="formularioUsuario" class="form" method="POST">
            <input type="number" id="id" name="id" hidden="true">
            <div class="form-file">
                <div class="form-group">
                    <label for="usuario">Usuario:<span class="required">*</span></label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ej: UserMax123" maxlength="16" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="ci">CI:<span class="required">*</span></label>
                    <input type="text" id="ci" name="ci" placeholder="Ej: 30333222" maxlength="16" autocomplete="off">
                </div>
            </div>
            <div class="form-file">
                <div class="form-group">
                    <label for="rango">Rol:<span class="required">*</span></label>
                    <select name="rango" id="rango">
                        <option value="empleado" selected>Empleado</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
            </div>
            <div class="form-file">
                <div class="form-group">
                    <label for="nombre">Nombre:<span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Pedro" maxlength="15" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:<span class="required">*</span></label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ej: López" maxlength="15" autocomplete="off">
                </div>
            </div>
            <div class="form-file">
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" placeholder="Ej: correoreal@gmail.com" maxlength="25" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="telef">Teléfono:</label>
                    <input type="text" id="telef" name="telef" placeholder="Ej: 0414-1234567" maxlength="15" autocomplete="off">
                </div>
            </div>
            <div class="form-file">
                <div class="form-group">
                    <label for="contrasena">Contraseña:<span class="required">*</span></label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ej: Pedro*15" maxlength="16" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="confirm">Confirmar Contraseña:<span class="required">*</span></label>
                    <input type="password" id="confirm" name="confirm" placeholder="Ej: Pedro*15" maxlength="16" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
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