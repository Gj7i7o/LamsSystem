/*
Script de Historial: Sección de JavaScript propio para las funciones del módulo
de historial de acciones de usuarios.
*/

//Tabla Historial
document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#TablaHistorial tbody");
    const headers = document.querySelectorAll("#TablaHistorial th");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const pageInfo = document.getElementById("pageInfo");

    let paginaActual = 1;
    const rowsPerPage = 10;
    let currentData = [];
    let currentTotal = 0;

    // Cargar selects de filtros
    cargarModulos();
    cargarUsuarios();

    // Función para cargar los módulos en el select
    async function cargarModulos() {
        try {
            const response = await fetch(APP_URL + "historial/getModulos");
            const { data } = await response.json();
            const select = document.getElementById("modulo");

            data.forEach((item) => {
                const option = document.createElement("option");
                option.value = item.modulo;
                option.textContent = item.modulo;
                select.appendChild(option);
            });
        } catch (error) {
            console.error("Error cargando módulos:", error);
        }
    }

    // Función para cargar los usuarios en el select
    async function cargarUsuarios() {
        try {
            const response = await fetch(APP_URL + "historial/getUsuarios");
            const { data } = await response.json();
            const select = document.getElementById("usuario");

            data.forEach((item) => {
                const option = document.createElement("option");
                option.value = item.id;
                option.textContent = item.usuario;
                select.appendChild(option);
            });
        } catch (error) {
            console.error("Error cargando usuarios:", error);
        }
    }

    // Función para obtener los datos del servidor
    async function tablaHistorial() {
        try {
            let modulo = document.getElementById("modulo");
            let usuario = document.getElementById("usuario");
            let query = document.getElementById("query");

            let fecha_desde = document.getElementById("fecha_desde");
            let fecha_hasta = document.getElementById("fecha_hasta");
            const params = new URLSearchParams({
                page: paginaActual,
                modulo: modulo?.value || "todo",
                usuario: usuario?.value || "todo",
                query: query?.value || "",
                fecha_desde: fecha_desde?.value || "",
                fecha_hasta: fecha_hasta?.value || "",
            });

            const response = await fetch(
                APP_URL + "historial/listar?" + params
            );

            if (response.error) {
                throw new Error(`Error en la solicitud: ${response.error}`);
            }

            const { data, total } = await response.json();
            currentData = [...data];
            currentTotal = total;

            mostrarTabla();
        } catch (error) {
            console.error("No se pudo obtener la data:", error);
        }
    }

    // Exponer la función para poder llamarla desde setfilter
    window.fetchHistorial = tablaHistorial;

    function mostrarTabla() {
        tableBody.innerHTML = "";
        const paginatedData = currentData;

        if (paginatedData.length === 0) {
            tableBody.innerHTML =
                '<tr><td colspan="6">No hay registros disponibles.</td></tr>';
            updatePaginationInfo();
            return;
        }

        paginatedData.forEach((item) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.usuario}</td>
                <td>${item.modulo}</td>
                <td>${item.accion}</td>
                <td>${item.descripcion}</td>
                <td>${item.fecha}</td>
                <td>${item.hora}</td>
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
            await tablaHistorial();
            mostrarTabla();
        }
    });

    nextBtn.addEventListener("click", async () => {
        const totalPages = getTotalPages();
        if (paginaActual < totalPages) {
            paginaActual++;
            await tablaHistorial();
            mostrarTabla();
        }
    });

    // Llama a la función para iniciar el proceso
    tablaHistorial();
});

/*Función para filtrar*/
function setfilter() {
    if (window.fetchHistorial) {
        window.fetchHistorial();
    }
}

/*Función para limpiar filtros de fecha*/
function limpiarFechas() {
  document.getElementById("fecha_desde").value = "";
  document.getElementById("fecha_hasta").value = "";
  setfilter();
}

/*Función para descargar reporte PDF*/
function descargarPDF() {
    const modulo = document.getElementById("modulo")?.value || "todo";
    const usuario = document.getElementById("usuario")?.value || "todo";
    const query = document.getElementById("query")?.value || "";
    const fecha_desde = document.getElementById("fecha_desde")?.value || "";
    const fecha_hasta = document.getElementById("fecha_hasta")?.value || "";
    window.open(APP_URL + "historial/reportePDF?modulo=" + modulo + "&usuario=" + usuario + "&query=" + query + "&fecha_desde=" + fecha_desde + "&fecha_hasta=" + fecha_hasta, "_blank");
}
