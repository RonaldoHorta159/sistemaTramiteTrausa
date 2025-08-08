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
          let seguimientoBtn = `<button class="btn btn-info btn-sm ver-btn" data-id="${row.id}" title="Ver Seguimiento"><i class="fas fa-eye"></i></button>`;
          let derivarBtn = "";
          // El botón "Derivar" solo aparece si el trámite está activo
          if (row.estado_destino === "EN TRAMITE") {
            derivarBtn = ` <button class="btn btn-warning btn-sm derivar-btn" data-id="${row.id}" title="Derivar"><i class="fas fa-share-square"></i></button>`;
          }
          return `<div class="btn-group">${seguimientoBtn}${derivarBtn}</div>`;
        },
      },
      { data: "nro_documento" },
      { data: "fecha_registro" },
      { data: "tipo_documento_nombre" },
      { data: "asunto" },
      { data: "nro_folios" },
      { data: "area_actual" }, // Corregido según la última versión del modelo
      {
        data: "estado_destino",
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
$(document).ready(function () {
  // 1. Cargamos la tabla de trámites al iniciar.
  listar_tramite();

  // 2. ABRIR MODAL DE REGISTRO
  $("#btn_nuevo_tramite").click(function () {
    $("#formulario_registro_tramite")[0].reset();

    // Cargar Tipos de Documento en el combo
    $.post(
      "../controller/tramite/controlador_listar_tipos_documento_combo.php"
    ).done(function (resp) {
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
    $.post("../controller/tramite/controlador_listar_area_combo.php").done(
      function (resp) {
        let data = JSON.parse(resp);
        let cadena = "<option value=''>Seleccione...</option>";
        if (data.length > 0) {
          data.forEach((item) => {
            cadena += `<option value='${item.id}'>${item.nombre}</option>`;
          });
        }
        $("#combo_area_destino").html(cadena);
      }
    );

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
          tablaTramite.ajax.reload();
        } else {
          Swal.fire(
            "Error",
            "No se pudo completar el registro. Detalles: " + response,
            "error"
          );
        }
      },
    });
  });

  // 4. VER SEGUIMIENTO
  $("#tabla_tramite tbody").on("click", ".ver-btn", function () {
    let data = tablaTramite.row($(this).parents("tr")).data();
    $("#codigo_tramite").text(data.codigo_unico);
    $.ajax({
      url: "../controller/tramite/controlador_ver_seguimiento.php",
      type: "POST",
      data: { id: data.id },
    }).done(function (resp) {
      let historial = JSON.parse(resp);
      let cadena = "";
      if (historial.length > 0) {
        historial.forEach((mov) => {
          let estadoBadge = `<span class="badge badge-info">${mov.estado_movimiento}</span>`;
          if (mov.estado_movimiento === "ENVIADO")
            estadoBadge = `<span class="badge badge-primary">${mov.estado_movimiento}</span>`;
          else if (mov.estado_movimiento === "RECIBIDO")
            estadoBadge = `<span class="badge badge-success">${mov.estado_movimiento}</span>`;
          else if (mov.estado_movimiento === "DERIVADO")
            estadoBadge = `<span class="badge badge-warning">${mov.estado_movimiento}</span>`;
          cadena += `<tr>
                                    <td>${mov.fecha_movimiento}</td>
                                    <td>${mov.area_origen}</td>
                                    <td>${mov.area_destino}</td>
                                    <td>${estadoBadge}</td>
                                    <td>${mov.proveido}</td>
                                    <td>${mov.usuario_nombre}</td>
                               </tr>`;
        });
        $("#cuerpo_seguimiento").html(cadena);
      } else {
        $("#cuerpo_seguimiento").html(
          '<tr><td colspan="6" class="text-center">No se encontraron movimientos.</td></tr>'
        );
      }
      $("#modal_seguimiento").modal("show");
    });
  });

  // 5. ABRIR MODAL PARA DERIVAR
  $("#tabla_tramite tbody").on("click", ".derivar-btn", function () {
    let data = tablaTramite.row($(this).parents("tr")).data();
    $("#id_documento_derivar").val(data.id);
    $("#codigo_tramite_derivar").text(data.codigo_unico);
    $("#area_origen_derivar").val(data.area_actual);

    $.post("../controller/tramite/controlador_listar_area_combo.php").done(
      function (resp) {
        let areas = JSON.parse(resp);
        let cadena = "<option value=''>Seleccione un destino...</option>";
        if (areas.length > 0) {
          areas.forEach((area) => {
            if (area.nombre !== data.area_actual) {
              cadena += `<option value='${area.id}'>${area.nombre}</option>`;
            }
          });
        }
        $("#combo_area_destino_derivar").html(cadena);
      }
    );

    $("#proveido_derivar").val("");
    $("#modal_derivar").modal("show");
  });

  // 6. GUARDAR DERIVACIÓN
  $("#btn_guardar_derivacion").click(function () {
    let id = $("#id_documento_derivar").val();
    let destino = $("#combo_area_destino_derivar").val();
    let proveido = $("#proveido_derivar").val();

    if (!destino || proveido.trim() === "") {
      return Swal.fire(
        "Campos Vacíos",
        "Debe seleccionar un destino y escribir un proveído.",
        "warning"
      );
    }

    $.ajax({
      url: "../controller/tramite/controlador_derivar_tramite.php",
      type: "POST",
      data: { id: id, destino: destino, proveido: proveido },
      success: function (response) {
        if (response.trim() === "OK") {
          $("#modal_derivar").modal("hide");
          Swal.fire(
            "¡Éxito!",
            "El trámite ha sido derivado correctamente.",
            "success"
          );
          tablaTramite.ajax.reload();
        } else {
          Swal.fire("Error", "No se pudo completar la derivación.", "error");
        }
      },
    });
  });
});
