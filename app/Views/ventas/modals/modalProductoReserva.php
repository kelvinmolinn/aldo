<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Reserva actualizada con éxito";
    } else {
        $mensajeAlerta = "Reserva agregada con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-reservas/operacion/guardar/NuevaReserva'); ?>">
    <div id="modalProductosReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Producto' : 'Agregar productos a la reserva'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reservaDetalleId" name="reservaDetalleId" value="<?= $campos['reservaDetalleId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <input type="hidden" id="reservaId" name="reservaId" value="<?= $campos['reservaId']; ?>">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <select name="productoId" id="productoId" class="form-control " style="width: 100%;" required>
                                <option></option>
                                <?php foreach ($producto as $producto) : ?>
                                    <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control active number-input" min="1" required>
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="precioUnitario" name="precioUnitario" class="form-control active" required>
                                <label class="form-label" for="precioUnitario">Precio</label>
                            </div>
                        </div>
                    </div>                                  
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarprodutos" class="btn btn-primary">
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
    });

        // Evitar la entrada de 'e', 'E', '+', y '-' en los campos de número
        document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
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
                        $('#modalProductosReserva').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaContinuarReserva").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.reservaDetalleId);
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
