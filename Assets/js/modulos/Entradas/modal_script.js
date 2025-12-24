/*Modal para registrar entrada*/
// Obtener los elementos del DOM
const modal = document.getElementById("modalEntrada");
const btn = document.getElementById("registrarEntrada");
const span = document.getElementsByClassName("close")[0];
const form = document.getElementById("formularioEntrada");
const btnAddLine = document.getElementById("addLine");
let opcionesProveedor = "";
let opcionesProducto = "";
let idx = 0;
const formLine = ` <div class="input_form" id="line_idx_${idx}">
                    <div>
                      <input type="number" name="lines[${idx}][id]" hidden="true">
                      <label for="producto">Producto</label>
                      <select name="lines[${idx}][producto]" id="producto" class="input_form_select">
                        ${opcionesProducto}
                      </select>
                    </div>

                    <div>
                        <label for="proveedor">Proveedor</label>
                        <select name="lines[${idx}][proveedor]" id="proveedor" class="input_form_select">
                          ${opcionesProveedor}
                        </select>
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" name="lines[${idx}][cantidad]" min="1" class="input_form_input" required>
                    </div>
                    <div class="buttonToLine">
                        <button class="button" type="button" onclick="deleteLine(${idx});"><i class="fas fa-trash"></i></button>
                    </div>
                    </div>`;

const newFormLine = (index) => `<div>
                      <input type="number" name="lines[${index}][id]" hidden="true">
                      <label for="producto">Producto</label>
                      <select name="lines[${index}][producto]" id="producto" class="input_form_select">
                        ${opcionesProducto}
                      </select>
                    </div>

                    <div>
                        <label for="proveedor">Proveedor</label>
                        <select name="lines[${index}][proveedor]" id="proveedor" class="input_form_select">
                          ${opcionesProveedor}
                        </select>
                    </div>

                    <div>
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" name="lines[${index}][cantidad]" min="1" class="input_form_input" required>
                    </div>`;

async function getListadoProducto() {
  const response = await fetch(
    "http://localhost/LamsSystem/Productos/getSelect"
  );
  const { data: opciones } = await response.json();
  const select = document.getElementById("producto");
  let opcionesHtml = `<option value="">Selecciones...</option>`;
  await opciones.forEach((opcion) => {
    opcionesHtml += `
    <option value="${opcion.id}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
  opcionesProducto = opcionesHtml;
}

async function getListadoProveedor() {
  const response = await fetch(
    "http://localhost/LamsSystem/Proveedores/getSelect"
  );
  const { data: opciones } = await response.json();
  const select = document.getElementById("proveedor");
  let opcionesHtml = `<option value="">Selecciones...</option>`;
  await opciones.forEach((opcion) => {
    opcionesHtml += `
    <option value="${opcion.id}">${opcion.etiqueta}</option>
    `;
  });
  select.innerHTML = opcionesHtml;
  opcionesProveedor = opcionesHtml;
}

btnAddLine.onclick = function () {
  idx++;
  form.innerHTML +=
    `<div class="input_form" id="line_idx_${idx}">` +
    newFormLine(idx) +
    ` <div class="buttonToLine">
        <button class="button" type="button" onclick="deleteLine(${idx});"><i class="fas fa-trash"></i></button>
      </div>
    </div>`;
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
  form.innerHTML = formLine;
  modal.style.display = "block";
};

// Cuando el usuario hace clic en la <span> (x), cierra el modal
span.onclick = function () {
  modal.style.display = "none";
  limpiarFormulario();
};

function limpiarFormulario() {
  document.getElementById("id").value = "";
}

const formulario = document.getElementById("formularioEntradas");

formulario.addEventListener("submit", function (e) {
  e.preventDefault();

  let lineas = [];

  // Selecciona todos los divs que representan una línea de formulario
  const lineasDelFormulario = formulario.querySelectorAll(".input_form");

  lineasDelFormulario.forEach((lineaDiv) => {
    const id = lineaDiv.querySelector('input[name*="[id]"]').value;
    const producto = lineaDiv.querySelector('select[name*="[producto]"]').value;
    const proveedor = lineaDiv.querySelector(
      'select[name*="[proveedor]"]'
    ).value;
    const cantidad = lineaDiv.querySelector('input[name*="[cantidad]"]').value;
    if (producto == "" || proveedor == "" || cantidad == "") {
      console.log("Error, datos nulos", producto, proveedor, cantidad);
    } else {
      lineas.push({
        id: id,
        producto: producto,
        proveedor: proveedor,
        cantidad: parseInt(cantidad), // Convierte a número si es necesario
      });
    }
  });
  console.log("Data: ", lineas);
});
