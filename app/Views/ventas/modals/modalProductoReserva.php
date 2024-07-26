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
                            <select name="productoId" id="productoId" class="form-control" style="width: 100%;" required>
                                <option></option>
                                <?php foreach ($producto as $producto) : ?>
                                    <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control active number-input" min="1" step="1" value="<?= $campos['cantidadProducto']; ?>" required >
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="precioUnitario" name="precioUnitario" class="form-control number-input active " placeholder="Precio Unitario"  value="<?= $campos['precioUnitario']; ?>"  readonly required>
                                <label class="form-label" for="precioUnitario">Precio Unitario</label>
                                <input type="hidden" name="hiddenPrecioUnitario" id="hiddenPrecioUnitario">
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="precioUnitarioIVA"></span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4"> 
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="porcentajeDescuento" name="porcentajeDescuento" class="form-control active number-input" min="0" max="25"  value="<?= $campos['porcentajeDescuento']; ?>" required >
                                <label class="form-label" for="porcentajeDescuento">Porcentaje de descuento</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="precioUnitarioVenta" name="precioUnitarioVenta" class="form-control active number-input" min="0" value="<?= $campos['precioUnitario']; ?>" readonly required >
                                <label class="form-label" for="precioUnitarioVenta">Precio de venta</label>
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="precioUnitarioVentaIVA"></span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="totalReservaDetalle" name="totalReservaDetalle" class="form-control active number-input" value="0.00" required readonly>
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
            placeholder: 'Producto'
        });

        $("#productoId").change(function() {
            $.ajax({
                url: 'select/catalogos-hacienda/producto-precio',
                type: "POST",
                dataType: "json",
                data: {
                    productoId: $(this).val()
                }
            }).done(function(data) {
                if (data.length > 0) {
                    var precioUnitario = data[0]['text'];
                    $('#precioUnitario').val(precioUnitario).trigger('change');
                    $('#hiddenPrecioUnitario').val(precioUnitario);
                    actualizarPrecios(); // Actualizar precios al seleccionar producto
                }
            });
        });

        // Calcular precios cuando cambie el valor de cantidadProducto, porcentajeDescuento, o precioUnitario
        $('#cantidadProducto, #porcentajeDescuento, #precioUnitario').on('input change', actualizarPrecios);

        function actualizarPrecios() {
            var precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
            var porcentajeDescuento = parseFloat($('#porcentajeDescuento').val()) || 0;
            var cantidadProducto = parseFloat($('#cantidadProducto').val()) || 0;

            // Calcular el precio unitario de venta
            var precioUnitarioVenta = precioUnitario * (1 - (porcentajeDescuento / 100));
            $('#precioUnitarioVenta').val(precioUnitarioVenta.toFixed(2));

            // Calcular el precio unitario de venta con IVA
            var ivaPorcentaje = 13; // Suponiendo un IVA del 13%
            var ivaPrecioUnitario = (precioUnitario * ivaPorcentaje) / 100;
            var precioUnitarioIVA = precioUnitario + ivaPrecioUnitario;
            $('#precioUnitarioIVA').text(precioUnitarioIVA.toFixed(2));

            var ivaVenta = (precioUnitarioVenta * ivaPorcentaje) / 100;
            var precioUnitarioVentaIVA = precioUnitarioVenta + ivaVenta;
            $('#precioUnitarioVentaIVA').text(precioUnitarioVentaIVA.toFixed(2));

            // Calcular el total de la reserva
            var totalReservaDetalle = precioUnitarioVenta * cantidadProducto;
            $('#totalReservaDetalle').val(totalReservaDetalle.toFixed(2));

            // Calcular el total de la reserva con IVA
            var totalReservaDetalleIVA = precioUnitarioVentaIVA * cantidadProducto;
            $('#totalReservaDetalleIVA').text(totalReservaDetalleIVA.toFixed(2));
        }

        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#modalProductosReserva').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaContinuarReserva").DataTable().ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se completó la operación',
                            text: response.mensaje
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Inicializar precios al cargar el formulario
        actualizarPrecios();
        $("#productoId").val(<?= $campos["productoId"]; ?>).trigger('change'); 
    });
</script>
