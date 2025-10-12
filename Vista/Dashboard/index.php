<?php
include 'Vista/Componentes/header.php';
?>

<!-- Index.php encargado de la vista del Inicio -->

<section class="main">
    <div class="main-top">
        <h1>Panel de Control</h1>
    </div>
    <div class="main-top-text">
        <p>Bienvenido, este es el resumen general de tu inventario:</p>
    </div>

    <!-- Muestra en unas cartas el total de objetos que hay en el sistema -->

    <div class="main-skills">
        <div class="card" title="Productos en sistema">
            <i class="fas fa-boxes-stacked"></i>
            <h3>Productos</h3>
            <p><?php echo $data['producto']['total']; ?></p>
        </div>
        <div class="card" title="Categorias en sistema">
            <i class="fa-solid fa-list"></i>
            <h3>Categorias</h3>
            <p><?php echo $data['categoria']['total']; ?></p>
        </div>
        <div class="card" title="Marcas en sistema">
            <i class="fas fa-copyright"></i>
            <h3>Marcas</h3>
            <p><?php echo $data['marca']['total']; ?></p>
        </div>
        <div class="card" title="Proveedores en sistema">
            <i class="fa-solid fa-truck-field"></i>
            <h3>Proveedores</h3>
            <p><?php echo $data['proveedor']['total']; ?></p>
        </div>
        <div class="card" title="Usuarios en sistema">
            <i class="fas fa-user"></i>
            <h3>Usuarios</h3>
            <p><?php echo $data['usuario']['total']; ?></p>
        </div>
    </div>

</section>

<?php
include 'Vista/Componentes/footer.php';
?>