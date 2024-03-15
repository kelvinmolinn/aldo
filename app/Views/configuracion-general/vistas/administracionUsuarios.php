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
                    $estadoUsuario = $empleados['estadoEmpleado'] ;
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
                            $mensaje = "¿Estás seguro que desea Desactivar el usuario?";
                            $mensaje2 = "pasara a Desactivado";
                        ?>
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Desactivar" onclick="ActivarDesactivarUsuario({usuarioId: <?= $usuarioId; ?>, estadoUsuario: '<?= $estadoUsuario; ?>'});">
                            <i class="fas fa-ban"></i>
                        </button>
                        <?php }else{
                            $mensaje = "¿Estás seguro que desea Activar el usuario?";
                            $mensaje2 = "pasara a Activo";
                        ?>
                        <button class="btn btn-success mb-1" data-toggle="tooltip" data-placement="top" title="Activar" onclick="ActivarDesactivarUsuario({usuarioId: <?= $usuarioId; ?>, estadoUsuario: '<?= $estadoUsuario; ?>'})">
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
    function ActivarDesactivarUsuario(campos){
        Swal.fire({
                title: '<?= $mensaje; ?>',
                text: '<?= $mensaje2; ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '<?php echo base_url('administracion-modulos/activar-desactivar-usuario'); ?>',
                            type: 'POST',
                            data: campos,
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Se cambió el estado con éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        // Recargar la DataTable después del update
                                        window.location.href = "<?= site_url('conf-general/administracion-usuarios'); ?>";
                                    });
                                } else {
                                    // update fallido, mostrar mensaje de error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.mensaje
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Manejar errores si los hay
                                console.error(xhr.responseText);
                            }
                        });
                }
            });
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