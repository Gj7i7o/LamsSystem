/*
App.js: Contiene funciones generales que pertenecen a todos los módulos
*/

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
