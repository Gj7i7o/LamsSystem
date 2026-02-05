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
  document.getElementById("ci").value = "";
  document.getElementById("usuario").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
  document.getElementById("correo").value = "";
  document.getElementById("telef").value = "";
  document.getElementById("contrasena").value = "";
  document.getElementById("confirm").value = "";
  document.getElementById("rango").value = "empleado";
}

/*Botón de editar usuario*/
function btnEditUsuario(id) {
  document.getElementById("title").innerHTML = "Actualizar Usuario";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "usuarios/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("ci").value = res.ci || "";
      document.getElementById("usuario").value = res.usuario;
      document.getElementById("nombre").value = res.nombre || "";
      document.getElementById("apellido").value = res.apellido || "";
      document.getElementById("correo").value = res.correo || "";
      document.getElementById("telef").value = res.telef || "";
      document.getElementById("rango").value = res.rango || "";
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
formularioUsuario.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario
  const ci = document.getElementById("ci");
  const usuario = document.getElementById("usuario");
  const nombre = document.getElementById("nombre");
  const apellido = document.getElementById("apellido");
  const correo = document.getElementById("correo");
  const telef = document.getElementById("telef");
  const rango = document.getElementById("rango");
  const contrasena = document.getElementById("contrasena");
  const confirm = document.getElementById("confirm");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  let pass =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/;
  let email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  let phone = /^(0412|0416|0414|0424|0426)[0-9]{7}$/;
  if (
    ci.value == "" ||
    usuario.value == "" ||
    nombre.value == "" ||
    apellido.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (contrasena.value != confirm.value) {
    alertas("Las contraseñas no coinciden", "warning");
  } else if (letras.test(nombre)) {
    alertas("No agregue caracteres indevidos en su nombre", "warning");
  } else if (letras.test(apellido)) {
    alertas("No agregue caracteres indevidos en su apellido", "warning");
  } else if (pass.test(contrasena)) {
    alertas("La contraseña NO cumple con las especificaciones", "warning");
    // } else if (email.test(correo)) {
    //   alertas("Escriba correctamente el correo", "warning");
    // } else if (phone.test(telef)) {
    //   alertas("Escriba correctamente su número", "warning");
  } else {
    const esEdicion = document.getElementById("id").value !== "";
    const enviarFormulario = () => {
      const url = APP_URL + "usuarios/registrar";
      const frm = document.getElementById("formularioUsuario");
      const http = new XMLHttpRequest();
      http.open("POST", url, true);
      http.send(new FormData(frm));
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res.icono != "success") {
            alertas(res.msg, res.icono);
          } else {
            alertas(res.msg, res.icono);
            modal.style.display = "none";
            limpiarFormulario();
            recargarVista();
          }
        }
      };
    };
    if (esEdicion) {
      Swal.fire({
        title: "¿Está seguro de guardar los cambios?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) enviarFormulario();
      });
    } else {
      enviarFormulario();
    }
  }
});
