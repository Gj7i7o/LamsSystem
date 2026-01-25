/*Modal para registrar salida*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalSalida");
const btn = document.getElementById("registrarSalida");
const span = document.getElementsByClassName("close")[0];
const form = document.getElementById("formularioSalida");
const btnAddLine = document.getElementById("addLine");
let opcionesProducto = "";
let productosData = {}; // Almacena los precios de los productos
let idx = 0;
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
                        <label for="precio">Precio venta</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${idx})" id="precio" name="lines[${idx}][precio]" min="0.00" class="input_form_input" placeholder="1.00$" required>
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" onchange="getSubTotal(${idx})" id="cantidad" name="lines[${idx}][cantidad]" min="1" class="input_form_input" placeholder="1" required>
                    </div>

                    <div>
                        <label for="subTotal">Sub-total</label>
                        <input type="number" step="0.01" disabled id="subTotal" name="lines[${idx}][subTotal]" value="0.00" min="0.00" class="input_form_input" required>
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
                        <label for="precio">Precio venta</label>
                        <input type="number" step="0.01" onchange="getSubTotal(${index})" id="precio" name="lines[${index}][precio]" min="0.00" class="input_form_input" placeholder="1.00$" required>
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" onchange="getSubTotal(${index})" id="cantidad" name="lines[${index}][cantidad]" min="1" class="input_form_input" placeholder="1" required>
                    </div>
                    
                    <div>
                        <label for="subTotal">Sub-total</label>
                        <input type="number" step="0.01" disabled id="subTotal" name="lines[${index}][subTotal]" value="0.00" min="0.00" class="input_form_input" required>
                    </div>`;

async function getListadoProducto() {
  const response = await fetch(
    "http://localhost/LamsSystem/productos/getSelect",
  );
  const { data: opciones } = await response.json();
  const selects = document.querySelectorAll("select.producto");
  let opcionesHtml = `<option value="">Seleccione...</option>`;
  productosData = {}; // Reiniciar datos de productos
  await opciones.forEach((opcion) => {
    const titleText = opcion.etiquetaCompleta || opcion.etiqueta;
    opcionesHtml += `
    <option value="${opcion.id}" title="${titleText}" data-precio="${opcion.precio}">${opcion.etiqueta}</option>
    `;
    // Guardar el precio del producto para validación
    productosData[opcion.id] = { precio: parseFloat(opcion.precio) };
  });
  selects.forEach((select) => {
    select.innerHTML = opcionesHtml;
  });
  opcionesProducto = opcionesHtml;
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
  form.insertAdjacentHTML('beforeend', newLineHtml);
};

function deleteLine(idx) {
  const line = document.getElementById("line_idx_" + idx);
  line.innerHTML = "";
  console.log("Identificador de linea: ", idx);
}

// Cuando el usuario hace clic en el botón, abre el modal
btn.onclick = function () {
  getListadoProducto();
  document.getElementById("title").innerHTML = "Registrar Salida";
  document.getElementById("btnAccion").innerHTML = "Registrar";
  form.innerHTML = formLine;
  modal.style.display = "block";
};

// Cuando el usuario hace clic en la <span> (x), cierra el modal
span.onclick = function () {
  modal.style.display = "none";
};

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
    const producto = lineaDiv.querySelector(
      'select[name*="[producto]"]',
    )?.value;
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
        cantidad: parseInt(cantidad), // Convierte a número si es necesario
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

  if (data.codigo == "") {
    alertas("El código de factura es necesario", "warning");
  } else if (precioInvalido) {
    alertas("El precio de venta no puede ser menor al precio del producto", "warning");
  } else {
    const url = APP_URL + "salidas/registrar"; // Quitamos los parámetros de la URL
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

  console.log("Data: ", data);
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
