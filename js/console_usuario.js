$(document).ready(function () {
  // 1. INICIALIZAR LA TABLA DE USUARIOS
  var tablaUsuario = $("#tabla_usuario").DataTable({
    responsive: true,
    processing: true,
    ajax: {
      url: "../controller/usuario/controlador_listar_usuario.php",
      type: "POST",
    },
    columns: [
      { data: "id" },
      { data: "nombre_usuario" },
      { data: "empleado_nombre" },
      { data: "area_nombre" },
      { data: "rol" },
      {
        data: "estado",
        render: function (data) {
          return data === "ACTIVO"
            ? '<span class="badge badge-success">ACTIVO</span>'
            : '<span class="badge badge-danger">INACTIVO</span>';
        },
      },
      {
        data: null,
        defaultContent: `<button class="btn btn-primary btn-sm editar-btn" title="Editar"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm eliminar-btn" title="Eliminar"><i class="fas fa-trash"></i></button>`,
      },
    ],
    language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
  });

  // --- LÓGICA PARA CARGAR LOS MENÚS DESPLEGABLES (COMBOS) ---
  function cargarCombos() {
    // Cargar empleados sin usuario para el combo de registro
    $.ajax({
      url: "../controller/usuario/controlador_listar_empleado_combo.php",
      type: "POST",
    }).done(function (resp) {
      let data = JSON.parse(resp);
      let cadena = "";
      if (data.length > 0) {
        for (let i = 0; i < data.length; i++) {
          cadena +=
            "<option value='" +
            data[i]["id"] +
            "'>" +
            data[i]["nombre_completo"] +
            "</option>";
        }
      } else {
        cadena = "<option value=''>No hay empleados disponibles</option>";
      }
      $("#combo_empleado").html(cadena);
    });

    // Cargar áreas para ambos combos (registro y edición)
    $.ajax({
      url: "../controller/usuario/controlador_listar_area_combo.php",
      type: "POST",
    }).done(function (resp) {
      let data = JSON.parse(resp);
      let cadena = "";
      if (data.length > 0) {
        for (let i = 0; i < data.length; i++) {
          cadena +=
            "<option value='" +
            data[i]["id"] +
            "'>" +
            data[i]["nombre"] +
            "</option>";
        }
        $("#combo_area").html(cadena);
        $("#combo_area_editar").html(cadena);
      } else {
        cadena = "<option value=''>No hay áreas disponibles</option>";
        $("#combo_area").html(cadena);
        $("#combo_area_editar").html(cadena);
      }
    });
  }

  // --- GESTIÓN DE EVENTOS DE CLIC ---

  // 2. ABRIR MODAL DE REGISTRO
  $("#btn_nuevo_usuario").click(function () {
    cargarCombos(); // Llenamos los combos antes de mostrar
    $("#formulario_registro_usuario")[0].reset();
    $("#modal_registro_usuario").modal("show");
  });

  // 3. GUARDAR NUEVO USUARIO
  $("#btn_guardar_usuario").click(function () {
    let datos = {
      empleado: $("#combo_empleado").val(),
      area: $("#combo_area").val(),
      usuario: $("#nombre_usuario").val(),
      password: $("#password_usuario").val(),
      rol: $("#combo_rol").val(),
    };

    if (
      !datos.empleado ||
      !datos.area ||
      !datos.usuario.trim() ||
      !datos.password.trim() ||
      !datos.rol
    ) {
      return Swal.fire(
        "Campos Vacíos",
        "Por favor, complete todos los campos.",
        "warning"
      );
    }

    $.ajax({
      url: "../controller/usuario/controlador_registrar_usuario.php",
      type: "POST",
      data: datos,
      success: function (response) {
        if (response === "OK") {
          $("#modal_registro_usuario").modal("hide");
          Swal.fire("¡Éxito!", "Usuario registrado correctamente.", "success");
          tablaUsuario.ajax.reload();
        } else if (response === "EXISTE_USUARIO") {
          Swal.fire(
            "Advertencia",
            "El nombre de usuario ya está en uso.",
            "warning"
          );
        } else {
          Swal.fire("Error", "No se pudo completar el registro.", "error");
        }
      },
    });
  });

  // 4. ABRIR MODAL DE EDICIÓN
  $("#tabla_usuario tbody").on("click", ".editar-btn", function () {
    let data = tablaUsuario.row($(this).parents("tr")).data();
    cargarCombos(); // Cargamos las áreas por si hay nuevas

    // Llenamos el formulario
    $("#id_usuario_editar").val(data.id);
    $("#empleado_editar").val(data.empleado_nombre); // Campo deshabilitado
    $("#combo_area_editar").val(data.area_id); // ¡Necesitamos el ID del área! Hay que añadirlo al listado
    $("#combo_rol_editar").val(data.rol);
    $("#combo_estado_editar").val(data.estado);

    $("#modal_edicion_usuario").modal("show");
  });

  // 5. ACTUALIZAR DATOS DEL USUARIO
  $("#btn_actualizar_usuario").click(function () {
    let datos = {
      id: $("#id_usuario_editar").val(),
      area: $("#combo_area_editar").val(),
      rol: $("#combo_rol_editar").val(),
      estado: $("#combo_estado_editar").val(),
    };
    $.ajax({
      url: "../controller/usuario/controlador_editar_usuario.php",
      type: "POST",
      data: datos,
      success: function (response) {
        if (response === "OK") {
          $("#modal_edicion_usuario").modal("hide");
          Swal.fire("¡Éxito!", "Datos actualizados.", "success");
          tablaUsuario.ajax.reload();
        } else {
          Swal.fire("Error", "No se pudo actualizar.", "error");
        }
      },
    });
  });

  // 6. ABRIR MODAL PARA CAMBIAR CONTRASEÑA
  $("#btn_cambiar_password_modal").click(function () {
    let id = $("#id_usuario_editar").val();
    $("#id_usuario_password").val(id);
    $("#nueva_password").val("");
    $("#modal_password").modal("show");
  });

  // 7. GUARDAR NUEVA CONTRASEÑA
  $("#btn_guardar_password").click(function () {
    let id = $("#id_usuario_password").val();
    let newPassword = $("#nueva_password").val();
    if (newPassword.trim().length < 4) {
      // Validación mínima
      return Swal.fire(
        "Contraseña Corta",
        "La contraseña debe tener al menos 4 caracteres.",
        "warning"
      );
    }
    $.ajax({
      url: "../controller/usuario/controlador_cambiar_password.php",
      type: "POST",
      data: { id: id, newPassword: newPassword },
      success: function (response) {
        if (response === "OK") {
          $("#modal_password").modal("hide");
          Swal.fire("¡Éxito!", "Contraseña actualizada.", "success");
        } else {
          Swal.fire("Error", "No se pudo cambiar la contraseña.", "error");
        }
      },
    });
  });

  // 8. ELIMINAR USUARIO
  $("#tabla_usuario tbody").on("click", ".eliminar-btn", function () {
    let id = tablaUsuario.row($(this).parents("tr")).data().id;
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, ¡bórralo!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controller/usuario/controlador_eliminar_usuario.php",
          type: "POST",
          data: { id: id },
          success: function (response) {
            if (response === "OK") {
              Swal.fire(
                "¡Eliminado!",
                "El usuario ha sido eliminado.",
                "success"
              );
              tablaUsuario.ajax.reload();
            } else if (response === "ERROR_FK") {
              Swal.fire(
                "Error",
                "No se puede eliminar un usuario que ya ha realizado acciones en el sistema.",
                "error"
              );
            } else {
              Swal.fire("Error", "No se pudo eliminar.", "error");
            }
          },
        });
      }
    });
  });
});
