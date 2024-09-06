<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Modulo actualizado con exito";
    } else {
        $mensajeAlerta = "Modulo creado con exito";
    }
?>
<form id="frmModal">
    <div id="modalModulos" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar módulo' : 'Nuevo módulo');?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="moduloId" name="moduloId" value="<?= $campos['moduloId']; ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" Id="modulo" name="modulo" class="form-control " value="<?= $campos['modulo']; ?>" required>
                                <label class="form-label" for="modulo">Módulo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" class="form-control " id="iconoModulo" name="iconoModulo" value="<?= ($operacion == 'editar' ? $campos["iconoModulo"] : 'fas fa-'); ?>" required>
                                <label class="form-label" for="iconoModulo">Icono</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnguardarUsuario" class="btn btn-primary">
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
        $('#btnguardarUsuario').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-modulos/operacion/guardar/modulo'); ?>',
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
