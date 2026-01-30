/*Modal para registrar categoría*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalCategoria");
const btn = document.getElementById("registrarCategoria");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  document.getElementById("title").innerHTML = "Registrar Categoría";
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
  document.getElementById("descripcion").value = "";
}

/*Botón de editar usuario*/
function btnEditCategoria(id) {
  document.getElementById("title").innerHTML = "Actualizar Categoría";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "categorias/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("descripcion").value = res.descrip || "";
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
const formularioCategoria = document.getElementById("formularioCategoria");
formularioCategoria.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const nombre = document.getElementById("nombre");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  if (nombre.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (letras.test(nombre)) {
    alertas("No agregue caracteres indevidos en el nombre", "warning");
  } else {
    const url = APP_URL + "categorias/registrar";
    const frm = document.getElementById("formularioCategoria");
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
