$(document).ready(function () {
  // 1. INICIALIZAR LA TABLA
  var tablaTipoDocumento = $("#tabla_tipo_documento").DataTable({
    responsive: true,
    processing: true,
    ajax: {
      url: "../controller/tipo_documento/controlador_listar_tipo_documento.php",
      type: "POST",
    },
    columns: [
      { data: "id" },
      { data: "nombre" },
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
  $("#btn_nuevo_tipo_documento").click(function () {
    $("#modal_registro_tipo_documento").modal("show");
    $("#nombre_tipo_documento").val("");
  });

  // 3. GUARDAR NUEVO REGISTRO
  $("#btn_guardar_tipo_documento").click(function () {
    let nombre = $("#nombre_tipo_documento").val();
    if (nombre.trim() === "")
      return Swal.fire("Campo Vacío", "Debe ingresar un nombre", "warning");

    $.ajax({
      url: "../controller/tipo_documento/controlador_registrar_tipo_documento.php",
      type: "POST",
      data: { nombre: nombre },
      success: function (response) {
        if (response === "OK") {
          $("#modal_registro_tipo_documento").modal("hide");
          Swal.fire("¡Éxito!", "Registrado correctamente", "success");
          tablaTipoDocumento.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire("Advertencia", "El tipo de documento ya existe", "warning");
        } else {
          Swal.fire("Error", "No se pudo registrar", "error");
        }
      },
    });
  });

  // 4. ABRIR MODAL DE EDICIÓN
  $("#tabla_tipo_documento tbody").on("click", ".editar-btn", function () {
    let data = tablaTipoDocumento.row($(this).parents("tr")).data();
    $("#id_tipo_documento_editar").val(data.id);
    $("#nombre_tipo_documento_editar").val(data.nombre);
    $("#modal_edicion_tipo_documento").modal("show");
  });

  // 5. ACTUALIZAR REGISTRO
  $("#btn_actualizar_tipo_documento").click(function () {
    let id = $("#id_tipo_documento_editar").val();
    let nombre = $("#nombre_tipo_documento_editar").val();
    if (nombre.trim() === "")
      return Swal.fire("Campo Vacío", "Debe ingresar un nombre", "warning");

    $.ajax({
      url: "../controller/tipo_documento/controlador_editar_tipo_documento.php",
      type: "POST",
      data: { id: id, nombre: nombre },
      success: function (response) {
        if (response === "OK") {
          $("#modal_edicion_tipo_documento").modal("hide");
          Swal.fire("¡Éxito!", "Actualizado correctamente", "success");
          tablaTipoDocumento.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire("Advertencia", "El nombre ya está en uso", "warning");
        } else {
          Swal.fire("Error", "No se pudo actualizar", "error");
        }
      },
    });
  });

  // 6. ELIMINAR REGISTRO
  $("#tabla_tipo_documento tbody").on("click", ".eliminar-btn", function () {
    let id = tablaTipoDocumento.row($(this).parents("tr")).data().id;
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, ¡bórralo!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controller/tipo_documento/controlador_eliminar_tipo_documento.php",
          type: "POST",
          data: { id: id },
          success: function (response) {
            if (response === "OK") {
              Swal.fire(
                "¡Eliminado!",
                "El registro ha sido eliminado.",
                "success"
              );
              tablaTipoDocumento.ajax.reload();
            } else {
              Swal.fire("Error", "No se pudo eliminar", "error");
            }
          },
        });
      }
    });
  });
});
