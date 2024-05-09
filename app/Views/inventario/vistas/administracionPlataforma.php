
<h2>Plataformas</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalPlataforma(0, 'insertar');">
            <i class="fas fa-save"></i>
            Nueva Plataforma
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblPlataforma" name = "tblPlataforma" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Plataforma</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
   function eliminarPlataforma(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar la plataforma?',
                text: "Se eiminara la plataforma seleccionada.",
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
                            url: '<?php echo base_url('inventario/admin-plataforma/operacion/eliminar/plataforma'); ?>',
                            type: 'POST',
                            data: {
                                productoPlataformaId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Plataforma eliminada con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblPlataforma").DataTable().ajax.reload(null, false);
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
    function modalPlataforma(productoPlataformaId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-plataforma/form/plataforma'); ?>',
                type: 'POST',
                data: { productoPlataformaId: productoPlataformaId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalPlataforma').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
    tituloVentana("Plataforma de producto");
    $('#tblPlataforma').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-plataforma/tabla/plataforma'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "55%", "targets": 1 }, 
                { "width": "35%", "targets": 2 }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
        });
    });
</script>
