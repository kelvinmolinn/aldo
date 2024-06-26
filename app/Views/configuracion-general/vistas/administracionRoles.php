<h2>Configuración de roles</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" class="btn btn-primary ttip" onclick="modalRoles(0,'insertar');">
            <i class="fas fa-plus-circle"></i>
            Nuevo rol
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaRoles" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function eliminarRol(id) {
    //alert("Vamos a eliminar " + id);
        Swal.fire({
            title: '¿Estás seguro que desea eliminar el Rol?',
            text: "Se eiminara el Rol seleccionado.",
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
                        url: '<?php echo base_url('conf-general/admin-roles/operacion/eliminar/rol'); ?>',
                        type: 'POST',
                        data: {
                            rolId: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rol eliminado con Éxito!',
                                    text: response.mensaje
                                }).then((result) => {
                                    $("#tablaRoles").DataTable().ajax.reload(null, false);
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
    }
    function modalRoles(rolId,operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('conf-general/admin-roles/form/nuevo/rol'); ?>',
                type: 'POST',
                data: {rolId: rolId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalRol').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana('Roles');
        $('#tablaRoles').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-roles/tabla/roles'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 } 
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>