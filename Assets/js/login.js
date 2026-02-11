// Formulario del Login: Validando Usuario y Contraseña

function frmInicio(e) {
  e.preventDefault();

  //Asignando valores a variables
  let usuario = document.getElementById("usuario");
  let contrasena = document.getElementById("contrasena");

  //Patrón de validación de usuario
  const password = /^[A-Za-z0-9-_]{3,16}$/;
  if (contrasena.value == "" || usuario.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
    // } else if (!password.test(contrasena)) {
    //   alertas(
    //     "El formato de la contraseña no es válida (*_-, mínimo 3 máximo 16 de largo), una mayúscula y una minúscula",
    //     "warning",
    //   );
  } else {
    //Enviando a Controllers/Users/valid
    const url = APP_URL + "login/validar";
    const frm = document.getElementById("frmInicio");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = async function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res == "ok") {
          window.location = APP_URL + "dashboard/index";
        } else {
          alertas(res.msg, res.icono);
          // usuario.value = "";
          // contrasena.value = "";
        }
      }
    };
  }
}
