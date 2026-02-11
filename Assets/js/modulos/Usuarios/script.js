/*
Script de usuarios: Sección de JavaScript propio para las funciones del módulo
de usuarios. 
*/

//Tabla Usuarios
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#TablaUsuarios tbody");
  const headers = document.querySelectorAll("#TablaUsuarios th");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");

  let paginaActual = 1;
  const rowsPerPage = 10;
  let currentData = [];
  let currentTotal = 0;

  // 2. Función para obtener los datos del servidor
  async function tablaUsuarios() {
    try {
      let estado = document.getElementById("estado");
      let query = document.getElementById("query");
      let fecha_desde = document.getElementById("fecha_desde");
      let fecha_hasta = document.getElementById("fecha_hasta");
      const params = new URLSearchParams({
        page: paginaActual,
        estado: estado?.value || "activo",
        query: query?.value || "",
        fecha_desde: fecha_desde?.value || "",
        fecha_hasta: fecha_hasta?.value || "",
      });

      const response = await fetch(
        "http://localhost/LamsSystem/usuarios/listar?" + params,
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
      mostrarTabla();
    } catch (error) {
      console.error("No se pudo obtener la data:", error);
    }
  }

  // Exponer la función para poder llamarla desde setfilter
  window.fetchUsuarios = tablaUsuarios;

  function mostrarTabla() {
    tableBody.innerHTML = "";
    const paginatedData = currentData;

    if (paginatedData.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="6">No hay datos disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${item.usuario}</td>
                <td>${item.rango}</td>
                <td>${item.estado}</td>
                <td style="text-align: center;">${item.acciones}</td>
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
      await tablaUsuarios();
      mostrarTabla();
    }
  });

  nextBtn.addEventListener("click", async () => {
    const totalPages = getTotalPages();
    if (paginaActual < totalPages) {
      paginaActual++;
      await tablaUsuarios();
      mostrarTabla();
    }
  });

  // 3. Llama a la función para iniciar el proceso
  tablaUsuarios();
});

/*Función para filtrar por estado*/
function setfilter() {
  if (window.fetchUsuarios) {
    window.fetchUsuarios();
  }
}

/*Función para limpiar filtros de fecha*/
function limpiarFechas() {
  document.getElementById("fecha_desde").value = "";
  document.getElementById("fecha_hasta").value = "";
  setfilter();
}

/*Botón para desactivar usuarios*/
function btnDesUsuario(id) {
  Swal.fire({
    title: "Está seguro de desactivar el usuario?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "usuarios/desactivar/" + id;
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

/*Botón para activar usuarios*/
function btnActUsuario(id) {
  Swal.fire({
    title: "Activar usuario?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "usuarios/activar/" + id;
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
      "usuarios/reportePDF?estado=" +
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
