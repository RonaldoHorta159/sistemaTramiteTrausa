$(document).ready(function () {
  // 1. INICIALIZAR LA TABLA
  var tablaEmpleado = $("#tabla_empleado").DataTable({
    responsive: true,
    processing: true,
    ajax: {
      url: "../controller/empleado/controlador_listar_empleado.php",
      type: "POST",
    },
    columns: [
      { data: "id" },
      { data: "dni" },
      {
        data: null,
        render: function (data, type, row) {
          return `${row.nombres} ${row.apellido_paterno} ${row.apellido_materno}`;
        },
      },
      { data: "email" },
      { data: "celular" },
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

  // 2. ABRIR MODAL DE REGISTRO
  $("#btn_nuevo_empleado").click(function () {
    $("#formulario_registro_empleado")[0].reset();
    $("#modal_registro_empleado").modal("show");
  });

  // 3. GUARDAR NUEVO EMPLEADO
  $("#btn_guardar_empleado").click(function () {
    // Recolectamos todos los datos del formulario
    let datos = {
      dni: $("#dni_registro").val(),
      nombres: $("#nombres_registro").val(),
      apePaterno: $("#ape_paterno_registro").val(),
      apeMaterno: $("#ape_materno_registro").val(),
      email: $("#email_registro").val(),
      celular: $("#celular_registro").val(),
      fecNacimiento: $("#fec_nacimiento_registro").val(),
      direccion: $("#direccion_registro").val(),
    };

    // Validación simple de campos obligatorios
    if (
      !datos.dni ||
      !datos.nombres ||
      !datos.apePaterno ||
      !datos.apeMaterno ||
      !datos.email
    ) {
      return Swal.fire(
        "Campos Vacíos",
        "Por favor, complete todos los campos marcados con (*).",
        "warning"
      );
    }

    $.ajax({
      url: "../controller/empleado/controlador_registrar_empleado.php",
      type: "POST",
      data: datos,
      success: function (response) {
        if (response === "OK") {
          $("#modal_registro_empleado").modal("hide");
          Swal.fire("¡Éxito!", "Empleado registrado correctamente.", "success");
          tablaEmpleado.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire(
            "Advertencia",
            "El DNI o Email ingresado ya existe.",
            "warning"
          );
        } else {
          Swal.fire("Error", "No se pudo completar el registro.", "error");
        }
      },
    });
  });

  // 4. ABRIR MODAL DE EDICIÓN Y CARGAR DATOS
  $("#tabla_empleado tbody").on("click", ".editar-btn", function () {
    let data = tablaEmpleado.row($(this).parents("tr")).data();

    // Llenamos el formulario de edición con los datos de la fila
    $("#id_empleado_editar").val(data.id);
    $("#dni_editar").val(data.dni);
    $("#nombres_editar").val(data.nombres);
    $("#ape_paterno_editar").val(data.apellido_paterno);
    $("#ape_materno_editar").val(data.apellido_materno);
    $("#email_editar").val(data.email);
    $("#celular_editar").val(data.celular);
    $("#fec_nacimiento_editar").val(data.fecha_nacimiento);
    $("#direccion_editar").val(data.direccion);

    $("#modal_edicion_empleado").modal("show");
  });

  // 5. ACTUALIZAR EMPLEADO
  $("#btn_actualizar_empleado").click(function () {
    let datosActualizados = {
      id: $("#id_empleado_editar").val(),
      dni: $("#dni_editar").val(),
      nombres: $("#nombres_editar").val(),
      apePaterno: $("#ape_paterno_editar").val(),
      apeMaterno: $("#ape_materno_editar").val(),
      email: $("#email_editar").val(),
      celular: $("#celular_editar").val(),
      fecNacimiento: $("#fec_nacimiento_editar").val(),
      direccion: $("#direccion_editar").val(),
    };

    if (
      !datosActualizados.dni ||
      !datosActualizados.nombres ||
      !datosActualizados.apePaterno ||
      !datosActualizados.apeMaterno ||
      !datosActualizados.email
    ) {
      return Swal.fire(
        "Campos Vacíos",
        "Por favor, complete todos los campos marcados con (*).",
        "warning"
      );
    }

    $.ajax({
      url: "../controller/empleado/controlador_editar_empleado.php",
      type: "POST",
      data: datosActualizados,
      success: function (response) {
        if (response === "OK") {
          $("#modal_edicion_empleado").modal("hide");
          Swal.fire("¡Éxito!", "Datos actualizados correctamente.", "success");
          tablaEmpleado.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire(
            "Advertencia",
            "El DNI o Email ingresado ya pertenece a otro empleado.",
            "warning"
          );
        } else {
          Swal.fire("Error", "No se pudo actualizar.", "error");
        }
      },
    });
  });

  // 6. ELIMINAR EMPLEADO CON CONFIRMACIÓN
  $("#tabla_empleado tbody").on("click", ".eliminar-btn", function () {
    let id = tablaEmpleado.row($(this).parents("tr")).data().id;

    Swal.fire({
      title: "¿Estás seguro de eliminar a este empleado?",
      text: "Esta acción no se puede revertir.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, ¡bórralo!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controller/empleado/controlador_eliminar_empleado.php",
          type: "POST",
          data: { id: id },
          success: function (response) {
            if (response === "OK") {
              Swal.fire(
                "¡Eliminado!",
                "El empleado ha sido eliminado.",
                "success"
              );
              tablaEmpleado.ajax.reload();
            } else if (response === "ERROR_FK") {
              Swal.fire(
                "Error",
                "No se puede eliminar al empleado porque está asociado a un usuario o a un trámite.",
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
