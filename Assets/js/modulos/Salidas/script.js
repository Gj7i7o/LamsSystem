/*
Script de salidas: Sección de JavaScript propio para las funciones del módulo
de salidas. 
*/

//Tabla Entradas
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#tablaSalidas tbody");
  const headers = document.querySelectorAll("#tablaSalidas th");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");

  let currentPage = 1;
  const rowsPerPage = 10;
  let currentData = [];
  let currentTotal = 0;

  // 2. Función para obtener los datos del servidor
  async function fetchDataAndRenderTable() {
    try {
      let query = document.getElementById("query");
      const params = new URLSearchParams({
        page: currentPage,
        query: query?.value || "",
      });

      const response = await fetch(
        "http://localhost/LamsSystem/Salidas/list?" + params
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

  // Mapeo de valores de tipo_despacho a etiquetas legibles
  const tipoDespachoLabels = {
    'venta': 'Venta',
    'uso_interno': 'Uso Interno',
    'danado': 'Dañado',
    'devolucion': 'Devolución'
  };

  function renderTable() {
    tableBody.innerHTML = "";
    const paginatedData = currentData;

    if (paginatedData.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="5">No hay salidas disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      const tipoDespachoLabel = tipoDespachoLabels[item.tipo_despacho] || item.tipo_despacho;
      row.innerHTML = `
                <td>${item.cod_docum}</td>
                <td>${tipoDespachoLabel}</td>
                <td>${item.total}$</td>
                <td>${item.fecha}</td>
                <td>${item.hora}</td>
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
  window.fetchSalidas = fetchDataAndRenderTable;

  // 3. Llama a la función para iniciar el proceso
  fetchDataAndRenderTable();
});

/*Función para filtrar por búsqueda*/
function setfilter() {
  if (window.fetchSalidas) {
    window.fetchSalidas();
  }
}
