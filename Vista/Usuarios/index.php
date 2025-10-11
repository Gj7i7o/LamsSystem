<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de los Usuarios -->

<section class="main">
    <div class="main-top">
        <!-- <i class="fas fa-user"></i> -->
        <h1>Usuarios</h1>
    </div>
    <div class="main-top-text">
        <p>Usuarios y opciones disponibles:</p>
    </div>

    <!-- Tabla Usuarios -->

    <section class="main-course">
        <button class="button" type="button" id="abrirModalBtn" title="Crear Usuario"><i class="fas fa-plus"></i></button>
        <div class="course-box">
            <div class="recent-orders tabla">
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

<div id="miModalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" title="Cerrar">&times;</span>

        <h2>Registrar usuario</h2>

        <form id="userForm" class="form">
          <input type="number" id="id" hidden="true">
          <label for="usuario">Usuario:</label>
          <input type="text" id="usuario" name="usuario" required>

          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" name="nombre" required>
          
          <label for="apellido">Apellido:</label>
          <input type="text" id="apellido" name="apellido" required>

          <label for="correo">Correo:</label>
          <input type="email" id="correo" name="correo" required>

          <label for="telef">Teléfono:</label>
          <input type="text" id="telef" name="telef" required>

          <label for="password">Contraseña:</label>
          <input type="password" id="password" name="password" required>
          
          <button type="submit">Registrar</button>
        </form>
    </div>
</div>

<?php
include "Vista/Componentes/footer.php";
?>