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
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control active number-input" min="1" step="1" required >
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
   
                        <div class="col-md-4">
                            <select name="precioUnitario" id="precioUnitario" class="form-control " style="width: 100%;" required>
                                <option></option>
                            </select>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="precioUnitarioIVA"></span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4"> 
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" id="porcentajeDescuento" name="porcentajeDescuento" class="form-control active number-input" min="0" max="25" value="0.00"  required >
                                <label class="form-label" for="porcentajeDescuento">Porcentaje de descuento</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" id="totalReservaDetalle" name="totalReservaDetalle" class="form-control"  required readonly>
                                <label class="form-label" for="totalReservaDetalle">Precio total</label>
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="totalReservaDetalleIVA"></span></small>
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
    
    // Evitar la entrada de 'e', 'E', '+', y '-' en los campos de número
    document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        });
    });
    $(document).ready(function() {
        // Inicializar Select2
        $("#productoId").select2({ 
            placeholder: 'Producto'});

        $("#precioUnitario").select2({ 
            placeholder: 'Precio unitario'});

            $("#productoId").change(function(e) {
                $.ajax({
                    url: 'select/catalogos-hacienda/producto-precio',
                    type: "POST",
                    dataType: "json",
                    data: {
                        productoId: $(this).val()
                    }
                }).done(function(data){
                    $('#precioUnitario').empty();
                    $('#precioUnitario').append("<option></option>");
                    for (let i = 0; i < data.length; i++){
                        $('#precioUnitario').append($('<option>', {
                            value: data[i]['id'],
                            text: data[i]['text']
                        }));
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
