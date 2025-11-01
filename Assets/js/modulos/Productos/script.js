/*
Script de productos: Sección de JavaScript propio para las funciones del módulo
de productos. 
*/

//Tabla Productos
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#TablaProductos tbody");
  const headers = document.querySelectorAll("#TablaProductos th");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");

  let currentPage = 1;
  const rowsPerPage = 5;
  let currentData = [];
  let currentTotal = 0;

  // 2. Función para obtener los datos del servidor
  async function fetchDataAndRenderTable() {
    try {
      // Reemplaza '/api/productos' con la URL real de tu backend
      const response = await fetch(
        "http://localhost/LamsSystem/Productos/list?page=" + currentPage
      );

      // Si la respuesta no es exitosa, lanza un error
      if (response.error) {
        throw new Error(`Error en la solicitud: ${response.error}`);
      }

      const { data, total } = await response.json();
      // Convierte la respuesta JSON en un array de JavaScript
      currentData = [...data];
      currentTotal = total;

      // Llama a renderTable para mostrar los datos obtenidos
      renderTable();
    } catch (error) {
      console.error("No se pudo obtener la data:", error);
      // Puedes mostrar un mensaje de error al usuario aquí
    }
  }

  function renderTable() {
    tableBody.innerHTML = "";
    const paginatedData = currentData;

    if (paginatedData.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="8">No hay datos disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${item.codigo}</td>
                <td>${item.nombre}</td>
                <td>${item.precio}</td>
                <td>${item.cantidad}</td>
                <td>${item.categoria}</td>
                <td>${item.marca}</td>
                <td>${item.estado}</td>
                <td>${item.acciones}</td>
            `;
      tableBody.appendChild(row);
    });

    updatePaginationInfo();
  }

  async function updatePaginationInfo() {
    const pages = getTotalPages();
    pageInfo.textContent = `Página ${currentPage} de ${pages}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === pages || pages === 0;
  }

  function sortTable(column, order) {
    currentData.sort((a, b) => {
      const valA = a[column];
      const valB = b[column];
      if (valA < valB) return order === "asc" ? -1 : 1;
      if (valA > valB) return order === "asc" ? 1 : -1;
      return 0;
    });
    currentPage = 1;
    renderTable();
  }

  headers.forEach((header) => {
    header.addEventListener("click", () => {
      const column = header.dataset.column;
      const order = header.dataset.order === "asc" ? "desc" : "asc";
      header.dataset.order = order;
      sortTable(column, order);
    });
  });

  function getTotalPages() {
    const total = currentTotal;
    const resto = total % rowsPerPage;
    const pages = Math.trunc(total / rowsPerPage) + (resto ? 1 : 0);
    return pages;
  }

  prevBtn.addEventListener("click", async () => {
    if (currentPage > 1) {
      currentPage--;
      await fetchDataAndRenderTable();
      renderTable();
    }
  });

  nextBtn.addEventListener("click", async () => {
    const totalPages = getTotalPages();
    if (currentPage < totalPages) {
      currentPage++;
      await fetchDataAndRenderTable();
      renderTable();
    }
  });

  // 3. Llama a la función para iniciar el proceso
  fetchDataAndRenderTable();
});

/*Botón para desactivar proveedores*/
function btnDesProducto(id) {
  Swal.fire({
    title: "Está seguro de desactivar el producto?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "Productos/destroy/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
        }
      };
    }
  });
}

async function getListadoCategoria() {
  const response = await fetch(
    "http://localhost/LamsSystem/Categorias/getSelect"
  );
  const { data: opciones } = await response.json();
  const select = document.getElementById("categoria");
  let opcionesHtml = `<option value="">Selecciones...</option>`;
  await opciones.forEach((opcion) => {
    opcionesHtml += `
    <option value="${opcion.id}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
}

async function getListadoMarca() {
  const response = await fetch("http://localhost/LamsSystem/Marcas/getSelect");
  const { data: opciones } = await response.json();
  const select = document.getElementById("marca");
  let opcionesHtml = `<option value="">Selecciones...</option>`;
  await opciones.forEach((opcion) => {
    opcionesHtml += `
    <option value="${opcion.id}">${opcion.etiqueta}</option>
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
  document.getElementById("precio").value = "";
}

/*Botón de editar categoria*/
function btnEditProducto(id) {
  document.getElementById("title").innerHTML = "Actualizar Producto";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Productos/edit/" + id;
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
      document.getElementById("precio").value = res.precio;
      document.getElementById("categoria").value = res.idcategoria;
      document.getElementById("marca").value = res.idmarca;
      modal.style.display = "block";
    }
  };
}

/*Acción de registrar producto, validaciones y alertas*/
function registrarProduct(e) {
  e.preventDefault();
  const codigo = document.getElementById("codigo");
  const nombre = document.getElementById("nombre");
  const precio = document.getElementById("precio");
  const idcat = document.getElementById("categoria");
  const idmar = document.getElementById("marca");
  if (
    codigo.value == "" ||
    nombre.value == "" ||
    precio.value == "" ||
    idcat.value == "" ||
    idmar.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Productos/store";
    const frm = document.getElementById("formProducto");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        $("#new_product").modal("hide");
        alertas(res.msg, res.icono);
        tblProduct.ajax.reload();
      }
    };
  }
}
