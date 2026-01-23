/*Modal para registrar marca*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalMarca");
const btn = document.getElementById("registrarMarca");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  document.getElementById("title").innerHTML = "Registrar Marca";
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
  document.getElementById("nombre").value = "";
}

/*Botón de editar usuario*/
function btnEditMarca(id) {
  document.getElementById("title").innerHTML = "Actualizar Marca";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "marcas/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
formularioMarca.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const nombre = document.getElementById("nombre");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  if (nombre.value == "") {
    alertas("Todos los campos SON obligatorios", "warning");
  } else if (letras.test(nombre)) {
    alertas("No agregue caracteres indevidos en el nombre", "warning");
  } else {
    const url = APP_URL + "marcas/registrar";
    const frm = document.getElementById("formularioMarca");
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
  }
});
