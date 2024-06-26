<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Plataforma actualizada con éxito";
    } else {
        $mensajeAlerta = "Plataforma creada con éxito";
    }
?>

<form id="frmModal" action="<?= base_url('inventario/admin-plataforma/operacion/guardar/plataforma')?>" method="POST">
    <div id="modalPlataforma" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Plataforma' : 'Nueva Plataforma'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productoPlataformaId" name="productoPlataformaId" value="<?= $campos['productoPlataformaId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="productoPlataforma" name="productoPlataforma" class="form-control " value="<?= $campos['productoPlataforma']; ?>" required>
                                <label class="form-label" for="productoPlataforma">Plataforma</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarPlataforma" class="btn btn-primary">
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
                        $('#modalPlataforma').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblPlataforma").DataTable().ajax.reload(null, false);
                            
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
