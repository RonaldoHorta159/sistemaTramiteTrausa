function Ingresar() {
  let usu = document.getElementById("text_usuario").value;
  let con = document.getElementById("text_contra").value;

  if (usu.length == 0 || con.length == 0) {
    return Swal.fire({
      icon: "error",
      title: "Error",
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
  });
}
