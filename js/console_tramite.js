// Variable global para la tabla, para poder acceder a ella desde cualquier función.
var tablaTramite;

/**
 * Función principal para inicializar y configurar la DataTable de trámites.
 */
function listar_tramite() {
  tablaTramite = $("#tabla_tramite").DataTable({
    ordering: false,
    bLengthChange: true,
    searching: { regex: false },
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    pageLength: 10,
    destroy: true,
    processing: true,
    ajax: {
      url: "../controller/tramite/controlador_listar_tramite.php",
      type: "POST",
    },
    columns: [
      { data: "codigo_unico" },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-info btn-sm ver-btn" data-id="${row.id}" title="Ver Seguimiento"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-primary btn-sm editar-btn" data-id="${row.id}" title="Editar"><i class="fas fa-edit"></i></button>`;
        },
      },
      { data: "nro_documento" },
      { data: "fecha_registro" },
      { data: "tipo_documento_nombre" },
      { data: "asunto" },
      { data: "nro_folios" },
      // ============ AQUÍ ESTÁ LA CORRECCIÓN ============
      { data: "area_destino" }, // Antes decía "area_actual"
      // ===============================================
      {
        data: "estado",
        render: function (data) {
          if (data === "EN TRAMITE")
            return `<span class="badge badge-primary">${data}</span>`;
          if (data === "FINALIZADO")
            return `<span class="badge badge-success">${data}</span>`;
          if (data === "ARCHIVADO")
            return `<span class="badge badge-secondary">${data}</span>`;
          if (data === "RECHAZADO")
            return `<span class="badge badge-danger">${data}</span>`;
          return `<span class="badge badge-light">${data}</span>`;
        },
      },
      {
        data: "archivo_pdf",
        render: function (data) {
          if (data) {
            return `<a href="../storage/documentos/${data}" target="_blank" class="btn btn-danger btn-sm" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>`;
          }
          return "";
        },
      },
      { data: "proveido" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
    },
    select: true,
  });
}

// ---- LÓGICA DE EVENTOS (CLICS) ----
// Se ejecuta una vez que el documento HTML está completamente cargado.
$(document).ready(function () {
  // 1. Cargamos la tabla de trámites al iniciar.
  listar_tramite();

  // 2. ABRIR MODAL DE REGISTRO
  $("#btn_nuevo_tramite").click(function () {
    $("#formulario_registro_tramite")[0].reset();

    const urlTiposDoc =
      "../controller/tramite/controlador_listar_tipos_documento_combo.php";
    const urlAreas = "../controller/tramite/controlador_listar_area_combo.php";

    // Cargar Tipos de Documento en el combo
    $.post(urlTiposDoc).done(function (resp) {
      let data = JSON.parse(resp);
      let cadena = "<option value=''>Seleccione...</option>";
      if (data.length > 0) {
        data.forEach((item) => {
          cadena += `<option value='${item.id}'>${item.nombre}</option>`;
        });
      }
      $("#combo_tipo_documento").html(cadena);
    });

    // Cargar Áreas de Destino en el combo
    $.post(urlAreas).done(function (resp) {
      let data = JSON.parse(resp);
      let cadena = "<option value=''>Seleccione...</option>";
      if (data.length > 0) {
        data.forEach((item) => {
          cadena += `<option value='${item.id}'>${item.nombre}</option>`;
        });
      }
      $("#combo_area_destino").html(cadena);
    });

    $("#modal_registro_tramite").modal("show");
  });

  // 3. GUARDAR NUEVO TRÁMITE
  $("#btn_guardar_tramite").click(function () {
    if (
      $("#combo_tipo_documento").val() === "" ||
      $("#nro_documento").val().trim() === "" ||
      $("#asunto").val().trim() === "" ||
      $("#nro_folios").val().trim() === "" ||
      $("#combo_area_destino").val() === ""
    ) {
      return Swal.fire(
        "Campos Vacíos",
        "Por favor, complete todos los campos marcados con (*).",
        "warning"
      );
    }

    var formData = new FormData($("#formulario_registro_tramite")[0]);

    $.ajax({
      url: "../controller/tramite/controlador_registrar_tramite.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.trim() === "OK") {
          $("#modal_registro_tramite").modal("hide");
          Swal.fire(
            "¡Éxito!",
            "Trámite registrado y enviado correctamente.",
            "success"
          );
          // Usamos la variable global para recargar la tabla
          tablaTramite.ajax.reload();
        } else if (response.trim() === "ERROR_TYPE") {
          Swal.fire(
            "Error de Archivo",
            "El archivo debe ser de tipo PDF.",
            "error"
          );
        } else if (response.trim() === "ERROR_SIZE") {
          Swal.fire(
            "Error de Archivo",
            "El archivo no debe pesar más de 5MB.",
            "error"
          );
        } else {
          Swal.fire(
            "Error",
            "No se pudo completar el registro del trámite.",
            "error"
          );
        }
      },
      error: function () {
        Swal.fire(
          "Error de Conexión",
          "No se pudo comunicar con el servidor.",
          "error"
        );
      },
    });
  });
});
