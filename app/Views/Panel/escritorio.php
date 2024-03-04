<?php 
$this->extend('Views/Panel/plantilla');
?>
<?php $this->section('menu'); ?>
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                    <p>Configuración usuario
                        <i class="right fas fa-angle-left"></i>
                    </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user-cog"></i>
                        <p> Admin. usuario
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="cargarAdminUsuarios()">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="cargarPermisosUsuarios()">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Permisos</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        
        <li class="nav-item">
        <a href="#" onclick="cerrarSession();" class="nav-link">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <p>Cerrar Sesión</p>
        </a>
        </li>
    </ul>
    </nav>
    <?php $this->endSection(); ?>
    <?php $this->section('contenido'); ?>
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div id = "contenidoGeneral" class="col-sm-12">
                    <h1></h1>
                </div>
            </div>

        </div><!-- /.container-fluid -->

        </section>

        <!-- Main content -->
        <section class="content">

        </section>
        <script>
            function cargarAdminUsuarios() {
                // Realizar la solicitud AJAX
                $.ajax({
                    url: "<?php echo base_url(); ?>../app/Views/configuracion-general/admin-usuarios/vistas/administracionUsuarios.php",
                    type: "GET",
                    success: function(response) {
                        // Insertar el contenido dentro del h1
                        $('#contenidoGeneral').html(response);
                    }
                });
            }
            function cargarPermisosUsuarios() {
                // Realizar la solicitud AJAX
                $.ajax({
                    url: "<?php echo base_url(); ?>../app/Views/configuracion-general/permisos-usuarios/vistas/permisosUsuario.php",
                    type: "GET",
                    success: function(response) {
                        // Insertar el contenido dentro del h1
                        $('#contenidoGeneral').html(response);
                    }
                });
            }
            function cerrarSession(){
                Swal.fire({
                    title: 'Desea cerrar session',
                    text: 'La session terminará!',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButton: '#d33',
                    confirmButtonText: 'Sí, salir' 
                }).then((result) =>{
                    if(result.isConfirmed){
                        window.location.href = "<?php echo base_url('login/cerrarSession'); ?>"
                    }
                })
            }
        </script>
    <?php $this->endSection(); ?>