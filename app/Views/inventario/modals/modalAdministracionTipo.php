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
                    <button type="button" id="btnguardarTipo" class="btn btn-primary">
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
    $('#btnguardarTipo').on('click', function() {
        // Realizar una petición AJAX para obtener el contenido de la modal
        $.ajax({
            url: '<?php echo base_url('inventario/admin-tipo/operacion/guardar/tipo'); ?>',
            type: 'POST',
            data: $("#frmModal").serialize(),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                    $('#modalTipo').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '<?= $mensajeAlerta; ?>',
                        text: response.mensaje
                    }).then((result) => {
                        $("#tblTipo").DataTable().ajax.reload(null, false);
                    });
                } else {
                    // Insert fallido, mostrar mensaje de error con Sweet Alert
                    let errorMessage = '<ul>';
                    $.each(response.errors, function(key, value) {
                        errorMessage += '<li>' + value + '</li>';
                    });
                    errorMessage += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Verifique las validaciones! No puede ir vacío o Agregar un tipo de producto ya Existente'
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
