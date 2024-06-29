<h2>Asignación de sucursales a usuario: <?php echo  $nombreCompleto;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarUsuarios" class="btn btn-secondary">
            <i class="fas fa-chevron-left"></i>
            Volver a usuarios
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAsignarSucursal" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
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
                            url: '<?php echo base_url('conf-general/admin-usuarios/operacion/eliminar/usuario/sucursal'); ?>',
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
                                        $("#tblSucursales").DataTable().ajax.reload(null, false);
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
        tituloVentana('Usuarios - Sucursales');

        $('#btnAsignarSucursal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-usuarios/form/Usuario/sucursal'); ?>',
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
            cambiarInterfaz('conf-general/admin-usuarios/index', {renderVista: 'No'});
        });

        $('#tblSucursales').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-usuarios/tabla/usuarios-sucursales'); ?>',
                "data": {
                    empleadoId: '<?= $empleadoId; ?>'
                }
            },
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "35%"}
            ]
        });
    });
</script>