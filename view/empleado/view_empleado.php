<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">MANTENIMIENTO EMPLEADO</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Empleado</li>
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
                        <h5 class="m-0">Listado de Empleados</h5>
                        <button class="btn btn-success btn-sm float-right" id="btn_nuevo_empleado">
                            <i class="fas fa-plus"></i> Nuevo Empleado
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabla_empleado" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Celular</th>
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

<div class="modal fade" id="modal_registro_empleado" tabindex="-1" aria-labelledby="modalRegistroLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroLabel">Registro de Nuevo Empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formulario_registro_empleado" onsubmit="return false;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DNI (*)</label>
                                <input type="text" class="form-control" id="dni_registro" required maxlength="8">
                            </div>
                            <div class="form-group">
                                <label>Apellido Paterno (*)</label>
                                <input type="text" class="form-control" id="ape_paterno_registro" required>
                            </div>
                            <div class="form-group">
                                <label>Email (*)</label>
                                <input type="email" class="form-control" id="email_registro" required>
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" class="form-control" id="fec_nacimiento_registro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombres (*)</label>
                                <input type="text" class="form-control" id="nombres_registro" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno (*)</label>
                                <input type="text" class="form-control" id="ape_materno_registro" required>
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" id="celular_registro" maxlength="9">
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="direccion_registro">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar_empleado">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edicion_empleado" tabindex="-1" aria-labelledby="modalEdicionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEdicionLabel">Editar Datos del Empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formulario_edicion_empleado" onsubmit="return false;">
                    <input type="hidden" id="id_empleado_editar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DNI (*)</label>
                                <input type="text" class="form-control" id="dni_editar" required maxlength="8">
                            </div>
                            <div class="form-group">
                                <label>Apellido Paterno (*)</label>
                                <input type="text" class="form-control" id="ape_paterno_editar" required>
                            </div>
                            <div class="form-group">
                                <label>Email (*)</label>
                                <input type="email" class="form-control" id="email_editar" required>
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" class="form-control" id="fec_nacimiento_editar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombres (*)</label>
                                <input type="text" class="form-control" id="nombres_editar" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno (*)</label>
                                <input type="text" class="form-control" id="ape_materno_editar" required>
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" id="celular_editar" maxlength="9">
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="direccion_editar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_empleado">Actualizar</button>
            </div>
        </div>
    </div>
</div>


<script src="../js/console_empleado.js"></script>