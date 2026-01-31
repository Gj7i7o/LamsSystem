async function getListadoCategoria() {
  const response = await fetch(
    "http://localhost/LamsSystem/categorias/getSelect",
  );
  const { data: opciones } = await response.json();
  const select = document.getElementById("categoria");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  await opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `
    <option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
}

async function getListadoMarca() {
  const response = await fetch("http://localhost/LamsSystem/marcas/getSelect");
  const { data: opciones } = await response.json();
  const select = document.getElementById("marca");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  await opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `
    <option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
}

/*Modal para registrar producto*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalProducto");
const btn = document.getElementById("registrarProducto");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  getListadoCategoria();
  getListadoMarca();
  document.getElementById("title").innerHTML = "Registrar Producto";
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
  document.getElementById("codigo").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("precioVenta").value = "";
  document.getElementById("precioCosto").value = "0";
  document.getElementById("cantidadMinima").value = "1";
}

/*Botón de modificar producto*/
function btnEditProducto(id) {
  document.getElementById("title").innerHTML = "Actualizar Producto";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "productos/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = async function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = await JSON.parse(this.responseText);
      await getListadoCategoria();
      await getListadoMarca();
      document.getElementById("id").value = res.id;
      document.getElementById("codigo").value = res.codigo;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("precioVenta").value = res.precioVenta;
      document.getElementById("precioCosto").value = res.precioCosto || 0;
      document.getElementById("cantidadMinima").value = res.cantidadMinima || 1;
      document.getElementById("categoria").value = res.idcategoria;
      document.getElementById("marca").value = res.idmarca;
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
formularioProducto.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const codigo = document.getElementById("codigo");
  const nombre = document.getElementById("nombre");
  const precioVenta = document.getElementById("precioVenta");
  const categoria = document.getElementById("categoria");
  const marca = document.getElementById("marca");
  if (
    codigo.value == "" ||
    nombre.value == "" ||
    precioVenta.value == "" ||
    categoria.value == "" ||
    marca.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const esEdicion = document.getElementById("id").value !== "";
    const enviarFormulario = () => {
      const url = APP_URL + "productos/registrar";
      const frm = document.getElementById("formularioProducto");
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
