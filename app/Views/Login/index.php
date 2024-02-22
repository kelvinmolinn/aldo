<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tienda Aldo Game Store | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/bootstrap/css/bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/bootstrap/css/adminlte.min.css">

  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/bootstrap/css/login.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>Aldo Games Store</b>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
        <img class="login mb-4" src="<?php echo base_url();?>../assets/plugins/img/algo_game_store.jpg">

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo electrónico">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary mb-4">
              <input type="checkbox" id="remember">
              <label for="remember">
                Mostrar contraseña
              </label>
            </div>
          </div>
          <!-- /.col -->
        
          <!-- /.col -->
        </div>
        <div class="row">
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesion</button>
        </div>          
      </form>

      <p class="mt-4">
        <a href="forgot-password.html">¿Olvidó su contraseña?</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/popper.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
