$(document).ready(function () {
  // 1. INICIALIZAR LA TABLA
  var tablaArea = new DataTable("#tabla_area", {
    responsive: true,
    processing: true,
    ajax: {
      url: "../controller/area/controlador_listar_area.php",
      type: "POST",
    },
    columns: [
      { data: "id" },
      { data: "nombre" },
      { data: "fecha_registro" },
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
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
    },
  });

  // 2. ABRIR MODAL PARA NUEVO REGISTRO
  $("#btn_nuevo_registro").click(function () {
    $("#formulario_registro_area")[0].reset();
    $("#modal_registro_area").modal("show");
  });

  // 3. GUARDAR NUEVO REGISTRO
  $("#btn_guardar").click(function () {
    let nombreArea = $("#nombre_area").val();
    if (nombreArea.trim() === "") {
      return Swal.fire(
        "Campo Vacío",
        "Por favor, ingrese el nombre del área.",
        "warning"
      );
    }
    $.ajax({
      url: "../controller/area/controlador_registrar_area.php",
      type: "POST",
      data: { nombreArea: nombreArea },
      success: function (response) {
        if (response === "OK") {
          $("#modal_registro_area").modal("hide");
          Swal.fire("¡Éxito!", "Área registrada.", "success");
          tablaArea.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire("Advertencia", "El área ya existe.", "warning");
        } else {
          Swal.fire("Error", "No se pudo registrar.", "error");
        }
      },
    });
  });

  // =========== LÓGICA PARA EDITAR Y ELIMINAR ===========

  // 4. ABRIR MODAL PARA EDICIÓN
  $("#tabla_area tbody").on("click", ".editar-btn", function () {
    // Obtenemos los datos de la fila seleccionada
    let data = tablaArea.row($(this).parents("tr")).data();

    // Llenamos el formulario del modal de edición
    $("#id_area_editar").val(data.id);
    $("#nombre_area_editar").val(data.nombre);

    // Mostramos el modal
    $("#modal_edicion_area").modal("show");
  });

  // 5. ACTUALIZAR REGISTRO
  $("#btn_actualizar").click(function () {
    let id = $("#id_area_editar").val();
    let nombre = $("#nombre_area_editar").val();

    if (nombre.trim() === "") {
      return Swal.fire(
        "Campo Vacío",
        "El nombre del área no puede estar vacío.",
        "warning"
      );
    }

    $.ajax({
      url: "../controller/area/controlador_editar_area.php",
      type: "POST",
      data: { id: id, nombreArea: nombre },
      success: function (response) {
        if (response === "OK") {
          $("#modal_edicion_area").modal("hide");
          Swal.fire("¡Éxito!", "Área actualizada.", "success");
          tablaArea.ajax.reload();
        } else if (response === "EXISTE") {
          Swal.fire(
            "Advertencia",
            "Ese nombre de área ya está en uso.",
            "warning"
          );
        } else {
          Swal.fire("Error", "No se pudo actualizar.", "error");
        }
      },
    });
  });

  // 6. ELIMINAR REGISTRO CON CONFIRMACIÓN
  $("#tabla_area tbody").on("click", ".eliminar-btn", function () {
    let data = tablaArea.row($(this).parents("tr")).data();
    let id = data.id;

    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esta acción!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, ¡bórralo!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controller/area/controlador_eliminar_area.php",
          type: "POST",
          data: { id: id },
          success: function (response) {
            if (response === "OK") {
              Swal.fire("¡Eliminado!", "El área ha sido eliminada.", "success");
              tablaArea.ajax.reload();
            } else {
              Swal.fire("Error", "No se pudo eliminar el registro.", "error");
            }
          },
        });
      }
    });
  });
});
