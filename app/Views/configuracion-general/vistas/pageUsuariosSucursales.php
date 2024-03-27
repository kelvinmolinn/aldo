<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    //$usuarioId = $request->getGet('usuarioId');
    //$nombreCompleto = $request->getGet('nombreCompleto');
?>
<h2>Asignación de sucursales a usuario: <?php echo  $nombreCompleto;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarUsuarios" class="btn btn-secondary">
            <i class="fas fa-angle-double-left"></i>
            Volver a usuarios
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAsignarSucursal" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Asignar sucursal
        </button>
    </div>
</div>
<div class="table-responsive">
    <table id="tblSucursales" name="tblSucursales" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Sucursal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $n = 0;
                foreach($sucursalUsuario as $sucursalUsuario){ 
                    $n++;
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    
                    <td><b>Sucursal: </b><?= $sucursalUsuario['sucursal']?></td>
                    
                    <td>
                        <button class="btn btn-danger" onclick="eliminarSucursal(`<?= $sucursalUsuario['sucursalUsuarioId']; ?>`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function eliminarSucursal(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar la sucursal?',
                text: "Se eiminara la sucursal del usuario seleccionado.",
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
                            url: '<?php echo base_url('usuarios-sucursales/eliminar-usuario-sucursal'); ?>',
                            type: 'POST',
                            data: {
                                sucursalUsuarioId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Sucursal eliminada con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        // Recargar la DataTable después del insert
                                        window.location.href = "<?= site_url('conf-general/usuario-sucursal/' . $empleadoId . '/' . $nombreCompleto); ?>";
                                    });
                                } else {
                                    // Insert fallido, mostrar mensaje de error
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
        /*
            data: {
                sucursalUsuarioId: id
            }
        */
    }

    $(document).ready(function() {
        $('#btnAsignarSucursal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('usuarios-sucursales/agregar-UsuarioSucursal'); ?>',
                data: {
                    empleadoId: <?= $empleadoId; ?>,
                    nombreCompleto: '<?= $nombreCompleto;?>'
                },
                type: 'POST',
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalUsuarioSucursal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });

        $('#btnRegresarUsuarios').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/administracion-usuarios'); ?>';
        });

        $('#tblSucursales').DataTable({
            "language": {
                "url": "../../../../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "35%"}
            ]
        });
    });
</script>
<?= $this->endSection(); ?>