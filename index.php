<?php
// 1. Iniciamos la sesión para poder revisar la "pulsera VIP".
session_start();

// 2. ¿El usuario tiene una pulsera y es auténtica?
//    (¿Ya inició sesión?)
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {

    // 3. ¡Sí! No tiene nada que hacer aquí. Lo mandamos de vuelta a la fiesta.
    //    Redirigimos desde http://localhost/sistemaTramiteTramusa/
    //    hacia http://localhost/sistemaTramiteTramusa/view/index.php
    header('Location: view/index.php');

    // 4. Detenemos el script para que no se muestre el formulario de login.
    exit();
}

// Si el usuario NO tiene la pulsera, el código de arriba se ignora y se muestra
// el resto del archivo, que es el formulario de login.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plantilla/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="plantilla/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b>TRAMUSA </b>SA</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">BIENVENIDO A TRAMITE DOCUMENTARIO</p>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="DNI o USUARIO" id="text_usuario">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="PASSWORD" id="text_contra">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block" onclick="Ingresar()">Ingresar</button>
                    </div>
                    <!-- /.col -->
                </div>


                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
    <script src="plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plantilla/dist/js/adminlte.min.js"></script>

    <script src="js/console_usuario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Obtenemos los elementos una sola vez cuando la página carga
        const rmcheck = document.getElementById('remember');
        const usuarioInput = document.getElementById('text_usuario');
        const passInput = document.getElementById('text_contra');

        // Verificamos si localStorage.checkbox existe y es 'true'
        if (localStorage.checkbox && localStorage.checkbox === "true") {
            rmcheck.checked = true; // Forma correcta de marcar un checkbox
            usuarioInput.value = localStorage.usuario || ""; // Usamos || "" como fallback
            passInput.value = localStorage.pass || "";
        } else {
            rmcheck.checked = false; // Forma correcta de desmarcar
            usuarioInput.value = "";
            passInput.value = "";
        }
    </script>

</body>

</html>