/*
Script de Categorías: Sección de JavaScript propio para las funciones del módulo
de categorías. 
*/

//Tabla Categorías
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#TablaCategorias tbody");
  const headers = document.querySelectorAll("#TablaCategorias th");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");
  // const estado = document.getElementById("estado");

  let currentPage = 1;
  const rowsPerPage = 10;
  let currentData = [];
  let currentTotal = 0;

  async function fetchDataAndRenderTable() {
    try {
      let estado = document.getElementById("estado");
      let query = document.getElementById("query");
      // Construir la URL con query parameters
      let fecha_desde = document.getElementById("fecha_desde");
      let fecha_hasta = document.getElementById("fecha_hasta");
      const params = new URLSearchParams({
        page: currentPage,
        query: query?.value || "",
        estado: estado?.value || "activo",
        fecha_desde: fecha_desde?.value || "",
        fecha_hasta: fecha_hasta?.value || "",
      });

      const url = `http://localhost/LamsSystem/categorias/listar?${params}`;

      // Realizar la petición GET
      const response = await fetch(url, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

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
        '<tr><td colspan="5">No hay datos disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td style="text-align: center;">${item.id}</td>
                <td>${item.nombre}</td>
                <td>${item.descrip || "-"}</td>
                <td style="text-align: center;">${item.estado}</td>
                <td style="text-align: center;">${item.acciones}</td>
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

  // Exponer la función para poder llamarla desde setfilter
  window.fetchCategorias = fetchDataAndRenderTable;

  // 3. Llama a la función para iniciar el proceso
  fetchDataAndRenderTable();
});

/*Función para filtrar por estado*/
function setfilter() {
  if (window.fetchCategorias) {
    window.fetchCategorias();
  }
}

/*Función para limpiar filtros de fecha*/
function limpiarFechas() {
  document.getElementById("fecha_desde").value = "";
  document.getElementById("fecha_hasta").value = "";
  setfilter();
}

/*Botón para desactivar categorías*/
function btnDesCategoria(id) {
  Swal.fire({
    title: "Está seguro de desactivar la categoría?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "categorias/desactivar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          recargarVista();
        }
      };
    }
  });
}

/*Botón para activar categorías*/
function btnActCategoria(id) {
  Swal.fire({
    title: "Activar categoría?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "categorias/activar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          alertas(res.msg, res.icono);
          recargarVista();
        }
      };
    }
  });
}

/*Función para descargar reporte PDF*/
function descargarPDF() {
  const estado = document.getElementById("estado")?.value || "todo";
  const query = document.getElementById("query")?.value || "";
  const fecha_desde = document.getElementById("fecha_desde")?.value || "";
  const fecha_hasta = document.getElementById("fecha_hasta")?.value || "";
  window.open(
    APP_URL +
      "categorias/reportePDF?estado=" +
      estado +
      "&query=" +
      query +
      "&fecha_desde=" +
      fecha_desde +
      "&fecha_hasta=" +
      fecha_hasta,
    "_blank",
  );
}
