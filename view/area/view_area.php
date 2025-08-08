<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">MANTENIMIENTO ÁREA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Área</li>
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
                        <h5 class="m-0">Listado de Áreas</h5>
                        <button class="btn btn-success btn-sm float-right" id="btn_nuevo_registro">
                            <i class="fas fa-plus"></i> Nuevo Registro
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabla_area" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Área</th>
                                    <th>Fecha Registro</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
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

<div class="modal fade" id="modal_registro_area" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Registro de Nueva Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario_registro_area">
                    <div class="form-group">
                        <label for="nombre_area">Nombre del Área</label>
                        <input type="text" class="form-control" id="nombre_area" required
                            placeholder="Ej: Contabilidad">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edicion_area" tabindex="-1" aria-labelledby="modalEdicionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEdicionLabel">Editar Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario_edicion_area">
                    <input type="hidden" id="id_area_editar">
                    <div class="form-group">
                        <label for="nombre_area_editar">Nombre del Área</label>
                        <input type="text" class="form-control" id="nombre_area_editar" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/console_area.js"></script>