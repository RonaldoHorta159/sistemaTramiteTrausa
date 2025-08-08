<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">SEGUIMIENTO DE TRÁMITES</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Trámites</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Listado General de Trámites</h5>
                        <button class="btn btn-success btn-sm float-right" id="btn_nuevo_tramite">
                            <i class="fas fa-plus"></i> Nuevo Trámite
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabla_tramite" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Código Único</th>
                                    <th>Opciones</th>
                                    <th>N° Doc.</th>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Asunto</th>
                                    <th>Nro Folios</th>
                                    <th>Destino</th>
                                    <th>Estado Destino</th>
                                    <th>PDF</th>
                                    <th>Proveído</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_registro_tramite" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Nuevo Trámite</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formulario_registro_tramite" onsubmit="return false;" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Documento (*)</label>
                                <select class="form-control" id="combo_tipo_documento" name="tipo_doc"
                                    required></select>
                            </div>
                            <div class="form-group">
                                <label>N° de Documento (*)</label>
                                <input type="text" class="form-control" id="nro_documento" name="nro_doc" required>
                            </div>
                            <div class="form-group">
                                <label>N° de Folios (*)</label>
                                <input type="number" class="form-control" id="nro_folios" name="nro_folios" required>
                            </div>
                            <div class="form-group">
                                <label>Área de Destino (*)</label>
                                <select class="form-control" id="combo_area_destino" name="area_destino"
                                    required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Asunto (*)</label>
                                <textarea class="form-control" id="asunto" name="asunto" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Adjuntar PDF (Opcional)</label>
                                <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf"
                                    accept=".pdf">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar_tramite">Registrar Trámite</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/console_tramite.js"></script>