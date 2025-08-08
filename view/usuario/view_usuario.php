<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">MANTENIMIENTO USUARIO</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Usuario</li>
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
                        <h5 class="m-0">Listado de Usuarios</h5>
                        <button class="btn btn-success btn-sm float-right" id="btn_nuevo_usuario">
                            <i class="fas fa-plus"></i> Nuevo Usuario
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabla_usuario" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Empleado</th>
                                    <th>Área</th>
                                    <th>Rol</th>
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

<div class="modal fade" id="modal_registro_usuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Nuevo Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formulario_registro_usuario" onsubmit="return false;">
                    <div class="form-group">
                        <label>Empleado (sin cuenta)</label>
                        <select class="form-control" id="combo_empleado" style="width:100%;" required></select>
                    </div>
                    <div class="form-group">
                        <label>Área</label>
                        <select class="form-control" id="combo_area" style="width:100%;" required></select>
                    </div>
                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" class="form-control" id="nombre_usuario" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" class="form-control" id="password_usuario" required>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control" id="combo_rol" style="width:100%;" required>
                            <option value="Administrador">Administrador</option>
                            <option value="Usuario" selected>Usuario</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar_usuario">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edicion_usuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Datos del Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_editar">
                <div class="form-group">
                    <label>Empleado</label>
                    <input type="text" class="form-control" id="empleado_editar" disabled>
                </div>
                <div class="form-group">
                    <label>Área</label>
                    <select class="form-control" id="combo_area_editar" style="width:100%;"></select>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select class="form-control" id="combo_rol_editar" style="width:100%;">
                        <option value="Administrador">Administrador</option>
                        <option value="Usuario">Usuario</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select class="form-control" id="combo_estado_editar" style="width:100%;">
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="btn_cambiar_password_modal">Cambiar
                    Contraseña</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_usuario">Actualizar Datos</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_password" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_password">
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" class="form-control" id="nueva_password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_password">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script src="../js/console_usuario.js"></script>