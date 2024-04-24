<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    //$param1 = $request->getGet('modulo');
?>
<h2>Asignación de menús a módulo: <?php echo  $modulo;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarModulo" class="btn btn-secondary estilo-btn">
            <i class="fas fa-angle-double-left"> </i>
            Volver a Módulo
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary estilo-btn" onclick="modalMenu(<?= $moduloId; ?>, 0, 'insertar');">
            <i class="fas fa-save"></i>
            Nuevo menú
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tblMenus" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Menú</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>

function eliminarMenu(id) {
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el menú?',
                text: "Se eiminara el menú seleccionado.",
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
                            url: '<?php echo base_url('conf-general/admin-modulos/operacion/eliminar/menu'); ?>',
                            type: 'POST',
                            data: {
                                menuId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Menú eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        // Recargar la DataTable después del insert
                                        $("#tblMenus").DataTable().ajax.reload(null, false);                                    });
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
    function modalMenu(moduloId, menuId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
            url: '<?php echo base_url('conf-general/admin-modulos/form/modulo/nuevo/menu'); ?>',
            type: 'POST',
            data: {moduloId : moduloId, menuId : menuId, operacion : operacion}, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalMenus').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        $('#btnRegresarModulo').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/admin-modulos/index'); ?>';
        });
        $('#tblMenus').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-modulos/tabla/modulos/menus'); ?>',
                "data": {
                    moduloId: '<?= $moduloId; ?>',
                    modulo: '<?= $modulo; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "35%"}, 
                { "width": "15%"}  
            ],
            "language": {
                "url": "../../../../../../../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
<?= $this->endSection(); ?>