<?php
    if($operacion == "editar") {
        $mensajeAlerta = "UDM actualizado con exito";
    } else {
        $mensajeAlerta = "UDM creado con exito";
    }
?>

<form id="frmModal">
    <div id="modalUnidades" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar UDM' : 'Nuevo UDM') . " " . $directorio; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="unidadMedidaId" name="unidadMedidaId" value="<?= $campos['unidadMedidaId']; ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" Id="unidadMedida" name="unidadMedida" class="form-control " placeholder="Unidad de medida" value="<?= $campos['unidadMedida']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" class="form-control " id="abreviaturaUnidadMedida" name="abreviaturaUnidadMedida" placeholder="]Abreviatura" value="<?= $campos['abreviaturaUnidadMedida']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnguardarUnidad" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
        $(document).ready(function() {
            $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalUnidades').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Unidad de medida agregada con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                            // Recargar la DataTable después del insert
                            $("#tblUnidades").DataTable().ajax.reload(null, false);
                        });
                        console.log("Último ID insertado:", response.empleadoId);
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
        });

        $('#btnguardarUnidad').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('inventario/admin-unidades/operacion/guardar/unidades'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                        $('#modalModulos').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?= $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#miTabla").DataTable().ajax.reload(null, false);
                        });
                    } else {
                        // Insert fallido, mostrar mensaje de error con Sweet Alert
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
        });
        });
</script>