<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary estilo-btn">
            <i class="fas fa-save"></i>
            Nuevo usuario
        </button>
    </div>
</div>
<div class="table-responsive">
    <table id="tblEmpleados" name = "tblEmpleados" class="table table-hover" style="width: 100%;">
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
            <?php 
                $n = 0;
                //var_dump($empleados);
                foreach($empleados as $empleados){ 
                    $n++;
                    $nombreCompleto = $empleados['primerNombre'].' '.$empleados['segundoNombre'].' '.$empleados['primerApellido'].' '.$empleados['segundoApellido'];
                    $usuarioId = $empleados['usuarioId'];
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><b>Nombre completo: </b><?php echo $empleados['primerNombre'].' '.$empleados['segundoNombre'].' '.$empleados['primerApellido'].' '.$empleados['segundoApellido']; ?><br>
                        <b>DUI: </b><?php echo $empleados['dui']; ?>
                    </td>
                    <td>
                        <b>Correo: </b><?php echo $empleados['correo']; ?>  <br>
                        <b>Rol: </b><?php echo $empleados['rol']; ?>        <br>
                        <b>En linea: </b>                                   <br>
                    </td>
                    <td>
                        <?php echo $empleados['estadoEmpleado'];?>
                    </td>
                    <td>
                        <button class="btn btn-primary mb-1">
                            <i class="fas fa-pencil-alt"></i>
                            <span class=""></span>
                        </button>
                        <button class="btn btn-success mb-1">Restablecer acceso</button>
                        
                        <a href="<?= site_url('conf-general/usuario-sucursal/' . $usuarioId . '/' . $nombreCompleto); ?>" class="btn btn-primary mb-1" data-toggle="tooltip" data-placement="top" title="Sucursales">
                            <span><?= $empleados['conteo_sucursales'];?></span>
                            <i class="fas fa-store"></i>
                        </a>
                        <?php
                            if($empleados['estadoEmpleado'] == 'Activo'){
                        ?>
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Desactivar" onclick="">
                            <i class="fas fa-ban"></i>
                        </button>
                        <?php }else{?>
                        <button class="btn btn-success mb-1" data-toggle="tooltip" data-placement="top" title="Activar">
                            <i class="fas fa-check"></i>
                        </button>
                        <?php }?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function ActivarDesactivar(){

    }
    
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

        $('#tblEmpleados').DataTable({
            "language": {
                "url": "../../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "35%"}, 
                { "width": "15%"}  
            ]
        });
    });
</script>
<?= $this->endSection(); ?>