<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">MANTENIMIENTO USUARIO</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Usuario</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Listado de usuarios</h5>
                        <button class="btn btn-success btn-sm float-right" id="btn_nuevo_registro">
                            <i class="fas fa-plus"></i> Nuevo Registro
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
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        new DataTable('#tabla_usuario', {
            responsive: true,
            // Habilitamos el procesamiento del lado del servidor
            processing: true,
            // Configuración para AJAX
            ajax: {
                // URL que apunta a nuestro nuevo controlador
                url: '../controller/usuario/controlador_listar_usuario.php',
                type: 'POST' // O 'GET', según prefieras
            },
            // Definimos qué datos van en qué columnas
            columns: [
                { data: 'id' }, // Corresponde a u.id
                { data: 'nombre_usuario' }, // Corresponde a u.nombre_usuario
                { data: 'empleado_nombre' }, // El nombre completo que concatenamos
                { data: 'area_nombre' }, // El nombre del área
                { data: 'rol' }, // El rol del usuario
                {
                    data: 'estado',
                    // Personalizamos cómo se ve la celda de estado
                    render: function (data, type, row) {
                        if (data === 'ACTIVO') {
                            return '<span class="badge badge-success">ACTIVO</span>';
                        } else {
                            return '<span class="badge badge-danger">INACTIVO</span>';
                        }
                    }
                },
                {
                    data: null,
                    // Columna para los botones de acción
                    render: function (data, type, row) {
                        return '<button class="btn btn-primary btn-sm editar-btn" data-id="' + row.id + '">Editar</button> ' +
                            '<button class="btn btn-danger btn-sm eliminar-btn" data-id="' + row.id + '">Eliminar</button>';
                    }
                }
            ],
            // Opcional: para traducir la tabla al español
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            }
        });
    });
</script>