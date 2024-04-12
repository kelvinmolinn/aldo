<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>

<h2>Tipos de producto</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalTipo(0, 'insertar');">
            <i class="fas fa-save"></i>
            Nuevo Tipo de producto
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblTipo" name = "tblTipo" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo de producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
   function eliminarTipo(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el tipo de producto?',
                text: "Se eiminara el tipo de producto seleccionado.",
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
                            url: '<?php echo base_url('inventario/admin-tipo/operacion/eliminar/tipo'); ?>',
                            type: 'POST',
                            data: {
                                productoTipoId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Tipo de producto eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblTipo").DataTable().ajax.reload(null, false);
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
    function modalTipo(productoTipoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-tipo/form/tipo'); ?>',
                type: 'POST',
                data: { productoTipoId: productoTipoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalTipo').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
    
    $('#tblTipo').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-tipo/tabla/tipo'); ?>',
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
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
        });
    });
</script>


<?= $this->endSection(); ?>