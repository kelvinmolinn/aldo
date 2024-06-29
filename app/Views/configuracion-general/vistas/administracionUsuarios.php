<h2>Administración de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalUsuario({usuarioId: '0',empleadoId: '0', operacion: 'insertar'});">
            <i class="fas fa-plus-circle"></i>
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
        </tbody>
    </table>
</div>
<script>
    function ActivarDesactivarUsuario(campos){
        Swal.fire({
            title: campos.mensaje,
            text: campos.mensaje2,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estado',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                $.ajax({
                    url: '<?php echo base_url('conf-general/admin-usuarios/operacion/estado/usuario'); ?>',
                    type: 'POST',
                    data: campos,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Se cambió el estado con éxito!',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tblEmpleados").DataTable().ajax.reload(null, false);
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

    function modalUsuario(campos) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('conf-general/admin-usuarios/form/empleados/usuarios'); ?>',
                type: 'POST',
                data: campos, // Pasar el ID del módulo como parámetro
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
    }
    
    $(document).ready(function() {
        tituloVentana('Usuarios');

        $('#tblEmpleados').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-usuarios/tabla/usuarios'); ?>',
                "data": {
                    x: ''
                }
            },
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "20%"}, 
                { "width": "15%"},
                { "width": "15%"}
            ]
        });
    });
</script>