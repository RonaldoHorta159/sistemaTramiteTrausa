function Ingresar() {
  let usu = document.getElementById("text_usuario").value;
  let con = document.getElementById("text_contra").value;

  if (usu.length == 0 || con.length == 0) {
    return Swal.fire({
      icon: "warning",
      title: "Campos Vacíos",
      text: "Por favor, complete todos los campos.",
      heightAuto: false, // <-- AÑADIDO AQUÍ
    });
  }

  $.ajax({
    url: "controller/usuario/controlador_iniciar_sesion.php",
    type: "POST",
    dataType: "json",
    data: {
      u: usu,
      c: con,
    },
    beforeSend: function () {
      Swal.fire({
        title: "Verificando...",
        allowOutsideClick: false,
        heightAuto: false, // <-- AÑADIDO AQUÍ
        didOpen: () => {
          Swal.showLoading();
        },
      });
    },
  })
    .done(function (data) {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: "Será redirigido a la página principal.",
          timer: 1500,
          showConfirmButton: false,
          heightAuto: false, // <-- CORREGIDO AQUÍ
        }).then((result) => {
          location.href = "view/index.php";
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error de Autenticación",
          text: data.message,
          heightAuto: false, // <-- AÑADIDO AQUÍ
        });
        document.getElementById("text_contra").value = "";
      }
    })
    .fail(function () {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error del Servidor",
        text: "No se pudo conectar o procesar la solicitud.",
        heightAuto: false, // <-- CORREGIDO AQUÍ (estaba como 'heighAuto: falase')
      });
    });
}
