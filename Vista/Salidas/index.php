<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-top">
        <h1>Historial de Salidas</h1>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <button class="button" type="button" id="registrarSalida" title="Crear nueva salida"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
            </div>
            <div>
                <table id="tablaSalidas">
                    <thead>
                        <tr>
                            <th data-column="producto" data-order="desc">Producto</th>
                            <th data-column="usuario" data-order="desc">Usuario</th>
                            <th data-column="cantidad" data-order="desc">Cantidad</th>
                            <th data-column="fecha" data-order="desc">Fecha</th>
                            <th data-column="hora" data-order="desc">Hora</th>
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

    <div id="modalSalida" class="modal">
        <div class="modal-content">
            <span class="close" title="Cerrar">&times;</span>

            <h2 id="title">Registrar Salida</h2>
            <button class="button" type="button" id="addLine" title=""><i class="fas fa-plus"></i></button>

            <form id="formularioSalidas" class="form" method="POST">
                <div id="formularioSalida">
                    <div class="buttonToLine">
                        <button class="button" type="button" data-idx="0" id="deleteLine"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div style="margin-top: 15px">
                    <button type="submit" id="btnAccion">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>Assets/js/modulos/Salidas/script.js"></script>
    <script src="<?php echo APP_URL; ?>Assets/js/modulos/Salidas/modal_script.js"></script>
    <?php
    include "Vista/Componentes/footer.php";
    ?>