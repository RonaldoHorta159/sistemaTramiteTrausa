// Hacemos que Recordar acepte los elementos como parámetros
function Recordar(checkElement, userElement, passElement) {
  // Verificamos si el checkbox está MARCADO (con la propiedad .checked)
  if (
    checkElement.checked &&
    userElement.value !== "" &&
    passElement.value !== ""
  ) {
    // Guardamos los valores en localStorage
    localStorage.usuario = userElement.value;
    localStorage.pass = passElement.value;
    localStorage.checkbox = checkElement.checked; // Guardamos 'true'
  } else {
    // Si no está marcado, limpiamos el localStorage
    localStorage.removeItem("usuario");
    localStorage.removeItem("pass");
    localStorage.removeItem("checkbox");
  }
}

function Ingresar() {
  // Obtenemos los elementos del DOM aquí, justo cuando se necesitan.
  let usuInput = document.getElementById("text_usuario");
  let conInput = document.getElementById("text_contra");
  let rememberCheck = document.getElementById("remember");

  // Llamamos a Recordar con los elementos correctos
  Recordar(rememberCheck, usuInput, conInput);

  // El resto de tu función Ingresar continúa aquí...
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
    dataType: "json",
    data: {
      u: usu,
      c: con,
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
  })
    .done(function (data) {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: "Será redirigido a la página principal.",
          timer: 1500,
          showConfirmButton: false,
          heightAuto: false,
        }).then((result) => {
          location.href = "view/index.php";
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error de Autenticación",
          text: data.message,
          heightAuto: false,
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
        heightAuto: false,
      });
    });
}
