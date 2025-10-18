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
  const rowsPerPage = 5;
  let currentData = [];
  let currentTotal = 0;

  // 2. Función para obtener los datos del servidor
  async function tablaUsuarios() {
    try {
      // Reemplaza '/api/productos' con la URL real de tu backend
      const response = await fetch(
        "http://localhost/LamsSystem/Usuarios/list?page=" + paginaActual
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
      // Puedes mostrar un mensaje de error al usuario aquí
    }
  }

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
                <td>${item.nombre}</td>
                <td>${item.apellido}</td>
                <td>${item.correo}</td>
                <td>${item.telef}</td>
                <td>${item.rango}</td>
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

/*Botón para desactivar proveedores*/
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
      const url = APP_URL + "Usuarios/desactivar/" + id;
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

/*Modal para registrar usuario*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalUsuario");
const btn = document.getElementById("registrarUsuario");
const span = document.getElementsByClassName("close")[0];

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  document.getElementById("title").innerHTML = "Registrar Usuario";
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
  document.getElementById("usuario").value = "";
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
  document.getElementById("correo").value = "";
  document.getElementById("telef").value = "";
}

/*Botón de editar usuario*/
function btnEditUsuario(id) {
  document.getElementById("title").innerHTML = "Actualizar Usuario";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Usuarios/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("usuario").value = res.usuario;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("apellido").value = res.apellido;
      document.getElementById("correo").value = res.correo;
      document.getElementById("telef").value = res.telef;
      modal.style.display = "block";
    }
  };
}

// Manejar el envío del formulario (opcional)
userForm.addEventListener("submit", function (event) {
  event.preventDefault(); // Detiene el envío real del formulario

  const user = document.getElementById("usuario");
  const name = document.getElementById("nombre");
  const ape = document.getElementById("apellido");
  const email = document.getElementById("correo");
  const telef = document.getElementById("telef");
  const password = document.getElementById("password");
  let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]+$/;
  let pass =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/;
  let correo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (
    user.value == "" ||
    name.value == "" ||
    ape.value == "" ||
    email.value == "" ||
    telef.value == ""
  ) {
    alertas("Todos los campos SON obligatorios", "warning");
  } else if (letras.test(name)) {
    alertas("No agregue caracteres indevidos en su nombre", "warning");
  } else if (letras.test(ape)) {
    alertas("No agregue caracteres indevidos en su apellido", "warning");
  } else if (pass.test(password)) {
    alertas("La contraseña NO cumple con las especificaciones", "warning");
  } else if (correo.test(email)) {
    alertas("Escriba correctamente el correo", "warning");
  } else {
    const url = APP_URL + "Usuarios/store";
    const frm = document.getElementById("userForm");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        alertas(res.msg, res.icono);
        modal.style.display = "none";
      }
    };
  }
});
