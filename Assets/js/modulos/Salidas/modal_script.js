/*Modal para registrar salida*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalSalida");
const modalDetalle = document.getElementById("modalDetalleSalida");
const btn = document.getElementById("registrarSalida");
const span = document.getElementsByClassName("close")[0];
const spanDetalle = document.getElementsByClassName("close-detalle")[0];
const form = document.getElementById("formularioSalida");
const btnAddLine = document.getElementById("addLine");
let productosData = {}; // Almacena los precios y stock de los productos
let idx = 0;
let modoEdicion = false;
const dataForm = { lines: [] };
const formLine = ` <div class="input_form" id="line_idx_${idx}">
                    <div>
                      <input name="lines[${idx}][id]" value="id${idx}" hidden="true">
                      <input name="lines[${idx}][producto]" type="hidden" class="producto_id">
                      <label>Codigo</label>
                      <input type="text" name="lines[${idx}][codigo_producto]" onblur="buscarProducto(${idx})" class="input_form_input codigo_producto" placeholder="Código producto">
                    </div>
                    <div>
                      <label>Producto</label>
                      <input type="text" class="input_form_input producto_nombre" disabled placeholder="Nombre del producto">
                    </div>

                    <div>
                        <label for="precio">Precio venta</label>
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
                      <input name="lines[${index}][id]" value="id${index}" hidden="true">
                      <input name="lines[${index}][producto]" type="hidden" class="producto_id">
                      <label>Codigo</label>
                      <input type="text" name="lines[${index}][codigo_producto]" onblur="buscarProducto(${index})" class="input_form_input codigo_producto" placeholder="Código producto">
                    </div>
                    <div>
                      <label>Producto</label>
                      <input type="text" class="input_form_input producto_nombre" disabled placeholder="Nombre del producto">
                    </div>

                    <div>
                        <label for="precio">Precio venta</label>
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

async function buscarProducto(lineIdx) {
  const input = document.querySelector(
    `input[name="lines[${lineIdx}][codigo_producto]"]`,
  );
  const hiddenId = document.querySelector(
    `input[name="lines[${lineIdx}][producto]"]`,
  );
  const lineDiv = document.getElementById("line_idx_" + lineIdx);
  const nombreInput = lineDiv
    ? lineDiv.querySelector(".producto_nombre")
    : null;
  const codigo = input ? input.value.trim() : "";

  if (codigo === "") {
    if (hiddenId) hiddenId.value = "";
    if (nombreInput) nombreInput.value = "";
    return;
  }

  try {
    const response = await fetch(
      APP_URL +
        "productos/buscarPorCodigo?codigo=" +
        encodeURIComponent(codigo),
    );
    const res = await response.json();
    if (res.encontrado) {
      hiddenId.value = res.id;
      if (nombreInput)
        nombreInput.value =
          res.nombre.toUpperCase() + " (Stock: " + res.stock + ")";
      // Guardar precio y stock para validación
      productosData[res.id] = {
        precio: parseFloat(res.precio),
        stock: parseInt(res.stock),
      };
    } else {
      hiddenId.value = "";
      if (nombreInput) nombreInput.value = "";
      alertas("El producto no existe", "warning");
      input.value = "";
    }
  } catch (error) {
    console.error("Error al buscar producto:", error);
  }
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
  document.getElementById("title").innerHTML = "Registrar Salida";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  document.getElementById("id").value = "";
  document.getElementById("codigo").value = "";
  document.getElementById("tipo_despacho").value = "venta";
  document.getElementById("total").value = "0.00";
  modoEdicion = false;
  productosData = {};
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

// Mapeo de valores de tipo_despacho a etiquetas legibles
const tipoDespachoLabels = {
  venta: "Venta",
  uso_interno: "Uso Interno",
  danado: "Dañado",
  devolucion: "Devolución",
};

/*Función para ver detalle de una salida*/
function btnVerDetalleSalida(id) {
  const url = APP_URL + "salidas/verDetalle/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      const cabecera = res.cabecera;
      const detalle = res.detalle;

      // Llenar datos de cabecera
      document.getElementById("detalle_usuario").textContent =
        cabecera.usuario_nombre || "N/A";
      document.getElementById("detalle_tipo_despacho").textContent =
        tipoDespachoLabels[cabecera.tipo_despacho] || cabecera.tipo_despacho;
      document.getElementById("detalle_codigo").textContent =
        cabecera.cod_docum;
      document.getElementById("detalle_fecha").textContent = cabecera.fecha;
      document.getElementById("detalle_hora").textContent = cabecera.hora;
      document.getElementById("detalle_total").textContent =
        cabecera.total + "$";

      // Llenar tabla de detalle
      const tbody = document.getElementById("detalleSalidaBody");
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

/*Función para editar una salida*/
function btnEditSalida(id) {
  document.getElementById("title").innerHTML = "Editar Salida";
  document.getElementById("btnAccion").innerHTML = "Modificar";
  modoEdicion = true;

  const url = APP_URL + "salidas/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = async function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      const cabecera = res.cabecera;
      const detalle = res.detalle;

      // Llenar datos de cabecera
      document.getElementById("id").value = cabecera.id;
      document.getElementById("codigo").value = cabecera.cod_docum;
      document.getElementById("tipo_despacho").value = cabecera.tipo_despacho;
      document.getElementById("total").value = cabecera.total;

      // Limpiar y crear líneas de detalle
      form.innerHTML = "";
      idx = 0;
      productosData = {};

      detalle.forEach((item, index) => {
        idx = index;
        // Guardar datos del producto para validación
        productosData[item.idproducto] = {
          precio: parseFloat(item.precio_producto || item.precio),
          stock: parseInt(item.stock_producto || 0),
        };
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
            <label for="precio">Precio venta</label>
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
  document.getElementById("tipo_despacho").value = "venta";
}

const formulario = document.getElementById("formularioSalidas");

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  let data = {
    id: document.getElementById("id").value || "",
    usuario: idusuario,
    codigo: "",
    fecha: fecha(),
    hora: hora(),
    lineas: [],
    total: "",
    tipo_despacho: "",
  };

  // Selecciona todos los divs que representan una línea de formulario
  const lineasDelFormulario = formulario.querySelectorAll(".input_form");
  const codigo = document.getElementById("codigo");
  const total = document.getElementById("total");

  lineasDelFormulario.forEach((lineaDiv) => {
    const id = lineaDiv.querySelector('input[name*="[id]"]')?.value;
    const producto = lineaDiv.querySelector('input[name*="[producto]"]')?.value;
    const cantidad = lineaDiv.querySelector('input[name*="[cantidad]"]')?.value;
    const precio = lineaDiv.querySelector('input[name*="[precio]"]')?.value;
    const subTotal = lineaDiv.querySelector('input[name*="[subTotal]"]')?.value;
    if (!id || !producto || !cantidad || !codigo) {
      console.log("Error, datos nulos", id, producto, cantidad, codigo);
    } else {
      data.lineas.push({
        id: id,
        producto: producto,
        precio: precio,
        cantidad: parseInt(cantidad),
        subTotal: subTotal,
      });
    }
  });
  data.codigo = codigo.value;
  data.total = total.value;
  data.tipo_despacho = document.getElementById("tipo_despacho").value;

  // Validar que el precio de venta no sea menor al precio del producto
  let precioInvalido = false;
  let productoConPrecioInvalido = "";
  for (const linea of data.lineas) {
    const precioProducto = productosData[linea.producto]?.precio || 0;
    const precioVenta = parseFloat(linea.precio);
    if (precioVenta < precioProducto) {
      precioInvalido = true;
      productoConPrecioInvalido = linea.producto;
      break;
    }
  }

  // Validar que la cantidad no supere el stock disponible
  // Agrupar cantidades por producto (para múltiples líneas del mismo producto)
  let stockInsuficiente = false;
  let productoSinStock = "";
  const cantidadesPorProducto = {};
  for (const linea of data.lineas) {
    const idProducto = linea.producto;
    const cantidad = parseInt(linea.cantidad);
    if (!cantidadesPorProducto[idProducto]) {
      cantidadesPorProducto[idProducto] = 0;
    }
    cantidadesPorProducto[idProducto] += cantidad;
  }

  // Validar stock para cada producto (considerando todas las líneas sumadas)
  for (const idProducto in cantidadesPorProducto) {
    const stockDisponible = productosData[idProducto]?.stock || 0;
    const cantidadTotal = cantidadesPorProducto[idProducto];
    if (cantidadTotal > stockDisponible) {
      stockInsuficiente = true;
      productoSinStock = idProducto;
      break;
    }
  }

  if (data.codigo == "") {
    alertas("Todos los campos son obligatorios", "warning");
  } else if (precioInvalido) {
    alertas(
      "El precio de venta no puede ser menor al precio del producto",
      "warning",
    );
  } else if (stockInsuficiente) {
    alertas("Stock insuficiente para uno o más productos", "warning");
  } else {
    const enviarFormulario = () => {
      const url = APP_URL + "salidas/registrar";
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

  console.log("Data: ", data);
});

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
