<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de Módulos</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" class="btn btn-primary ttip" onclick="modalModulo(0, 'insertar');">
            <i class="fas fa-user-plus"></i>
            Nuevo módulo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="miTabla" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Módulo</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
        function eliminarModulo(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el módulo?',
                text: "Se eiminara el módulo seleccionado.",
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
                            url: '<?php echo base_url('conf-general/admin-modulos/operacion/eliminar/modulo'); ?>',
                            type: 'POST',
                            data: {
                                moduloId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Módulo eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#miTabla").DataTable().ajax.reload(null, false);
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

    function modalModulo(moduloId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('conf-general/admin-modulos/form/nuevo/modulo'); ?>',
                type: 'POST',
                data: { moduloId: moduloId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalModulos').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
     $(document).ready(function() {
        $('#miTabla').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-modulos/tabla/modulos'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 }, 
                { "width": "15%", "targets": 3 }  
            ],
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
        });
    });
</script>
<?= $this->endSection(); ?>