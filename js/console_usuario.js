function Ingresar() {
  let usu = document.getElementById("text_usuario").value;
  let con = document.getElementById("text_contra").value;

  // La validación inicial está bien.
  if (usu.length == 0 || con.length == 0) {
    return Swal.fire({
      icon: "warning", // Usamos 'warning' para campos vacíos.
      title: "Campos Vacíos",
      text: "Por favor, complete todos los campos.",
    });
  }

  $.ajax({
    url: "../controller/usuario/controlador_iniciar_sesion.php",
    type: "POST",
    data: {
      u: usu,
      c: con,
    },
    // Añadimos un beforeSend para dar retroalimentación al usuario
    beforeSend: function () {
      // Muestra un loader mientras se procesa la petición
      Swal.fire({
        title: "Verificando...",
        text: "Por favor espere.",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
    },
  })
    .done(function (resp) {
      // La petición se completó, analizamos la respuesta del servidor.
      let data = JSON.parse(resp);

      // El backend nos devuelve un objeto con un campo 'status'.
      if (data.status === "success") {
        // Si el login es exitoso, creamos las variables de sesión en el backend.
        // Aquí, simplemente redirigimos.
        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: "Será redirigido a la página principal.",
          timer: 1500, // Se cierra automáticamente después de 1.5 segundos
          showConfirmButton: false,
        }).then((result) => {
          // Redirección a la página principal del sistema
          location.href = "view/index.php";
        });
      } else {
        // Si el status es 'error' o cualquier otra cosa.
        Swal.fire({
          icon: "error",
          title: "Error de Autenticación",
          text: data.message, // Mostramos el mensaje que nos envía el backend.
        });
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      // Añadimos un .fail() para capturar errores de servidor o de red.
      Swal.close(); // Cerramos el loader
      Swal.fire({
        icon: "error",
        title: "Error del Servidor",
        text: "No se pudo conectar con el servidor. Por favor, intente más tarde.",
      });
      console.error("Error en la petición AJAX: " + textStatus, errorThrown);
    });
}
