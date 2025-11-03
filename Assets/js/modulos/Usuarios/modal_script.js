/*Modal para registrar usuario*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalUsuario");
const btn = document.getElementById("registrarUsuario");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  document.getElementById("title").innerHTML = "Registrar Usuario";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  modal.style.display = "block";
};

// Cuando el usuario hace clic en la <span> (x), cierra el modal
span.onclick = function () {
  modal.style.display = "none";
  limpiarFormulario();
};

function limpiarFormulario() {
  document.getElementById("id").value = "";
  document.getElementById("usuario").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
  document.getElementById("correo").value = "";
  document.getElementById("telef").value = "";
  document.getElementById("password").value = "";
  document.getElementById("confirm").value = "";
}

/*Botón de editar usuario*/
function btnEditUsuario(id) {
  document.getElementById("title").innerHTML = "Actualizar Usuario";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Usuarios/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("usuario").value = res.usuario;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("apellido").value = res.apellido;
      document.getElementById("correo").value = res.correo;
      document.getElementById("telef").value = res.telef;
      //   document.getElementById("password").classList.add("d-none");
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
formularioUsuario.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const user = document.getElementById("usuario");
  const name = document.getElementById("nombre");
  const ape = document.getElementById("apellido");
  const email = document.getElementById("correo");
  const telef = document.getElementById("telef");
  const password = document.getElementById("password");
  const confirm = document.getElementById("confirm");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  let pass =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/;
  let correo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (
    user.value == "" ||
    name.value == "" ||
    ape.value == "" ||
    email.value == "" ||
    telef.value == ""
  ) {
    alertas("Todos los campos SON obligatorios", "warning");
  } else if (password.value != confirm.value) {
    alertas("Las contraseñas no coinciden", "warning");
  } else if (letras.test(name)) {
    alertas("No agregue caracteres indevidos en su nombre", "warning");
  } else if (letras.test(ape)) {
    alertas("No agregue caracteres indevidos en su apellido", "warning");
  } else if (pass.test(password)) {
    alertas("La contraseña NO cumple con las especificaciones", "warning");
  } else if (correo.test(email)) {
    alertas("Escriba correctamente el correo", "warning");
  } else {
    const url = APP_URL + "Usuarios/store";
    const frm = document.getElementById("formularioUsuario");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        alertas(res.msg, res.icono);
        modal.style.display = "none";
        limpiarFormulario();
      }
    };
  }
});
