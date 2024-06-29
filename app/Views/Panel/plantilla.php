<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aldo Games | Bienvenido</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/bootstrap/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/bootstrap/css/estilo.css">
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/select2/css/select2.min.css">
  <!-- En el head de tu vista -->
  <link rel="stylesheet" href="<?php echo base_url();?>../assets/plugins/datatables/css/dataTables.min.css">
  <!--<link rel="stylesheet" href="<?php //echo base_url();?>../public/css/input.css">-->

  <!-- jQuery -->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/jquery.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/inputs.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/popper.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/adminlte.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/select2/js/select2.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/datatables/js/dataTables.min.js"></script>
  <script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/jquery.inputmask.min.js"></script>
  <!--<script src="<?php echo base_url();?>../assets/plugins/bootstrap/js/jquery.inputmask.bundle.js"></script>-->

    <style>
    /* Estilo personalizado para cambiar la sombra al pasar el mouse */
    .nav-color:hover {
      box-shadow: 0 4px 8px rgba(255, 255, 255, 0.5); 
    }

    .nav-cerrar-sesion:hover {
      box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.5); /* Sombra de color rojo */
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->

<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <?php
      if(session('defaultPass') == "Propia") {
    ?>
        <a role="button" class="brand-link" onclick="cambiarInterfaz('escritorio/dashboard', {renderVista: 'No'});">
          <img class="brand-image img-circle elevation-3" style="opacity: .8" src="<?php echo base_url();?>../assets/plugins/img/algo_game_store.jpg">
          <span class="brand-text font-weight-light">Aldo Games</span>
        </a>
    <?php 
      } else {
    ?>
        <a role="button" class="brand-link">
          <img class="brand-image img-circle elevation-3" style="opacity: .8" src="<?php echo base_url();?>../assets/plugins/img/algo_game_store.jpg">
          <span class="brand-text font-weight-light">Aldo Games</span>
        </a>
    <?php 
      }
    ?>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url();?>../public/usuarios/fotos/<?php echo session('fotoUsuario') ? session('fotoUsuario') : 'IMG1.PNG'; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?= session('nombreUsuario');?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <?php
        if(session('defaultPass') == "Propia") {
      ?>
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
              <li class="nav-item">
                <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('escritorio/dashboard', {renderVista: 'No'});">
                  <i class="nav-icon fas fa-home"></i>
                  <p>INICIO</p>
                </a>
              </li>
                        <li class="nav-item">
                <a href="#" class="nav-link nav-color">
                  <i class="nav-icon fas fa-cog"></i>
                  <p>Configuración usuario
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                  <li class="nav-item">
                    <a href="#" class="nav-link nav-color">
                      <i class=" fas fa-user-cog"></i>
                      <p> Admin. usuario
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview ml-4">
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('conf-general/admin-usuarios/index', {renderVista: 'No'});">
                          <i class="fas fa-user "></i>
                          <p>Usuarios</p>
                        </a>
                      </li>
                    </ul>
                    <ul class="nav nav-treeview ml-4">
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('conf-general/admin-roles/index', {renderVista: 'No'});">
                          <i class="fas fa-user "></i>
                          <p>Roles</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link nav-color">
                      <i class=" fas fa-wrench"></i>
                      <p> Admin. módulos
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview ml-4">
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('conf-general/admin-modulos/index', {renderVista: 'No'});">
                          <i class="fas fa-tasks "></i>
                          <p>Módulos</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link nav-color">
                  <i class="nav-icon fas fa-shopping-cart"></i>
                  <p>Compras
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview ml-3">
                  <li class="nav-item">
                    <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('compras/admin-proveedores/index', {renderVista: 'No'});">
                      <i class="fas fa-users"></i>
                      <p> Proveedores  
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('compras/admin-compras/index', {renderVista: 'No'});">
                      <i class="fas fa-store"></i>
                      <p> Compras  
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('compras/admin-retaceo/index', {renderVista: 'No'});">
                    <i class="fas fa-list-ol"></i>
                      <p> Retaceo  
                      </p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link nav-color">
                  <i class="nav-icon fas fa-box-open"></i>
                  <p>Inventario
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                    <ul class="nav nav-treeview ml-3">
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-producto/index', {renderVista: 'No'});">
                          <i class="fas fa-box-open"></i>
                          <p>Existencia productos</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-salida/index', {renderVista: 'No'});">
                          <i class="fas fa-arrow-circle-right"></i>
                          <p>Descargos/Salidas</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-traslados/index', {renderVista: 'No'});">
                          <i class="fas fa-truck"></i>
                          <p>Traslados de productos</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-tipo/index', {renderVista: 'No'});">
                          <i class="fas fa-box"></i>
                          <p>Tipos de producto</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a role= "button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-plataforma/index', {renderVista: 'No'});">
                          <i class="fas fa-gamepad"></i>
                          <p>Plataforma</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('inventario/admin-unidades/index', {renderVista: 'No'});">
                          <i class="fas fa-tag"></i>
                          <p>Unidades de medida</p>
                        </a>
                      </li>
                    </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link nav-color">
                  <i class="nav-icon fas fa-money-check-alt"></i>
                  <p>Ventas
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                    <ul class="nav nav-treeview ml-3">
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('ventas/admin-clientes/index', {renderVista: 'No'});">
                          <i class="fas fa-users"></i>
                          <p>Clientes</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('ventas/admin-reservas/index', {renderVista: 'No'});">
                          <i class="fas fa-dolly"></i>
                          <p>Reservas</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz('ventas/admin-facturacion/index', {renderVista: 'No'});">
                          <i class="fas fa-cash-register"></i>
                          <p>Facturación</p>
                        </a>
                      </li>
                    </ul>
              </li>
              <li class="nav-item">
                <a href="#" onclick="cerrarSession();" class="nav-link nav-cerrar-sesion">
                  <i class="fas fa-sign-out-alt"></i>
                  <p>Cerrar Sesión</p>
                </a>
              </li>
            </ul>
          </nav>
      <?php 
        } else {
      ?>
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="#" onclick="cerrarSession();" class="nav-link">
                  <i class="fas fa-sign-out-alt text-danger"></i>
                  <p>Cerrar Sesión</p>
                </a>
              </li>
            </ul>
          </nav>
      <?php
        }
      ?>
      <!--<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        </ul>
      </nav>-->
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div id="divContenido" class="content-wrapper" style="padding-left: 15px; padding-right: 15px;">
    <!-- Content Header (Page header) -->
    <?php $this->renderSection('contenido'); ?>
    <!-- /.content -->
  </div>
  <div id="divModalContent"></div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.3.0
    </div>
    <strong>Copyright &copy;</strong> DK development 2024. 
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
<script>
  function cerrarSession(){
    Swal.fire({
      title: '¿Está seguro que desea cerrar sesión?',
      text: 'Recuerde guardar los últimos cambios realizados para evitar perderlos.',
      icon: 'warning',
      showCancelButton: true,
      cancelButton: '#d33',
      confirmButtonText: 'Cerrar sesión' 
    }).then((result) =>{
      if(result.isConfirmed){
        window.location.href = '<?= site_url('cerrarSession'); ?>';
      }
    })
  }
  function tituloVentana(titulo) {
      document.title = 'Aldo Games | ' + titulo;
  }
function cambiarInterfaz(ruta, campos) {
    // Destruir todos los tooltips activos
    $('[data-toggle="tooltip"]').tooltip('dispose');

    $.ajax({
        url: '<?= site_url(); ?>' + ruta,
        type: 'POST',
        data: campos,
        success: function(response) {
            $('#divContenido').html(response);
            // Inicializar los tooltips en la nueva interfaz
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
</script>
</html>
