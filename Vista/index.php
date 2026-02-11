<!DOCTYPE html>
<html lang="es">

<!-- Index.php del LOGIN o inicio de sesión -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/login.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/all.min.css">
    <title>LAMS system</title>
</head>

<body>

    <section>
        <div class="contenedor">
            <div class="form">

                <!-- Formulario de inicio -->

                <form id="frmInicio" method="POST">
                    <img src="<?php echo APP_URL; ?>Assets/img/Logo.png" title="Logo de la empresa">
                    <h1>Iniciar Sesión</h1>

                    <div class="input-container">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="usuario" id="usuario" title="Nombre de usuario" placeholder="Usuario" maxlength="16" autocomplete="off">
                    </div>

                    <div class="input-container" style="position: relative;">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="contrasena" id="contrasena" title="Contraseña" placeholder="Contraseña" maxlength="16" autocomplete="off">

                        <!-- Ojo para ocultar/mostrar contraseña -->
                        <i id="togglePassword"
                            class="fa-solid fa-eye"
                            style="position:absolute; right:20px; top:37%; transform:translateY(-50%); cursor:pointer;">
                        </i>
                    </div>

                    <button class="acceder" type="submit" onclick="frmInicio(event);" title="Acceder al sistema">Acceder</button>

                    <!-- Botón para limpiar campos -->
                    <button type="button" id="btnLimpiar" class="limpiar" title="Limpiar campos">Limpiar</button>

                </form>
            </div>
        </div>
    </section>

    <!-- SCRIPTS -->

    <script>
        const APP_URL = '<?php echo APP_URL; ?>';
    </script>

    <script src="<?php echo APP_URL; ?>assets/js/login.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/app.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/sweetalert2.all.min.js"></script>

    <!-- SCRIPT OJITO -->
    <script>
        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("contrasena");

        togglePassword.addEventListener("click", function() {
            const type = password.type === "password" ? "text" : "password";
            password.type = type;
            if (type === 'password') {
                // Contraseña OCULTA -> Mostrar ícono de OJO ABIERTO
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            } else {
                // Contraseña VISIBLE -> Mostrar ícono de OJO TACHADO
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            }
        });
    </script>

    <!--SCRIPT PARA LIMPIAR CAMPOS -->
    <script>
        const btnLimpiar = document.getElementById("btnLimpiar");
        const usuario = document.getElementById("usuario");
        const contrasena = document.getElementById("contrasena");

        btnLimpiar.addEventListener("click", function() {
            usuario.value = "";
            contrasena.value = "";
            usuario.focus();
        });
    </script>

</body>

</html>