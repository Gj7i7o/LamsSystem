/*
App.js: Contiene funciones generales que pertenecen a todos los módulos
*/

/*Función para mostrar alertas*/
function alertas(mensaje, icono) {
  Swal.fire({
    icon: icono,
    title: mensaje,
    showConfirmButton: false,
    timer: 3000,
  });
}

function recargarVista(tiempo = 1000) {
  setTimeout(() => {
    location.reload();
  }, tiempo);
}
