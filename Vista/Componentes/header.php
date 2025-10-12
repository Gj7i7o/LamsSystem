<!DOCTYPE html>
<html lang="en">

<!-- Header usado como estandar. Para la reutilización de su código en las diferentes secciones/módulos del sistema -->

<head>

    <!-- Además de agregar los componentes css para su uso -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>Assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>Assets/css/all.min.css">
    <title>LAMS system</title>
</head>

<!-- Dashboard o Menú donde el usuario puede pulsar click para ir a los módulos, o salir del sistema -->

<body>
    <div class="container">
        <nav>
            <div href="#" class="logo">
                <img src="<?php echo APP_URL; ?>Assets/img/Logo.png" alt="" title="Logo de la empresa">
                <p>Multirepuestos y Accesorios, Carúpano. C.A (MULTIRECA)</p>
                <!-- <p><?php echo $_SESSION['usuario']; ?></p> -->
            </div>
            <ul>
                <li>
                    <a href="<?php echo APP_URL; ?>Dashboard" title="Panel de control">
                        <span class="nav-item"><i class="fas fa-home"></i> Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Productos" title="Inventario">
                        <span class="nav-item"><i class="fas fa-boxes-stacked icon"></i> Productos</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Entradas" title="Entradas del sistema">
                        <span class="nav-item"><i class="fa-solid fa-door-closed"></i> Entradas</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Salidas" title="Salidas del sistema">
                        <span class="nav-item"><i class="fa-solid fa-door-open"></i> Salidas</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Categorias" title="Categorias de productos">
                        <span class="nav-item"><i class="fa-solid fa-list"></i> Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Marcas" title="Marcas de productos">
                        <span class="nav-item"><i class="fas fa-copyright"></i> Marcas</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo APP_URL; ?>Proveedores" title="Proveedores del sistema">
                        <span class="nav-item"><i class="fa-solid fa-truck-field"></i> Proveedores</span>
                    </a>
                </li>
                <!-- Condición que oculta o muestra la sección Usuarios si el Usuario que entra poseé el rango empleado -->
                <?php if ($_SESSION['rango'] == "empleado") { ?>
                    <li hidden="true">
                        <a href="<?php echo APP_URL; ?>Usuarios" title="Usuarios en sistema">
                            <span class="nav-item"><i class="fas fa-user"></i> Usuarios</span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?php echo APP_URL; ?>Usuarios" title="Usuarios en sistema">
                            <span class="nav-item"><i class="fas fa-user"></i> Usuarios</span>
                        </a>
                    </li>
                <?php } ?>


                <li>
                    <a href="<?php echo APP_URL; ?>Usuarios/logout" class="logout" title="Cerrar sesión">
                        <span class="nav-item"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </nav>