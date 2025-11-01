/*Modal para registrar categoria*/
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
  document.getElementById("des").value = "";
}

/*Botón de editar usuario*/
function btnEditCategoria(id) {
  document.getElementById("title").innerHTML = "Actualizar Categoría";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Categorias/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("des").value = res.descrip;
      modal.style.display = "block";
    }
  };
}

/*Acción de registrar categoria, validaciones y alertas*/
function registrarCategoria(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  const descrip = document.getElementById("des");
  if (nombre.value == "" || descrip.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Categorias/store";
    const frm = document.getElementById("formularioCategoria");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    console.log("ver: ", nombre.value, descrip.value);
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        modal.style.display = "none";
        alertas(res.msg, res.icono);
        limpiarFormulario();
      }
    };
  }
}
