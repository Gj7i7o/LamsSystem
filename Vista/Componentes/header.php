<!DOCTYPE html>
<html lang="en">

<!-- Header usado como estandar. Para la reutilización de su código en las diferentes secciones/módulos del sistema -->

<head>

    <!-- Además de agregar los componentes css para su uso -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/all.min.css">
    <title>LAMS system</title>
</head>

<!-- Dashboard o Menú donde el usuario puede pulsar click para ir a los módulos, o salir del sistema -->

<body>
    <div class="container">
        <nav>
            <ul>
                <li>
                    <!-- Botón del inicio (Panel de control) -->
                    <a href="<?php echo APP_URL; ?>dashboard" title="Panel de control"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fas fa-home"></i> Inicio</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de producto -->
                    <a href="<?php echo APP_URL; ?>productos" title="Inventario"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'productos') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fas fa-boxes-stacked icon"></i> Productos</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de entradas -->
                    <a href="<?php echo APP_URL; ?>entradas" title="Entradas del sistema"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'entradas') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fa-solid fa-door-closed"></i> Entradas</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de salidas -->
                    <a href="<?php echo APP_URL; ?>salidas" title="Salidas del sistema"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'salidas') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fa-solid fa-door-open"></i> Salidas</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de categorías -->
                    <a href="<?php echo APP_URL; ?>categorias" title="Categorias de productos"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'categorias') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fa-solid fa-list"></i> Categorías</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de marcas -->
                    <a href="<?php echo APP_URL; ?>marcas" title="Marcas de productos"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'marcas') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fas fa-copyright"></i> Marcas</span>
                    </a>
                </li>
                <li>
                    <!-- Botón del módulo de proveedores -->
                    <a href="<?php echo APP_URL; ?>proveedores" title="Proveedores del sistema"
                        class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'proveedores') !== false) ? 'active' : ''; ?>">
                        <span class="nav-item"><i class="fa-solid fa-truck-field"></i> Proveedores</span>
                    </a>
                </li>
                <!-- Condición que oculta o muestra la sección Usuarios si el Usuario que entra poseé el rango empleado -->
                <?php if ($_SESSION['rango'] == "empleado") { ?>
                    <li hidden="true">
                        <!-- Botón del módulo de usuarios -->
                        <a href="<?php echo APP_URL; ?>usuarios" title="Usuarios en sistema"
                            class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false) ? 'active' : ''; ?>">
                            <span class="nav-item"><i class="fas fa-user"></i> Usuarios</span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li>
                        <!-- Botón del módulo de usuarios -->
                        <a href="<?php echo APP_URL; ?>usuarios" title="Usuarios en sistema"
                            class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false) ? 'active' : ''; ?>">
                            <span class="nav-item"><i class="fas fa-user"></i> Usuarios</span>
                        </a>
                    </li>
                <?php } ?>


                <li>
                    <!-- Botón de cerrar sesión -->
                    <a href="<?php echo APP_URL; ?>usuarios/logout" class="logout" title="Cerrar sesión">
                        <span class="nav-item"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Footer con el nombre y logo de la empresa -->
        <footer>
            <div href="#" class="logo">
                <img src="<?php echo APP_URL; ?>assets/img/logo.png" alt="" title="Logo de la empresa">
            </div>
            <div class="logo-text">
                <p>Multirepuestos y Accesorios, Carúpano. C.A (MULTIRECA)</p>
            </div>
        </footer>