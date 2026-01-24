/*
Script de Marca: Sección de JavaScript propio para las funciones del módulo
de marcas. 
*/

//Tabla Marcas
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#TablaMarcas tbody");
  const headers = document.querySelectorAll("#TablaMarcas th");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");

  let paginaActual = 1;
  const rowsPerPage = 10;
  let currentData = [];
  let currentTotal = 0;

  async function tablaMarcas() {
    try {
      let estado = document.getElementById("estado");
      let query = document.getElementById("query");
      const params = new URLSearchParams({
        page: paginaActual,
        estado: estado?.value || "activo",
        query: query?.value || "",
      });

      const response = await fetch(
        "http://localhost/LamsSystem/marcas/listar?" + params,
      );

      // Si la respuesta no es exitosa, lanza un error
      if (response.error) {
        throw new Error(`Error en la solicitud: ${response.error}`);
      }

      const { data, total } = await response.json();
      // Convierte la respuesta JSON en un array de JavaScript
      currentData = [...data];
      currentTotal = total;

      // Llama a mostrarTabla para mostrar los datos obtenidos
      mostrarTabla();
    } catch (error) {
      console.error("No se pudo obtener la data:", error);
    }
  }

  // Exponer la función para poder llamarla desde setfilter
  window.fetchMarcas = tablaMarcas;

  function mostrarTabla() {
    tableBody.innerHTML = "";
    const paginatedData = currentData;

    if (paginatedData.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="4">No hay datos disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.nombre}</td>
                <td>${item.estado}</td>
                <td>${item.acciones}</td>
            `;
      tableBody.appendChild(row);
    });

    updatePaginationInfo();
  }

  async function updatePaginationInfo() {
    const pages = getTotalPages();
    pageInfo.textContent = `Página ${paginaActual} de ${pages}`;
    prevBtn.disabled = paginaActual === 1;
    nextBtn.disabled = paginaActual === pages || pages === 0;
  }

  function sortTable(column, order) {
    currentData.sort((a, b) => {
      const valA = a[column];
      const valB = b[column];
      if (valA < valB) return order === "asc" ? -1 : 1;
      if (valA > valB) return order === "asc" ? 1 : -1;
      return 0;
    });
    paginaActual = 1;
    mostrarTabla();
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
    if (paginaActual > 1) {
      paginaActual--;
      await tablaMarcas();
      mostrarTabla();
    }
  });

  nextBtn.addEventListener("click", async () => {
    const totalPages = getTotalPages();
    if (paginaActual < totalPages) {
      paginaActual++;
      await tablaMarcas();
      mostrarTabla();
    }
  });

  tablaMarcas();
});

/*Función para filtrar por estado*/
function setfilter() {
  if (window.fetchMarcas) {
    window.fetchMarcas();
  }
}

/*Botón para desactivar marcas*/
function btnDesMarca(id) {
  Swal.fire({
    title: "Está seguro de desactivar la marca?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "marcas/desactivar/" + id;
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

/*Botón para activar marcas*/
function btnActMarca(id) {
  Swal.fire({
    title: "Activar marca?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "marcas/activar/" + id;
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
