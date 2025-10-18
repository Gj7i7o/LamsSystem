// Formulario del Login: Validando Usuario y Contraseña

function frmInicio(e) {
  e.preventDefault();

  //Asignando valores a variables
  let usuario = document.getElementById("usuario");
  let contrasena = document.getElementById("contrasena");

  //Patrón de validación de usuario
  const usuarioValido = /^[A-Za-z0-9-_]{3,16}$/;
  if (usuario.value == "" || !usuarioValido.test(usuario.value)) {
    //Evaluando Usuario
    contrasena.classList.remove("is-invalid");
    usuario.classList.add("is-invalid");
    usuario.focus();
  } else if (contrasena.value == "") {
    //Evaluando contraseña
    usuario.classList.remove("is-invalid");
    contrasena.classList.add("is-invalid");
    contrasena.focus();
  } else {
    //Enviando a Controllers/Users/valid
    const url = APP_URL + "Usuarios/validar";
    const frm = document.getElementById("frmInicio");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          window.location = APP_URL + "Dashboard/index";
        } else {
          document.getElementById("alerta").classList.remove("d-none");
          document.getElementById("alerta").innerHTML = res;
          usuario.value = "";
          contrasena.value = "";
        }
      }
    };
  }
}
