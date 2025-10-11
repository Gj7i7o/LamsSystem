<!DOCTYPE html>
<html lang="es">

<!-- Index.php encargado del Login: Aquí se muestra un formulario con los campos 
 Usuario y Contraseña, además de los botones de acción (Limpiar, Acceder y Ver)-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>Assets/css/login.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>Assets/css/all.min.css">
    <title>LAMS system</title>
</head>

<body>

    <section>
        <div class="contenedor">
            <div class="form">

                <!-- Formulario de inicio de sesión -->

                <form id="frmInicio" method="POST">
                    <img src="<?php echo APP_URL; ?>Assets/img/Logo.png" title="Logo de la empresa">
                    <h1>Iniciar Sesión</h1>

                    <div class="input-container">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="usuario" id="usuario" title="Nombre de usuario" placeholder="Usuario" required>
                        <button class="limpiar" title="Limpiar campos">Limpiar</button>
                    </div>

                    <div class="input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="contrasena" id="contrasena" title="Contraseña" placeholder="Contraseña" required>
                        <button class="ver" id="togglePassword" title="Ver/Ocultar contraseña">Ver</button>
                    </div>

                    <button class="acceder" type="submit" onclick="frmInicio(event);" title="Acceder al sistema">Acceder</button>

                </form>
            </div>
        </div>
    </section>

    <!-- Se guarda la URL en una variable JS del mismo nombre -->

    <script>
        const APP_URL = '<?php echo APP_URL; ?>';
    </script>

    <!-- Posteriormente se envían los datos al login.js para su evaluación -->

    <script src="<?php echo APP_URL; ?>Assets/js/login.js"></script>

</body>

</html>