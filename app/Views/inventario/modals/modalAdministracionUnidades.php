<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Unidad de medida actualizada con exito";
    } else {
        $mensajeAlerta = "Unidad de medida creada con exito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('inventario/admin-unidades/operacion/unidades'); ?>">
    <input type="hidden" id="unidadMedidaId" name="unidadMedidaId" value="<?= $unidadMedidaId; ?>">
    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
    <div id="modalUnidades" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar unidad de medida' : 'Nuevo usuario'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" Id="unidadMedida" name="unidadMedida" class="form-control" placeholder="Unidad de medida" value="<?= $campos['unidadMedida']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" Id="abreviaturaUnidadMedida" name="abreviaturaUnidadMedida" class="form-control" placeholder="Unidad de medida" value="<?= $campos['abreviaturaUnidadMedida']; ?>" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarUsuario" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="$('#modalUnidades').modal('hide');">
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
        });
</script>