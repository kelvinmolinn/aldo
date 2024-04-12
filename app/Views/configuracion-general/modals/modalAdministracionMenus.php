<form id="frmModal">
    <div id="modalMenus" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar menu' : 'Nuevo menú'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <input type="hidden" id="moduloId" name="moduloId" value="<?= $moduloId; ?>">
                    <input type="hidden" id="menuId" name="menuId" value="<?= $menuId; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" Id="menu" name="menu" class="form-control " placeholder="Menú" value="<?= $campos['menu']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" class="form-control " id="iconoMenu" name="iconoMenu" placeholder="Icono" value="<?= ($operacion == 'editar' ? $campos["iconoMenu"] : 'fas fa-'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="form-select-control">
                                <select name="urlMenu" id="urlMenu" style="width: 100%;">
                                    <?php foreach ($archivos as $archivos) { ?>
                                        <option value="<?= esc($archivos) ?>"><?= esc($archivos) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnMenus" class="btn btn-primary">
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
    $("#urlMenu").select2({
        placeholder: 'Ruta'
    });

    $('#btnMenus').on('click', function() {
        // Realizar una petición AJAX para obtener el contenido de la modal
        $.ajax({
            url: '<?php echo base_url('conf-general/admin-modulos/operacion/guardar/menu'); ?>',
            type: 'POST',
            data: $("#frmModal").serialize(),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                    $('#modalMenus').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Menú creado con Éxito!',
                        text: response.mensaje
                    }).then((result) => {
                        // Recargar la DataTable después del insert
                        $('#tblMenus').DataTable().ajax.reload(); // Recargar la tabla
                    });
                    console.log("Último ID insertado:", response.menuId);

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

    $("#urlMenu").val('<?= $campos["urlMenu"]; ?>').trigger("change");
});

</script>
