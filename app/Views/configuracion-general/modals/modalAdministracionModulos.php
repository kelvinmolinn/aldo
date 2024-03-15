<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Modulo actualizado con exito";
    } else {
        $mensajeAlerta = "Modulo creado con exito";
    }
?>
<form id="frmModal">
    <div id="modalModulos" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar módulo' : 'Nuevo módulo'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="moduloId" name="moduloId" value="<?= $campos['moduloId']; ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" Id="modulo" name="modulo" class="form-control " placeholder="Módulo" value="<?= $campos['modulo']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" class="form-control " id="iconoModulo" name="iconoModulo" placeholder="Icono" value="<?= $campos['iconoModulo']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="form-select-control">
                                <select name="urlModulo" id="urlModulo" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="ruta">Url (select de las carpetas en la raíz de /modulos)</option>
                                    <option value="ruta2">Url (select de las carpetas en la raíz de /modulos)</option>
                                </select>
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
        $("#urlModulo").select2({
            placeholder: 'Ruta'
        })
        $('#btnguardarUsuario').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general-administracion-modulos/modulo/operacion'); ?>',
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
                            window.location.href = "<?= site_url('conf-general/administracion-modulos'); ?>";
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
        $("#urlModulo").val('<?= $campos['urlModulo']; ?>').trigger("change");
    });
</script>
