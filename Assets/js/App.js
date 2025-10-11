/*
App.js: Se encarga de una segunda validación de datos, además del PHP. 
Ayuda con otras interacciones, como los modales, el uso del DataTable para mostrar los datos de los módulos,
y las alertas del sistema.
*/

//Tabla Proveedores
document.addEventListener("DOMContentLoaded", () => {
  // 1. Variable para almacenar los datos de la base de datos
  let data = [];

  const tableBody = document.querySelector("#TablaProveedores tbody");
  const headers = document.querySelectorAll("#TablaProveedores th");
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
        "http://localhost/miversion/Proveedores/list?page=" + currentPage
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
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedData = currentData.slice(start, end);

    if (paginatedData.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="6">No hay datos disponibles.</td></tr>';
      updatePaginationInfo();
      return;
    }

    paginatedData.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${item.nombre}</td>
                <td>${item.apellido}</td>
                <td>${item.rif}</td>
                <td>${item.direccion}</td>
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

/*Modal para registrar usuario*/
// Obtener los elementos del DOM
const modal = document.getElementById("miModalUsuario");
const btn = document.getElementById("abrirModalBtn");
const span = document.getElementsByClassName("close")[0];
const userForm = document.getElementById("userForm");

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  modal.style.display = "block";
};

// Cuando el usuario hace clic en la <span> (x), cierra el modal
span.onclick = function () {
  modal.style.display = "none";
};

// Cuando el usuario hace clic fuera del modal, también lo cierra
// window.onclick = function(event) {
//   if (event.target == modal) {
//     modal.style.display = "none";
//   }
// }

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
        modal.style.display = "none"; // Cierra el modal después de la "simulación" de registro
        userForm.reset(); // Opcional: Limpiar el formulario
      }
    };
  }
});

/*Botón de editar usuario*/
function btnEditUser(id) {
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
      document.getElementById("user").value = res.usuario;
      document.getElementById("name").value = res.nombre;
      document.getElementById("ape").value = res.apellido;
      document.getElementById("email").value = res.correo;
      document.getElementById("telef").value = res.telef;
      // document.getElementById("password").value = res.password;
      // document.getElementById("confirm").value = res.confirm;
      document.getElementById("passwords").classList.add("d-none");
      $("#new_user").modal("show");
    }
  };
}

/*Botón de eliminar usuario*/
function btnDelUser(id) {
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
      const url = APP_URL + "Usuarios/destroy/" + id;
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

/*Contenido de la tabla producto*/
let tblProduct;
document.addEventListener("DOMContentLoaded", function () {
  tblProduct = $("#tblProduct").DataTable({
    ajax: {
      url: APP_URL + "Productos/list",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "codigo",
      },
      {
        data: "nombre",
      },
      {
        data: "precio",
      },
      {
        data: "cantidad",
      },
      {
        data: "categoria",
      },
      {
        data: "marca",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Modal del producto*/
function frmProduct() {
  document.getElementById("title").innerHTML = "Registrar Producto";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("frmProduct").reset();
  $("#new_product").modal("show");
  document.getElementById("id").value = "";
}

/*Acción de registrar producto, validaciones y alertas*/
function registrarProduct(e) {
  e.preventDefault();
  const code = document.getElementById("code");
  const name = document.getElementById("name");
  const price = document.getElementById("price");
  const idcat = document.getElementById("idcat");
  const idmar = document.getElementById("idmar");
  if (
    code.value == "" ||
    name.value == "" ||
    price.value == "" ||
    idcat.value == "" ||
    idmar.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Productos/store";
    const frm = document.getElementById("frmProduct");
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

/*Botón de editar producto*/
function btnEditProduct(id) {
  document.getElementById("title").innerHTML = "Actualizar Producto";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Productos/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("code").value = res.codigo;
      document.getElementById("name").value = res.nombre;
      document.getElementById("price").value = res.precio;
      document.getElementById("idcat").value = res.idcategoria;
      document.getElementById("idmar").value = res.idmarca;
      $("#new_product").modal("show");
    }
  };
}

/*Botón de eliminar producto*/
function btnDelProduct(id) {
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

/*Contenido de la tabla categorias*/
let tblCategory;
document.addEventListener("DOMContentLoaded", function () {
  tblCategory = $("#tblCategory").DataTable({
    ajax: {
      url: APP_URL + "Categorias/list",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "nombre",
      },
      {
        data: "descrip",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Modal de la categoria*/
function frmCategory() {
  document.getElementById("title").innerHTML = "Registrar Categoria";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("frmCategory").reset();
  $("#new_category").modal("show");
  document.getElementById("id").value = "";
}

/*Acción de registrar categoria, validaciones y alertas*/
function registrarCategory(e) {
  e.preventDefault();
  const name = document.getElementById("name");
  const des = document.getElementById("des");
  if (name.value == "" || des.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Categorias/store";
    const frm = document.getElementById("frmCategory");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        $("#new_category").modal("hide");
        alertas(res.msg, res.icono);
        tblCategory.ajax.reload();
      }
    };
  }
}

/*Botón de editar categoria*/
function btnEditCategory(id) {
  document.getElementById("title").innerHTML = "Actualizar Categoria";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Categorias/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("name").value = res.nombre;
      document.getElementById("des").value = res.descrip;
      $("#new_category").modal("show");
    }
  };
}

/*Botón de eliminar categoria*/
function btnDelCategory(id) {
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
      const url = APP_URL + "Categorias/destroy/" + id;
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

/*Contenido de la tabla marcas*/
let tblMarca;
document.addEventListener("DOMContentLoaded", function () {
  tblMarca = $("#tblMarca").DataTable({
    ajax: {
      url: APP_URL + "Marcas/list",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "nombre",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Modal de la marca*/
function frmMarca() {
  document.getElementById("title").innerHTML = "Registrar Marca";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("frmMarca").reset();
  $("#new_marca").modal("show");
  document.getElementById("id").value = "";
}

/*Acción de registrar marca, validaciones y alertas*/
function registrarMarca(e) {
  e.preventDefault();
  const name = document.getElementById("name");
  if (name.value == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Marcas/store";
    const frm = document.getElementById("frmMarca");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        $("#new_marca").modal("hide");
        alertas(res.msg, res.icono);
        tblMarca.ajax.reload();
      }
    };
  }
}

/*Botón de editar marca*/
function btnEditMarca(id) {
  document.getElementById("title").innerHTML = "Actualizar Marca";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Marcas/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("name").value = res.nombre;
      $("#new_marca").modal("show");
    }
  };
}

/*Botón de eliminar marca*/
function btnDelMarca(id) {
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
      const url = APP_URL + "Marcas/destroy/" + id;
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

/*Contenido de la tabla proveedores*/
let tblProveedores;
document.addEventListener("DOMContentLoaded", function () {
  tblProveedores = $("#tblProveedores").DataTable({
    ajax: {
      url: APP_URL + "Proveedores/list",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "nombre",
      },
      {
        data: "apellido",
      },
      {
        data: "rif",
      },
      {
        data: "direccion",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Modal de proveedores*/
function frmProveedores() {
  document.getElementById("title").innerHTML = "Registrar Proveedor";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("frmProveedor").reset();
  $("#new_proveedor").modal("show");
  document.getElementById("id").value = "";
}

/*Acción de registrar proveedores, validaciones y alertas*/
function registrarProveedores(e) {
  e.preventDefault();
  const name = document.getElementById("name");
  const ape = document.getElementById("ape");
  const rif = document.getElementById("rif");
  const dir = document.getElementById("dir");
  if (
    name.value == "" ||
    ape.value == "" ||
    rif.value == "" ||
    dir.value == ""
  ) {
    alertas("Todos los campos son obligatorios", "warning");
  } else {
    const url = APP_URL + "Proveedores/store";
    const frm = document.getElementById("frmProveedor");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        $("#new_proveedor").modal("hide");
        alertas(res.msg, res.icono);
        tblProveedores.ajax.reload();
      }
    };
  }
}

/*Botón de editar proveedores*/
function btnEditProveedores(id) {
  document.getElementById("title").innerHTML = "Actualizar Proveedor";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Proveedores/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("name").value = res.nombre;
      document.getElementById("ape").value = res.apellido;
      document.getElementById("rif").value = res.rif;
      document.getElementById("dir").value = res.direccion;
      $("#new_proveedor").modal("show");
    }
  };
}

/*Botón de editar proveedores*/
function btnDelProveedores(id) {
  Swal.fire({
    title: "Está seguro de desactivar el proveedor?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = APP_URL + "Proveedores/destroy/" + id;
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

/*Contenido de la tabla Join*/
let tblJoin;
document.addEventListener("DOMContentLoaded", function () {
  tblJoin = $("#tblJoin").DataTable({
    ajax: {
      url: APP_URL + "Entradas/listProductEntrada",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "codigo",
      },
      {
        data: "nombre",
      },
      {
        data: "cantidad",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Contenido de la tabla Out*/
let tblOut;
document.addEventListener("DOMContentLoaded", function () {
  tblOut = $("#tblOut").DataTable({
    ajax: {
      url: APP_URL + "Salidas/listProductSalida",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "nombre",
      },
      {
        data: "cantidad",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Contenido de la tabla Entrada*/
let tblEntrada;
document.addEventListener("DOMContentLoaded", function () {
  tblEntrada = $("#tblEntrada").DataTable({
    ajax: {
      url: APP_URL + "Entradas/listEntrada",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "cantidad",
      },
      {
        data: "precio",
      },
      {
        data: "nombre",
      },
      {
        data: "fecha",
      },
      {
        data: "hora",
      },
      {
        data: "acciones",
      },
    ],
  });
});

/*Contenido de la tabla Salida*/
let tblSalida;
document.addEventListener("DOMContentLoaded", function () {
  tblSalida = $("#tblSalida").DataTable({
    ajax: {
      url: APP_URL + "Salidas/listSalida",
      dataSrc: "",
    },
    language: {
      url: APP_URL + "Assets/utils/es-mx.json",
    },
    columns: [
      {
        data: "cantidad",
      },
      {
        data: "precio",
      },
      {
        data: "nombre",
      },
      {
        data: "fecha",
      },
      {
        data: "hora",
      },
      {
        data: "acciones",
      },
    ],
  });
});

function frmCantidad() {
  document.getElementById("title").innerHTML = "Cantidad a ingresar";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("frmCantidad").reset();
  $("#sumar_cantidad").modal("show");
}

function btnAddCantidad(id) {
  document.getElementById("title").innerHTML = "Cantidad a ingresar";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  const url = APP_URL + "Entradas/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("cantidad").value = res.Cantidad;
      $("#sumar_cantidad").modal("show");
    }
  };
}

/*Botón de mostrar proveedores*/
function btnShowInfo(id) {
  document.getElementById("title").innerHTML = "Datos del Proveedor";
  const url = APP_URL + "Proveedores/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id").value = res.id;
      document.getElementById("name").value = res.nombre;
      document.getElementById("ape").value = res.apellido;
      document.getElementById("rif").value = res.rif;
      document.getElementById("dir").value = res.direccion;
      $("#show_proveedor").modal("show");
    }
  };
}

/*Función para mostrar alertas*/
function alertas(mensaje, icono) {
  Swal.fire({
    // position: "top-center",
    icon: icono,
    title: mensaje,
    showConfirmButton: false,
    timer: 3000,
  });
}
