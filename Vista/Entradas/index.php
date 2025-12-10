<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-top">
        <h1>Historial de Entradas</h1>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <!-- Botón de registrar -->
            <button class="button" type="button" id="registrarEntrada" title="Crear nueva entrada"><i class="fas fa-plus"></i></button>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass">
                </i><input type="text" name="" id="">
            </div>
            <div>
                <table id="tablaEntradas">
                    <thead>
                        <tr>
                            <th data-column="producto" data-order="desc">Producto</th>
                            <th data-column="proveedor" data-order="desc">Proveedor</th>
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

    <!-- Modal -->
    <div id="modalEntrada" class="modal">
        <div class="modal-content">
            <span class="close" title="Cerrar">&times;</span>
            <div class="modal-header">
                <h2 id="title">Registrar Entrada</h2>
                <!-- <button class="button" type="button" id="addLine" title=""><i class="fas fa-plus"></i></button> -->
            </div>
            <button class="button" type="button" id="addLine" title=""><i class="fas fa-plus"></i></button>
            <form id="formularioEntradas" class="form" method="POST">
                <div id="formularioEntrada">
                    <div class="buttonToLine">
                        <button class="button" type="button" data-idx="0" id="deleteLine"><i class="fas fa-trash"></i></button>
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

    <script src="<?php echo APP_URL; ?>assets/js/modulos/entradas/script.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/modulos/entradas/modal_script.js"></script>
    <?php
    include "vista/componentes/footer.php";
    ?>