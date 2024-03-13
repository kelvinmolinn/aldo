<form id="frmModal">
    <div id="modalModulos" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo módulo</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" Id="modulo" name="modulo" class="form-control " placeholder="Módulo" value="<?= $modulo; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" class="form-control " id="iconoModulo" name="iconoModulo" placeholder="Icono" value="<?= $iconoModulo; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="form-select-control">
                                <select name="urlModulo" id="urlModulo" value="<?= $urlModulo; ?>" style="width: 100%;">
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
                url: '<?php echo base_url('xd/nuevo-xd'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                        $('#modalModulos').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Módulo creado con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                              // Recargar la DataTable después del insert
                              $('#miTabla').DataTable().ajax.reload(); // Recargar la tabla
                        });
                        console.log("Último ID insertado:", response.moduloId);

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
        $("#urlModulo").val('<?= $urlModulo; ?>').trigger("change");
    });
</script>
