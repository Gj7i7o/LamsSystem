/*Modal para registrar entrada*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalEntrada");
const modalDetalle = document.getElementById("modalDetalleEntrada");
const btn = document.getElementById("registrarEntrada");
const span = document.getElementsByClassName("close")[0];
const spanDetalle = document.getElementsByClassName("close-detalle")[0];
const form = document.getElementById("formularioEntrada");
const btnAddLine = document.getElementById("addLine");
let opcionesProducto = "";
let idx = 0;
let modoEdicion = false;
const dataForm = { lines: [] };
const formLine = ` <div class="input_form" id="line_idx_${idx}">
                    <div>
                      <input name="lines[${idx}][id]" value="id${idx}" hidden="true">
                      <label for="producto">Producto</label>
                      <select name="lines[${idx}][producto]" onchange="changeProducto(this)" id="producto${idx}" class="input_form_select producto">
                        ${opcionesProducto}
                      </select>
                    </div>

                    <div>
                        <label for="precio">PrecioUni</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${idx})" id="precio" name="lines[${idx}][precio]" min="0.00" class="input_form_input" placeholder="1.00$">
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" onchange="getSubTotal(${idx})" id="cantidad" name="lines[${idx}][cantidad]" min="1" class="input_form_input" placeholder="1">
                    </div>

                    <div>
                        <label for="subTotal">Sub-total</label>
                        <input type="number" step="0.01" disabled id="subTotal" name="lines[${idx}][subTotal]" value="0.00" min="0.00" class="input_form_input">
                    </div>

                    <div class="buttonToLine">
                        <button class="button" type="button" onclick="deleteLine(${idx});"><i class="fas fa-trash"></i></button>
                    </div>
                    </div>`;

const newFormLine = (index) => `<div>
                      <input name="lines[${index}][id]" value="id${idx}" hidden="true">
                      <label for="producto">Producto</label>
                      <select name="lines[${index}][producto]" onchange="changeProducto(this)" id="producto${idx}" class="input_form_select producto">
                        ${opcionesProducto}
                      </select>
                    </div>

                    <div>
                        <label for="precio">PrecioUni</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${index})" id="precio" name="lines[${index}][precio]" min="0.00" class="input_form_input" placeholder="1.00$">
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" onchange="getSubTotal(${index})" id="cantidad" name="lines[${index}][cantidad]" min="1" class="input_form_input" placeholder="1">
                    </div>
                    
                    <div>
                        <label for="subTotal">Sub-total</label>
                        <input type="number" step="0.01" disabled id="subTotal" name="lines[${index}][subTotal]" value="0.00" min="0.00" class="input_form_input">
                    </div>`;

async function getListadoProducto() {
  const response = await fetch(
    "http://localhost/LamsSystem/productos/getSelect",
  );
  const { data: opciones } = await response.json();
  const selects = document.querySelectorAll("select.producto");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  await opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `
    <option value="${opcion.id}" title="${titleText}">${opcion.etiqueta}</option>
    `;
  });
  selects.forEach((select) => {
    select.innerHTML = opcionesHtml;
  });
  opcionesProducto = opcionesHtml;
}

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

function deleteLine(idx) {
  const line = document.getElementById("line_idx_" + idx);
  line.innerHTML = "";
  console.log("Identificador de linea: ", idx);
}

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  getListadoProducto();
  getListadoProveedor();
  document.getElementById("title").innerHTML = "Registrar Entrada";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  document.getElementById("codigo").value = "";
  document.getElementById("proveedor").value = "";
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

      // Cargar listas primero
      await getListadoProducto();
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
            <label for="producto">Producto</label>
            <select name="lines[${index}][producto]" onchange="changeProducto(this)" id="producto${index}" class="input_form_select producto">
              ${opcionesProducto}
            </select>
          </div>
          <div>
            <label for="precio">PrecioUni</label>
            <input type="number" step="0.01" onchange="getSubTotal(${index})" id="precio" name="lines[${index}][precio]" min="0.00" class="input_form_input" placeholder="1.00$" value="${item.precio}">
          </div>
          <div>
            <label for="cantidad">Cantidad</label>
            <input type="number" onchange="getSubTotal(${index})" id="cantidad" name="lines[${index}][cantidad]" min="1" class="input_form_input" placeholder="1" value="${item.cantidad}">
          </div>
          <div>
            <label for="subTotal">Sub-total</label>
            <input type="number" step="0.01" disabled id="subTotal" name="lines[${index}][subTotal]" value="${item.sub_total}" min="0.00" class="input_form_input">
          </div>
          <div class="buttonToLine">
            <button class="button" type="button" onclick="deleteLine(${index});"><i class="fas fa-trash"></i></button>
          </div>
        </div>`;
        form.insertAdjacentHTML("beforeend", lineHtml);
      });

      // Establecer los valores de los selects de producto después de crear las líneas
      setTimeout(() => {
        detalle.forEach((item, index) => {
          const select = document.querySelector(
            `select[name="lines[${index}][producto]"]`,
          );
          if (select) {
            select.value = item.idproducto;
          }
        });
      }, 100);

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
  document.getElementById("codigo").value = "";
  document.getElementById("total").value = "";
  document.getElementById("tipo_pago").value = "contado";
}

const formulario = document.getElementById("formularioEntradas");

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  let data = {
    id: document.getElementById("id").value || "",
    proveedor: "",
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
  const proveedor = document.getElementById("proveedor");
  const codigo = document.getElementById("codigo");
  const total = document.getElementById("total");

  lineasDelFormulario.forEach((lineaDiv) => {
    const id = lineaDiv.querySelector('input[name*="[id]"]')?.value;
    const producto = lineaDiv.querySelector(
      'select[name*="[producto]"]',
    )?.value;
    const cantidad = lineaDiv.querySelector('input[name*="[cantidad]"]')?.value;
    const precio = lineaDiv.querySelector('input[name*="[precio]"]')?.value;
    const subTotal = lineaDiv.querySelector('input[name*="[subTotal]"]')?.value;
    if (!id || !producto || !proveedor || !cantidad || !codigo) {
      console.log(
        "Error, datos nulos",
        id,
        producto,
        proveedor,
        cantidad,
        codigo,
      );
    } else {
      data.lineas.push({
        id: id,
        producto: producto,
        precio: precio,
        cantidad: parseInt(cantidad), // Convierte a número si es necesario
        subTotal: subTotal,
      });
    }
  });
  data.codigo = codigo.value;
  data.proveedor = proveedor.value;
  data.total = total.value;
  data.tipo_pago = document.getElementById("tipo_pago").value;

  // Validar que todos los precios sean mayores a 0
  let precioInvalido = data.lineas.some(
    (linea) => parseFloat(linea.precio) <= 0,
  );

  if (data.codigo == "" || data.proveedor == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (precioInvalido) {
    alertas("El precio debe ser mayor a 0", "warning");
  } else {
    const url = APP_URL + "entradas/registrar"; // Quitamos los parámetros de la URL
    const http = new XMLHttpRequest();

    http.open("POST", url, true);

    // Es fundamental indicar que enviaremos un JSON
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

    // Convertimos el objeto JS a un string JSON para enviarlo
    http.send(JSON.stringify(data));
  }

  // console.log("Data: ", data);
});

function changeProducto(element) {
  console.log("Valor del Select: ", element.value);
  console.log("Id", element.id);
}

function getSubTotal(idx) {
  let precio = document.querySelector(
    'input[name="lines[' + idx + '][precio]"]',
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
  // Seleccionamos todos los inputs cuyo nombre empiece con "lines[" y termine en "[subTotal]"
  const subTotales = document.querySelectorAll(
    'input[name^="lines["][name$="][subTotal]"]',
  );
  let totalGeneral = 0;

  subTotales.forEach((input) => {
    // Convertimos el valor a número. Usamos || 0 por si el campo está vacío.
    totalGeneral += parseFloat(input.value) || 0;
  });

  // Buscamos el elemento donde quieras mostrar el total (ajusta el ID según tu HTML)
  const total = document.getElementById("total");
  if (total) {
    // Si es un input usa .value, si es un div/span usa .textContent
    total.value = totalGeneral.toFixed(2);
  }
}
