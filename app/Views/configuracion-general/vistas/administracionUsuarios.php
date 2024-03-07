<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Nuevo usuario
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php //foreach($data as $usuarios){ ?>
                <tr>
                    <td></td>
                    <td>Nombre completo: <?php //echo $usuarios->primerNombre;?><br>
                        Sucursal:               <br>
                        DUI:
                    </td>
                    <td>
                        Correo:         <br>
                        Rol:            <br>
                        En linea:       <br>
                    </td>
                    <td>
                        Activo
                    </td>
                    <td>
                        <button class="btn btn-primary mb-1">
                            <i class="fas fa-pencil-alt"></i>
                            <span class="">Editar Usuario</span>
                        </button><br>
                        <button class="btn btn-success mb-1">Restablecer acceso</button><br>
                        <button class="btn btn-primary mb-1">0 Sucursales</button><br>
                        <button class="btn btn-primary mb-1">Activar</button>
                    </td>
                </tr>
            <?php //} ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#btnAbrirModal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('administracion-usuarios/nuevo-usuario'); ?>',
                type: 'GET',
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalUsuario').modal('show');
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>