<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Descargo actualizado con éxito";
    } else {
        $mensajeAlerta = "Descargo agregado con éxito";
    }
?>

<form id="frmModal" action="<?= base_url('inventario/admin-salida/operacion/guardar/NuevaSalida') ?>" method="POST">
    <div id="modalNuevaSalida" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Producto' : 'Agregar productos a la salida'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="descargoDetalleId" name="descargoDetalleId" value="<?= $campos['descargoDetalleId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <input type="hidden" id="descargosId" name="descargosId" value="<?= $campos['descargosId']; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-select-control">
                                <select name="productoId" id="productoId" class="form-control " style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($producto as $producto) : ?>
                                        <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadDescargo" name="cantidadDescargo" class="form-control number-input" min="1"  value="<?= $campos['cantidadDescargo']; ?>" required>
                                <label class="form-label" for="cantidadDescargo">Cantidad de descargo</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-outline">
                                <input type="text" id="obsDescargoDetalle" name="obsDescargoDetalle" class="form-control"  value="<?= $campos['obsDescargoDetalle']; ?>" required>
                                <label class="form-label" for="obsDescargoDetalle">Observación de la salida/descargo</label>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnGuardarExistencia" class="btn btn-primary">
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
    // Inicializar Select2
    $("#productoId").select2({ placeholder: 'Producto' });
   
    // Evitar la entrada de 'e', 'E', '+', y '-' en los campos de número
    document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        });
    });

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
                        $('#modalNuevaSalida').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblContinuarSalida").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.descargoDetalleId);
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
    $("#productoId").val('<?= $campos["productoId"]; ?>').trigger("change");
});
</script>
