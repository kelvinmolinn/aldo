
<form id="frmModal">
    <div id="modalUsuarioSucursal" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Sucursal al usuario: <?= $nombreCompleto; ?></h5>
            </div>
            <div class="modal-body">
            <input type="hidden" id="empleadoId" name="empleadoId" value="<?= $empleadoId; ?>">
            <input type="hidden" id="nombreCompleto" name="nombreCompleto" value="<?= $nombreCompleto; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-select-control">
                            <select name="selectSucursales" id="selectSucursales" style = "width: 100%;">
                                <option></option>
                                <?php foreach ($sucursales as $sucursales) : ?>
                                    <option value="<?php echo $sucursales['sucursalId']; ?>"><?php echo $sucursales['sucursal']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" id="btnAgregarSucursal" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="$('#modalUsuarioSucursal').modal('hide');">
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
        $("#selectSucursales").select2({
            placeholder: 'Sucursales'
        });

        $('#btnAgregarSucursal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-usuarios/operacion/agregar/usuario/sucursal'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalUsuarioSucursal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucursal agregada con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                              // Recargar la DataTable después del insert
                              window.location.href = "<?= site_url('conf-general/admin-usuarios/vista/usuario/sucursal/' . $empleadoId . '/' . $nombreCompleto); ?>";
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
        });
    });
</script>