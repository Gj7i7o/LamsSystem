/*Modal para registrar entrada*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalEntrada");
const modalDetalle = document.getElementById("modalDetalleEntrada");
const btn = document.getElementById("registrarEntrada");
const span = document.getElementsByClassName("close")[0];
const spanDetalle = document.getElementsByClassName("close-detalle")[0];
const form = document.getElementById("formularioEntrada");
const btnAddLine = document.getElementById("addLine");
let idx = 0;
let modoEdicion = false;
const dataForm = { lines: [] };
const formLine = ` <div class="input_form" id="line_idx_${idx}">
                    <input type="number" name="lines[${idx}][id]" class="input_form_input id" hidden="true">
                    <div style="position: relative;"> 
                        <label>Codigo</label>
                        <input type="text" name="lines[${idx}][codigo]" oninput="buscarProducto(this, ${idx})" class="input_form_input codigo_producto" autocomplete="off" 
                        placeholder="Código producto">
                      <div id="lista_productos_${idx}" class="autocomplete-items"></div>
                    </div>
                    <div>
                      <label>Producto</label>
                      <input type="number" name="lines[${idx}][producto]" class="input_form_input producto_id" hidden="true">
                      <input type="text" class="input_form_input producto_nombre" disabled placeholder="Nombre del producto">
                    </div>

                    <div>
                        <label>P. Costo</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${idx})" name="lines[${idx}][precioCosto]" min="0.00" class="input_form_input precioCosto" placeholder="1.00$">
                    </div>

                    <div>
                        <label>P. Venta</label>
                        <input type="number" step="0.01" name="lines[${idx}][precioVenta]" min="0.00" class="input_form_input precioVenta" placeholder="1.00$">
                    </div>

                    <div>
                        <label>Cantidad</label>
                        <input type="number" onchange="getSubTotal(${idx})" name="lines[${idx}][cantidad]" min="1" class="input_form_input cantidad" placeholder="1">
                    </div>

                    <div>
                        <label>Sub-total</label>
                        <input type="number" step="0.01" disabled name="lines[${idx}][subTotal]" value="0.00" min="0.00" class="input_form_input subTotal">
                    </div>

                    <div class="buttonToLine">
                        <button class="button" type="button" onclick="deleteLine(${idx});"><i class="fas fa-trash"></i></button>
                    </div>
                    </div>`;

const newFormLine = (index) => `
                    <input type="number" name="lines[${index}][id]" class="input_form_input id" hidden="true">
                    <div style="position: relative;"> 
                        <label>Codigo</label>
                        <input type="text" name="lines[${index}][codigo]" oninput="buscarProducto(this, ${index})" class="input_form_input codigo_producto" autocomplete="off" 
                        placeholder="Código producto">
                      <div id="lista_productos_${index}" class="autocomplete-items"></div>
                    </div>
                    <div>
                      <label>Producto</label>
                      <input type="number" name="lines[${index}][producto]" class="input_form_input producto_id" hidden="true">
                      <input type="text" class="input_form_input producto_nombre" disabled placeholder="Nombre del producto">
                    </div>

                    <div>
                        <label>P. Costo</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${index})" name="lines[${index}][precioCosto]" min="0.00" class="input_form_input precioCosto" placeholder="1.00$">
                    </div>

                    <div>
                        <label>P. Venta</label>
                        <input type="number" step="0.01" name="lines[${index}][precioVenta]" min="0.00" class="input_form_input precioVenta" placeholder="1.00$">
                    </div>

                    <div>
                        <label>Cantidad</label>
                        <input type="number" onchange="getSubTotal(${index})" name="lines[${index}][cantidad]" min="1" class="input_form_input cantidad" placeholder="1">
                    </div>

                    <div>
                        <label>Sub-total</label>
                        <input type="number" step="0.01" disabled name="lines[${index}][subTotal]" value="0.00" min="0.00" class="input_form_input subTotal">
                    </div>`;

// Variable global para evitar múltiples peticiones seguidas (Debounce)
let timeoutSearch = null;
// Línea activa para asignar el producto recién creado
let lineaActivaParaProducto = null;

async function buscarProducto(inputElement, lineIdx) {
  // 1. Identificar elementos RELATIVOS a la línea (Más seguro)
  // Buscamos el div padre de la línea actual
  // Asegúrate que tu fila tenga una clase o ID identificable, ej: id="line_idx_0"
  const fila =
    document.getElementById(`line_idx_${lineIdx}`) ||
    inputElement.closest(".linea-producto") ||
    inputElement.parentNode.parentNode;

  // Buscamos los inputs dentro de esa fila específica
  const hiddenId =
    fila.querySelector(`input[name="lines[${lineIdx}][id]"]`) ||
    fila.querySelector(".producto_id"); // Añade clase .producto_id al hidden si falla el name
  const inputNombre = fila.querySelector(".producto_nombre");
  const hiddenProductoId = fila.querySelector(
    `input[name="lines[${lineIdx}][producto]"]`,
  );

  // 2. Manejo del contenedor de resultados
  let wrapper = inputElement.parentNode; // El div con position: relative
  let lista = wrapper.querySelector(".autocomplete-items");

  // Si no existe la lista, la creamos
  if (!lista) {
    lista = document.createElement("div");
    lista.className = "autocomplete-items";
    // Estilos forzados por JS para asegurar visibilidad
    lista.style.position = "absolute";
    lista.style.zIndex = "1000";
    lista.style.width = "100%";
    wrapper.appendChild(lista);
  }

  const codigo = inputElement.value.trim();

  // 3. Limpiar si está vacío
  if (codigo.length === 0) {
    lista.innerHTML = "";
    lista.style.display = "none";
    return;
  }

  // 4. Debounce (Esperar a que termine de escribir 300ms)
  clearTimeout(timeoutSearch);
  timeoutSearch = setTimeout(async () => {
    try {
      console.log("Buscando:", codigo); // Para depurar

      const response = await fetch(
        APP_URL +
          "productos/buscarPorCodigo?codigo=" +
          encodeURIComponent(codigo),
      );

      if (!response.ok) throw new Error("Error de red");

      const productos = await response.json();
      console.log("Resultados:", productos); // Ver resultados en consola

      lista.innerHTML = "";
      lista.style.display = "block";

      if (productos.length > 0) {
        productos.forEach((prod) => {
          const item = document.createElement("div");
          item.className = "autocomplete-item";
          item.style.padding = "8px";
          item.style.cursor = "pointer";
          item.style.borderBottom = "1px solid #eee";
          item.style.backgroundColor = "#fff";

          item.innerHTML = `<b>${prod.codigo}</b> - ${prod.nombre}`;

          // Al hacer click
          item.onclick = function () {
            inputElement.value = prod.codigo; // Pone el código
            if (inputNombre) inputNombre.value = prod.nombre; // Pone el nombre
            if (hiddenProductoId) hiddenProductoId.value = prod.id; // ID del producto

            // Ojo: el input 'lines[...][id]' suele ser para el ID de la tabla detalle (base de datos),
            // si es una línea nueva suele ir vacío o con un ID temporal.
            // Si necesitas el ID del producto ahí también:
            if (hiddenId) hiddenId.value = prod.id;

            lista.innerHTML = "";
            lista.style.display = "none";
          };
          lista.appendChild(item);
        });
      } else {
        const noEncontrado = document.createElement("div");
        noEncontrado.style.padding = "8px";
        noEncontrado.style.cursor = "pointer";
        noEncontrado.style.color = "#d33";
        noEncontrado.style.backgroundColor = "#fff";
        noEncontrado.style.borderBottom = "1px solid #eee";
        noEncontrado.innerHTML = `<i class="fas fa-exclamation-triangle"></i> "<b>${codigo}</b>" no encontrado - Click para registrar`;
        noEncontrado.onclick = function () {
          lista.innerHTML = "";
          lista.style.display = "none";
          mostrarModalProductoNoExiste(codigo, lineIdx);
        };
        lista.appendChild(noEncontrado);
      }
    } catch (error) {
      console.error("Error JS:", error);
    }
  }, 300); // Retraso de 300ms
}

// Cerrar listas al hacer click fuera
document.addEventListener("click", function (e) {
  if (!e.target.matches(".codigo")) {
    document
      .querySelectorAll(".autocomplete-items")
      .forEach((el) => (el.innerHTML = ""));
  }
});

// async function buscarProducto(lineIdx) {
//   const input = document.querySelector(
//     `input[name="lines[${lineIdx}][codigo_producto]"]`,
//   );
//   const hiddenId = document.querySelector(
//     `input[name="lines[${lineIdx}][producto]"]`,
//   );
//   const lineDiv = document.getElementById("line_idx_" + lineIdx);
//   const nombreInput = lineDiv
//     ? lineDiv.querySelector(".producto_nombre")
//     : null;
//   const codigo = input ? input.value.trim() : "";

//   if (codigo === "") {
//     if (hiddenId) hiddenId.value = "";
//     if (nombreInput) nombreInput.value = "";
//     return;
//   }

//   try {
//     const response = await fetch(
//       APP_URL +
//         "productos/buscarPorCodigo?codigo=" +
//         encodeURIComponent(codigo),
//     );
//     const res = await response.json();
//     if (res.encontrado) {
//       hiddenId.value = res.id;
//       if (nombreInput) nombreInput.value = res.nombre.toUpperCase();
//     } else {
//       hiddenId.value = "";
//       if (nombreInput) nombreInput.value = "";
//       alertas("El producto no existe", "warning");
//       input.value = "";
//     }
//   } catch (error) {
//     console.error("Error al buscar producto:", error);
//   }
// }

// Variables globales de los elementos
const inputRif = document.getElementById("proveedor_rif");
const listaResultados = document.getElementById("lista_busqueda_proveedor");
const hiddenId = document.getElementById("proveedor_id");
const nombreInput = document.getElementById("proveedor_nombre");

// Escuchar cuando el usuario escribe
inputRif.addEventListener("input", function () {
  let valor = this.value;

  // Limpiar lista si está vacío
  if (!valor) {
    cerrarLista();
    limpiarCampos();
    return;
  }

  // Petición al servidor
  fetch(`${APP_URL}proveedores/buscarPorRif?rif=${encodeURIComponent(valor)}`)
    .then((response) => response.json())
    .then((data) => {
      // Limpiamos la lista actual antes de llenarla
      cerrarLista();

      if (data.length > 0) {
        // Creamos los elementos de la lista
        data.forEach((prov) => {
          // Crear el DIV que simula la opción del select
          let item = document.createElement("div");

          // Texto que se muestra: RIF - NOMBRE
          item.innerHTML = `<strong>${prov.rif}</strong> - ${prov.nombre}`;

          // Input oculto para guardar el valor real de este item
          item.dataset.id = prov.id;
          item.dataset.rif = prov.rif;
          item.dataset.nombre = prov.nombre;

          // Evento al hacer click en una opción
          item.addEventListener("click", function () {
            // Llenar los campos del formulario
            inputRif.value = this.dataset.rif;
            hiddenId.value = this.dataset.id;
            nombreInput.value = this.dataset.nombre;

            // Cerrar la lista
            cerrarLista();
          });

          listaResultados.appendChild(item);
        });
        listaResultados.style.display = "block";
      }
    })
    .catch((error) => console.error("Error:", error));
});

// Función para cerrar la lista
function cerrarLista() {
  listaResultados.innerHTML = "";
  listaResultados.style.display = "none";
}

// Función para limpiar campos ocultos si el usuario borra el texto
function limpiarCampos() {
  hiddenId.value = "";
  nombreInput.value = "";
}

// Cerrar la lista si se hace clic fuera del componente
document.addEventListener("click", function (e) {
  if (e.target !== inputRif) {
    cerrarLista();
  }
});

async function getListadoProveedor() {
  const response = await fetch(
    "http://localhost/LamsSystem/proveedores/getSelect",
  );
  const { data: opciones } = await response.json();
  const select = document.getElementById("proveedor");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  await opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `
    <option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
}

btnAddLine.onclick = function () {
  idx++;
  const newLineHtml =
    `<div class="input_form" id="line_idx_${idx}">` +
    newFormLine(idx) +
    ` <div class="buttonToLine">
        <button class="button" type="button" onclick="deleteLine(${idx});"><i class="fas fa-trash"></i></button>
      </div>
    </div>`;
  form.insertAdjacentHTML("beforeend", newLineHtml);
};

// Al presionar Enter en el formulario, agregar nueva línea en lugar de enviar
form.addEventListener("keydown", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    btnAddLine.click();
  }
});

function deleteLine(idx) {
  const line = document.getElementById("line_idx_" + idx);
  line.innerHTML = "";
  console.log("Identificador de linea: ", idx);
}

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  // getListadoProveedor();
  document.getElementById("title").innerHTML = "Registrar Entrada";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  document.getElementById("codigo").value = "";
  document.getElementById("proveedor_id").value = "";
  document.getElementById("proveedor_rif").value = "";
  document.getElementById("proveedor_nombre").value = "";
  document.getElementById("tipo_pago").value = "contado";
  document.getElementById("total").value = "0.00";
  modoEdicion = false;
  idx = 0;
  form.innerHTML = formLine;
  modal.style.display = "block";
};

// Cuando el usuario hace clic en la <span> (x), cierra el modal
span.onclick = function () {
  modal.style.display = "none";
};

// Cerrar modal de detalle
if (spanDetalle) {
  spanDetalle.onclick = function () {
    modalDetalle.style.display = "none";
  };
}

/*Función para ver detalle de una entrada*/
function btnVerDetalleEntrada(id) {
  const url = APP_URL + "entradas/verDetalle/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      const cabecera = res.cabecera;
      const detalle = res.detalle;

      // Llenar datos de cabecera
      document.getElementById("detalle_proveedor").textContent =
        cabecera.proveedor_nombre || "N/A";
      document.getElementById("detalle_usuario").textContent =
        cabecera.usuario_nombre || "N/A";
      document.getElementById("detalle_tipo_pago").textContent =
        cabecera.tipo_pago === "contado" ? "Contado" : "Crédito";
      document.getElementById("detalle_codigo").textContent =
        cabecera.cod_docum;
      document.getElementById("detalle_fecha").textContent = cabecera.fecha;
      document.getElementById("detalle_hora").textContent = cabecera.hora;
      document.getElementById("detalle_total").textContent =
        cabecera.total + "$";

      // Llenar tabla de detalle
      const tbody = document.getElementById("detalleEntradaBody");
      tbody.innerHTML = "";
      detalle.forEach((item) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${item.producto_nombre} (${item.producto_codigo})</td>
          <td>${item.cantidad}</td>
          <td>${item.precio}$</td>
          <td>${item.precioVenta || 0}$</td>
          <td>${item.sub_total}$</td>
        `;
        tbody.appendChild(row);
      });

      modalDetalle.style.display = "block";
    }
  };
}

/*Función para editar una entrada*/
function btnEditEntrada(id) {
  document.getElementById("title").innerHTML = "Editar Entrada";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  modoEdicion = true;

  const url = APP_URL + "entradas/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = async function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      const cabecera = res.cabecera;
      const detalle = res.detalle;

      // Cargar lista de proveedores
      await getListadoProveedor();

      // Llenar datos de cabecera
      document.getElementById("id").value = cabecera.id;
      document.getElementById("codigo").value = cabecera.cod_docum;
      document.getElementById("proveedor").value = cabecera.idproveedor;
      document.getElementById("tipo_pago").value = cabecera.tipo_pago;
      document.getElementById("total").value = cabecera.total;

      // Limpiar y crear líneas de detalle
      form.innerHTML = "";
      idx = 0;

      detalle.forEach((item, index) => {
        idx = index;
        const lineHtml = `<div class="input_form" id="line_idx_${index}">
          <div>
            <input name="lines[${index}][id]" value="id${index}" hidden="true">
            <input name="lines[${index}][producto]" type="hidden" class="producto_id" value="${item.idproducto}">
            <label>Codigo</label>
            <input type="text" name="lines[${index}][codigo_producto]" onblur="buscarProducto(${index})" class="input_form_input codigo_producto" placeholder="Código producto" value="${item.producto_codigo || ""}">
          </div>
          <div>
            <label>Producto</label>
            <input type="text" class="input_form_input producto_nombre" disabled placeholder="Nombre del producto" value="${(item.producto_nombre || "").toUpperCase()}">
          </div>
          <div>
            <label>P. Costo</label>
            <input type="number" step="0.01" onchange="getSubTotal(${index})" name="lines[${index}][precioCosto]" min="0.00" class="input_form_input precioCosto" placeholder="1.00$" value="${item.precio}">
          </div>
          <div>
            <label>P. Venta</label>
            <input type="number" step="0.01" name="lines[${index}][precioVenta]" min="0.00" class="input_form_input precioVenta" placeholder="1.00$" value="${item.precioVenta || ""}">
          </div>
          <div>
            <label>Cantidad</label>
            <input type="number" onchange="getSubTotal(${index})" name="lines[${index}][cantidad]" min="1" class="input_form_input cantidad" placeholder="1" value="${item.cantidad}">
          </div>
          <div>
            <label>Sub-total</label>
            <input type="number" step="0.01" disabled name="lines[${index}][subTotal]" value="${item.sub_total}" min="0.00" class="input_form_input subTotal">
          </div>
          <div class="buttonToLine">
            <button class="button" type="button" onclick="deleteLine(${index});"><i class="fas fa-trash"></i></button>
          </div>
        </div>`;
        form.insertAdjacentHTML("beforeend", lineHtml);
      });

      modal.style.display = "block";
    }
  };
}

function fecha() {
  const fecha = new Date();
  return fecha.toLocaleDateString();
}

function hora() {
  const hora = new Date();
  return hora.toLocaleTimeString();
}

function limpiarFormulario() {
  document.getElementById("proveedor_id").value = "";
  document.getElementById("proveedor_rif").value = "";
  document.getElementById("proveedor_nombre").value = "";
  document.getElementById("codigo").value = "";
  document.getElementById("total").value = "";
  document.getElementById("tipo_pago").value = "contado";
}

const formulario = document.getElementById("formularioEntradas");

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  let data = {
    id: document.getElementById("id").value || "",
    proveedor: document.getElementById("proveedor_id").value || "",
    codigo: "",
    fecha: fecha(),
    hora: hora(),
    lineas: [],
    total: "",
    tipo_pago: "",
    usuario: idusuario,
  };

  // Selecciona todos los divs que representan una línea de formulario
  const lineasDelFormulario = formulario.querySelectorAll(".input_form");
  const proveedor = document.getElementById("proveedor_id");
  const codigo = document.getElementById("codigo");
  const total = document.getElementById("total");

  lineasDelFormulario.forEach((lineaDiv) => {
    const id = lineaDiv.querySelector('input[name*="[id]"]')?.value;
    const producto = lineaDiv.querySelector('input[name*="[producto]"]')?.value;
    const cantidad = lineaDiv.querySelector('input[name*="[cantidad]"]')?.value;
    const precioCosto = lineaDiv.querySelector(
      'input[name*="[precioCosto]"]',
    )?.value;
    const precioVenta = lineaDiv.querySelector(
      'input[name*="[precioVenta]"]',
    )?.value;
    const subTotal = lineaDiv.querySelector('input[name*="[subTotal]"]')?.value;
    if (!id || !producto || !cantidad) {
      console.log(
        "Error, datos nulos",
        "Id: ",
        id,
        "Producto: ",
        producto,
        "Cantidad: ",
        cantidad,
      );
    } else {
      data.lineas.push({
        id: id,
        producto: producto,
        precioCosto: precioCosto,
        precioVenta: precioVenta,
        cantidad: parseInt(cantidad),
        subTotal: subTotal,
      });
    }
  });
  data.codigo = codigo.value;
  data.proveedor = proveedor.value;
  data.total = total.value;
  data.tipo_pago = document.getElementById("tipo_pago").value;

  // Validar precios
  let precioInvalido = data.lineas.some(
    (linea) => parseFloat(linea.precioCosto) <= 0 || !linea.precioCosto,
  );
  let precioVentaInvalido = data.lineas.some(
    (linea) => parseFloat(linea.precioVenta) <= 0 || !linea.precioVenta,
  );
  // Validar que precioVenta no sea inferior a precioCosto
  let ventaMenorCosto = data.lineas.some(
    (linea) => parseFloat(linea.precioVenta) < parseFloat(linea.precioCosto),
  );
  // Validar que líneas con el mismo producto tengan el mismo precioVenta
  let precioVentaInconsistente = false;
  const preciosPorProducto = {};
  data.lineas.forEach((linea) => {
    if (preciosPorProducto[linea.producto] !== undefined) {
      if (preciosPorProducto[linea.producto] !== linea.precioVenta) {
        precioVentaInconsistente = true;
      }
    } else {
      preciosPorProducto[linea.producto] = linea.precioVenta;
    }
  });

  if (data.codigo == "" || data.proveedor == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (precioInvalido) {
    alertas("El precio de costo debe ser mayor a 0", "warning");
  } else if (precioVentaInvalido) {
    alertas("El precio de venta debe ser mayor a 0", "warning");
  } else if (ventaMenorCosto) {
    alertas(
      "El precio de venta no puede ser inferior al precio de costo",
      "warning",
    );
  } else if (precioVentaInconsistente) {
    alertas(
      "Las líneas con el mismo producto deben tener el mismo precio de venta",
      "warning",
    );
  } else {
    const enviarFormulario = () => {
      const url = APP_URL + "entradas/registrar";
      const http = new XMLHttpRequest();

      http.open("POST", url, true);

      http.setRequestHeader("Content-Type", "application/json");

      http.onreadystatechange = function () {
        if (this.readyState == 4) {
          if (this.status == 200) {
            const res = JSON.parse(this.responseText);
            if (res.icono != "success") {
              alertas(res.msg, res.icono);
            } else {
              alertas(res.msg, res.icono);
              modal.style.display = "none";
              limpiarFormulario();
              recargarVista();
            }
          } else {
            console.error("Error en el servidor:", this.responseText);
          }
        }
      };

      http.send(JSON.stringify(data));
    };
    if (modoEdicion) {
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

function getSubTotal(idx) {
  let precio = document.querySelector(
    'input[name="lines[' + idx + '][precioCosto]"]',
  )?.value;
  let cantidad = document.querySelector(
    'input[name="lines[' + idx + '][cantidad]"]',
  )?.value;
  let subTotal = document.querySelector(
    'input[name="lines[' + idx + '][subTotal]"]',
  );

  subTotal.value = (cantidad ?? 0) * (precio ?? 0);
  getTotal();
}

function getTotal() {
  const subTotales = document.querySelectorAll(
    'input[name^="lines["][name$="][subTotal]"]',
  );
  let totalGeneral = 0;

  subTotales.forEach((input) => {
    totalGeneral += parseFloat(input.value) || 0;
  });

  const total = document.getElementById("total");
  if (total) {
    total.value = totalGeneral.toFixed(2);
  }
}

// === Flujo de producto no existente ===

function mostrarModalProductoNoExiste(codigoBuscado, lineIdx) {
  Swal.fire({
    title: "Producto no encontrado",
    text: `El producto con código "${codigoBuscado}" no existe. ¿Desea registrarlo?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Registrar producto",
    cancelButtonText: "Cerrar",
  }).then((result) => {
    if (result.isConfirmed) {
      lineaActivaParaProducto = lineIdx;
      abrirModalNuevoProducto(codigoBuscado);
    }
  });
}

async function cargarCategoriasNP() {
  const response = await fetch(APP_URL + "categorias/getSelect");
  const { data: opciones } = await response.json();
  const select = document.getElementById("np_categoria");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `<option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>`;
  });
  select.innerHTML = opcionesHtml;
}

async function cargarMarcasNP() {
  const response = await fetch(APP_URL + "marcas/getSelect");
  const { data: opciones } = await response.json();
  const select = document.getElementById("np_marca");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `<option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>`;
  });
  select.innerHTML = opcionesHtml;
}

function abrirModalNuevoProducto(codigoPrellenado) {
  // Cargar selects
  cargarCategoriasNP();
  cargarMarcasNP();

  // Limpiar formulario
  document.getElementById("formularioNuevoProducto").reset();

  // Pre-llenar el código buscado
  document.getElementById("np_codigo").value = codigoPrellenado || "";

  // Mostrar modal
  document.getElementById("modalNuevoProducto").style.display = "block";
}

// Cerrar modal de nuevo producto
const spanNuevoProducto = document.querySelector(".close-nuevo-producto");
if (spanNuevoProducto) {
  spanNuevoProducto.onclick = function () {
    document.getElementById("modalNuevoProducto").style.display = "none";
  };
}

// Envío del formulario de nuevo producto
const formularioNuevoProducto = document.getElementById("formularioNuevoProducto");
formularioNuevoProducto.addEventListener("submit", function (e) {
  e.preventDefault();

  const codigo = document.getElementById("np_codigo").value.trim();
  const nombre = document.getElementById("np_nombre").value.trim();
  const precioVenta = document.getElementById("np_precioVenta").value;
  const categoria = document.getElementById("np_categoria").value;
  const marca = document.getElementById("np_marca").value;

  if (!codigo || !nombre || !precioVenta || !categoria || !marca) {
    alertas("Todos los campos obligatorios deben estar completos", "warning");
    return;
  }

  const url = APP_URL + "productos/registrar";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(new FormData(formularioNuevoProducto));
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.icono !== "success") {
        alertas(res.msg, res.icono);
      } else {
        alertas(res.msg, res.icono);
        document.getElementById("modalNuevoProducto").style.display = "none";

        // Buscar el producto recién creado para obtener su ID y asignarlo a la línea
        if (lineaActivaParaProducto !== null) {
          asignarProductoRecienCreado(codigo, nombre, lineaActivaParaProducto);
        }
      }
    }
  };
});

async function asignarProductoRecienCreado(codigo, nombre, lineIdx) {
  try {
    const response = await fetch(
      APP_URL + "productos/buscarPorCodigo?codigo=" + encodeURIComponent(codigo),
    );
    const productos = await response.json();

    // Buscar coincidencia exacta por código
    const producto = productos.find(
      (p) => p.codigo.toLowerCase() === codigo.toLowerCase(),
    );

    if (producto) {
      const fila = document.getElementById(`line_idx_${lineIdx}`);
      if (fila) {
        const inputCodigo = fila.querySelector(`input[name="lines[${lineIdx}][codigo]"]`);
        const inputNombre = fila.querySelector(".producto_nombre");
        const hiddenProductoId = fila.querySelector(`input[name="lines[${lineIdx}][producto]"]`);
        const hiddenId = fila.querySelector(`input[name="lines[${lineIdx}][id]"]`);

        if (inputCodigo) inputCodigo.value = producto.codigo;
        if (inputNombre) inputNombre.value = producto.nombre;
        if (hiddenProductoId) hiddenProductoId.value = producto.id;
        if (hiddenId) hiddenId.value = producto.id;
      }
    }
  } catch (error) {
    console.error("Error al asignar producto recién creado:", error);
  } finally {
    lineaActivaParaProducto = null;
  }
}
