// js/console_login.js

function Recordar(checkElement, userElement, passElement) {
  if (
    checkElement.checked &&
    userElement.value !== "" &&
    passElement.value !== ""
  ) {
    localStorage.usuario = userElement.value;
    localStorage.pass = passElement.value;
    localStorage.checkbox = checkElement.checked;
  } else {
    localStorage.removeItem("usuario");
    localStorage.removeItem("pass");
    localStorage.removeItem("checkbox");
  }
}

function Ingresar() {
  let usuInput = document.getElementById("text_usuario");
  let conInput = document.getElementById("text_contra");
  let rememberCheck = document.getElementById("remember");

  Recordar(rememberCheck, usuInput, conInput);

  let usu = usuInput.value;
  let con = conInput.value;

  if (usu.length == 0 || con.length == 0) {
    return Swal.fire({
      icon: "warning",
      title: "Campos Vacíos",
      text: "Por favor, complete todos los campos.",
      heightAuto: false,
    });
  }

  $.ajax({
    url: "controller/usuario/controlador_iniciar_sesion.php",
    type: "POST",
    dataType: "json", // Esperamos un JSON del servidor
    data: {
      u: usu,
      c: con,
    },
    success: function (data) {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: "Será redirigido a la página principal.",
          timer: 1500,
          showConfirmButton: false,
          heightAuto: false,
        }).then(() => {
          // Usamos la URL que nos envía el controlador
          location.href = data.redirect;
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error de Autenticación",
          text: data.message, // Usamos el mensaje del controlador
          heightAuto: false,
        });
        document.getElementById("text_contra").value = "";
      }
    },
    error: function () {
      // .fail() está obsoleto, usamos error:
      Swal.fire({
        icon: "error",
        title: "Error del Servidor",
        text: "No se pudo conectar o procesar la solicitud.",
        heightAuto: false,
      });
    },
    beforeSend: function () {
      Swal.fire({
        title: "Verificando...",
        allowOutsideClick: false,
        heightAuto: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
    },
  });
}
