/*Modal para registrar proveedor*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalProveedor");
const btn = document.getElementById("registrarProveedor");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  document.getElementById("title").innerHTML = "Registrar Proveedor";
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
  document.getElementById("rif").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
  document.getElementById("direccion").value = "";
}

/*Botón de modificar proveedor*/
function btnEditProveedor(id) {
  document.getElementById("title").innerHTML = "Actualizar Proveedor";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "proveedores/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("rif").value = res.rif;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("apellido").value = res.apellido;
      document.getElementById("direccion").value = res.direccion;
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
formularioProveedor.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const rif = document.getElementById("rif");
  const nombre = document.getElementById("nombre");
  const apellido = document.getElementById("apellido");
  const direccion = document.getElementById("direccion");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  let regexrif = /^([JGVPEjgvpe])([0-9]{8,9})$/;
  if (
    rif.value == "" ||
    nombre.value == "" ||
    apellido.value == "" ||
    direccion.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (letras.test(nombre)) {
    alertas("No agregue caracteres indevidos en su nombre", "warning");
  } else if (letras.test(apellido)) {
    alertas("No agregue caracteres indevidos en su apellido", "warning");
  } else if (regexrif.test(rif)) {
    alertas("Escriba correctamente el rif", "warning");
  } else {
    const url = APP_URL + "proveedores/registrar";
    const frm = document.getElementById("formularioProveedor");
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
