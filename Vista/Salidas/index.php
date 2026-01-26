<?php
include "vista/componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-title">
        <h1>Historial de Salidas</h1>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <div class="course-box">
            <div class="form-file">
                <div>
                    <!-- Botón de registrar -->
                    <button class="button" type="button" id="registrarSalida" title="Crear nueva salida"><i class="fas fa-plus"></i></button>
                </div>
                <div class="buscador">
                    <i class="fa-solid fa-magnifying-glass">
                    </i><input type="text" name="query" id="query" placeholder="Buscar..." oninput="setfilter()">
                </div>
            </div>
            <div>
                <table id="tablaSalidas">
                    <thead>
                        <tr>
                            <th data-column="cod_docum" data-order="desc">Código</th>
                            <th data-column="total" data-order="desc">Precio Total</th>
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
    <div id="modalSalida" class="modal">
        <div class="modal-content">
            <span class="close" title="Cerrar">&times;</span>

            <div class="modal-header">
                <h2 id="title">Registrar Salida</h2>
            </div>

            <div class="modal-header-info">
                <div class="datos-proveedor">

                </div>
                <div class="fecha">
                    <h3>Fecha: <span id="fecha"></span></h3>
                    <input type="text" id="codigo" name="codigo" placeholder="Código Documento" required>
                </div>
            </div>

            <form id="formularioSalidas" class="form" method="POST">
                <div id="formularioSalida">
                    <div class="buttonToLine">
                        <button class="button" type="button" data-idx="0" id="deleteLine"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="modal-footer-info">
                    <div class="datos-proveedor">
                        <h3>Total: </h3>
                    </div>
                    <div class="total" title="Precio total">
                        <input type="number" step="0.01" min="0.00" id="total" value="0.00" name="total" disabled onchange="getTotal()">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="button" type="button" id="addLine" title="Añadir línea de salida"><i class="fas fa-plus"></i></button>
                    <button type="submit" id="btnAccion" class="btn-submit">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>assets/js/modulos/salidas/script.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/modulos/salidas/modal_script.js"></script>
    <script>
        const idusuario = '<?php echo $_SESSION['id_usuario']; ?>';
    </script>

    <script>
        const elementoFecha = document.getElementById('fecha');
        const hoy = new Date();
        elementoFecha.textContent = hoy.toLocaleDateString();
    </script>
    <?php
    include "vista/componentes/footer.php";
    ?>