<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Tipo de producto actualizado con éxito";
    } else {
        $mensajeAlerta = "Tipo de producto creado con éxito";
    }
?>

<form id="frmModal" action="<?= base_url('inventario/admin-tipo/operacion/guardar/tipo')?>" method="POST">
    <div id="modalTipo" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Tipo de producto' : 'Nuevo Tipo de producto'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productoTipoId" name="productoTipoId" value="<?= $campos['productoTipoId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="productoTipo" name="productoTipo" class="form-control "  value="<?= $campos['productoTipo']; ?>" required>
                                <label class="form-label" for="productoTipo">Tipo de producto</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarTipo" class="btn btn-primary">
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
                        $('#modalTipo').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblTipo").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.productoTipoId);
                    } else {
                        // Insert fallido, mostrar mensaje de error con Sweet Alert
                        Swal.fire({
                            icon: 'error',
                            title: 'No se completó la operación',
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
