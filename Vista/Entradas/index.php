<?php
include "Vista/Componentes/header.php";
?>

<!-- Index.php encargado de la vista de las Entradas -->

<section class="main">
    <div class="main-top">
        <h1>Entradas disponibles</h1>
    </div>
    <div class="main-top-text">
        <p>Entradas y opciones disponibles:</p>
    </div>

    <!-- Tabla de productos -->

    <section class="main-course">
        <!-- <button class="button" type="button" onclick="frmJoin();" title="Añadir cantidad a producto"><i class="fas fa-plus"></i></button> -->
        <div class="course-box">
            <div class="recent-orders tabla">
                <table class="table" id="tblJoin">
                    <thead class="thead-light">
                        <tr>
                            <td>Código</td>
                            <td>Producto</td>
                            <td>Cantidad</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody class="table__body">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <div class="main-top">
        <!-- <i class="fas fa-layer-group"></i> -->
        <h1>Historial de Entradas</h1>
    </div>
    <section class="main-course">
        <!-- <button class="button" type="button" onclick="frmJoin();" title="Añadir cantidad a producto"><i class="fas fa-plus"></i></button> -->
        <div class="course-box">
            <div class="recent-orders tabla">
                <table class="table" id="tblEntrada">
                    <thead class="thead-light">
                        <tr>
                            <td>Cantidad</td>
                            <td>Precio $</td>
                            <td>Nombre</td>
                            <td>Fecha</td>
                            <td>Hora</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody class="table__body">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</section>

<!-- Modal para ingresar nuevo proveedor -->

<div id="show_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ba-primary">
                <h5 class="modal-title title-modal titleColor" id="title">Proveedor</h5>
            </div>
            <div class="modal-body">
                <form method="post" id="frmProveedor">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id">
                        <!-- <label for="name" class="text-label">Nombre</label> -->
                        <input id="name" class="form-control" type="text" name="name" placeholder="Nombre" title="Nombre del proveedor">
                    </div>
                    <div class="form-group">
                        <!-- <label for="ape" class="text-label">Apellido</label> -->
                        <input id="ape" class="form-control" type="text" name="ape" placeholder="Apellido" title="Apellido del proveedor">
                    </div>
                    <div class="form-group">
                        <!-- <label for="rif" class="text-label">Rif</label> -->
                        <input id="rif" class="form-control" type="text" name="rif" placeholder="Rif" title="Rif del proveedor">
                    </div>
                    <div class="form-group">
                        <!-- <label for="dir" class="text-label">Dirección</label> -->
                        <input id="dir" class="form-control" type="text" name="dir" placeholder="Dirección" title="Ubicación del proveedor">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir cantidad -->

<div id="sumar_cantidad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ba-primary">
                <h5 class="modal-title title-modal titleColor" id="title">Cenerar entrada</h5>
            </div>
            <div class="modal-body">
                <form method="post" id="frmCantidad">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id">
                        <input id="cantidad" class="form-control" type="number" name="cantidad" placeholder="Cantidad" title="Cantidad de producto a ingresar">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="button" type="button" onclick="registrarEntrada(event);" id="btnAccion">Registrar</button>
            </div>
        </div>
    </div>
</div>

<?php
include "Vista/Componentes/footer.php";
?>