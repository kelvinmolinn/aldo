<?php
if ($operacion == "editar") {
    $mensajeAlerta = "DTE actualizado con éxito";
} else {
    $mensajeAlerta = "DTE agregado con éxito";
}
?>
<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/NuevaNotaCredito'); ?>">
    <div id="modalProductoNotaCredito" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Producto' : 'Agregar productos a la nota de crédito'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="facturaDetalleId" name="facturaDetalleId" value="<?= $campos['facturaDetalleId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <input type="hidden" id="facturaId" name="facturaId" value="<?= $campos['facturaId']; ?>">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <select name="productoId" id="productoId" class="form-control" style="width: 100%;" required>
                                <option></option>
                                <!-- Este select se llenará dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control active number-input" min="1" step="1" value="<?= $campos['cantidadProducto']; ?>" required>
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="precioUnitario" name="precioUnitario" class="form-control number-input active" value="0.00" readonly required>
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
                                <input type="number" id="porcentajeDescuento" name="porcentajeDescuento" class="form-control active number-input" min="0" max="25" step="0.01" value="<?= $campos['porcentajeDescuento']; ?>" required>
                                <label class="form-label" for="porcentajeDescuento">Porcentaje de descuento</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="precioUnitarioVenta" name="precioUnitarioVenta" class="form-control active number-input" min="0" value="0.00" readonly required>
                                <label class="form-label" for="precioUnitarioVenta">Precio de venta</label>
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="precioUnitarioVentaIVA"></span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="totalDetalle" name="totalDetalle" class="form-control active number-input" value="0.00" required readonly>
                                <label class="form-label" for="totalDetalle">Precio total</label>
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="totalDetalleIVA"></span></small>
                            </div>
                        </div>
                    </div>

                    <!-- Mostrar el IVA Unitario y Total -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="ivaUnitario" name="ivaUnitario" class="form-control number-input active" value="0.00" readonly required>
                                <label class="form-label" for="ivaUnitario">IVA Unitario</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="ivaTotal" name="ivaTotal" class="form-control number-input active" value="0.00" readonly required>
                                <label class="form-label" for="ivaTotal">IVA Total</label>
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

    // Llamada AJAX para cargar productos al abrir el modal
    $.ajax({
        url: 'ventas/admin-facturacion/operacion/select/notaCredito',
        type: "POST",
        dataType: "json",
        data: {
            facturaId: <?= $campos['facturaId']; ?> // Se pasa el 'facturaId' al servidor
        },
        success: function(response) {
            if (response.success) {
                // Vaciar el select de productos antes de llenarlo
                $("#productoId").empty().append('<option></option>');

                // Iterar sobre los productos recibidos y agregarlos al select
                $.each(response.producto, function(index, producto) {
                    $("#productoId").append(
                        '<option value="' + producto.productoId + '" data-precio="' + producto.precioUnitario + '" data-cantidad-original="' + producto.cantidadProducto + '">' + producto.producto + '</option>'
                    );
                });

                // Refrescar Select2 después de llenar el select
                $("#productoId").trigger('change');
            } else {
                console.log("No hay productos disponibles.");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

    // Calcular precios cuando cambie el producto seleccionado
    $("#productoId").change(function() {
        var selectedOption = $(this).find('option:selected');
        var precioUnitario = selectedOption.data('precio');
        var cantidadOriginal = selectedOption.data('cantidad-original');

        // Actualizar los campos con los valores del producto
        $('#precioUnitario').val(precioUnitario).trigger('change');
        $('#hiddenPrecioUnitario').val(precioUnitario);
        $('#cantidadProducto').attr('max', cantidadOriginal); // Establecer el máximo permitido en el campo de cantidad
        actualizarPrecios();
    });

    $('#cantidadProducto, #porcentajeDescuento, #precioUnitario').on('input change', function() {
        // Validar que la cantidad no sea mayor a la cantidad original
        var cantidadOriginal = $("#productoId").find('option:selected').data('cantidad-original');
        var cantidadIngresada = $('#cantidadProducto').val();

        if (parseFloat(cantidadIngresada) > parseFloat(cantidadOriginal)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cantidad ingresada no puede ser mayor que la cantidad original (' + cantidadOriginal + ')'
            });
            $('#cantidadProducto').val(cantidadOriginal); // Revertir a la cantidad original
        } else {
            actualizarPrecios();
        }
    });

    // Función para actualizar precios
    function actualizarPrecios() {
        var precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
        var porcentajeDescuento = parseFloat($('#porcentajeDescuento').val()) || 0;
        var cantidadProducto = parseFloat($('#cantidadProducto').val()) || 0;

        // Calcular el precio unitario de venta
        var precioUnitarioVenta = precioUnitario * (1 - (porcentajeDescuento / 100));
        $('#precioUnitarioVenta').val(precioUnitarioVenta.toFixed(2));

        // Calcular el IVA unitario y total
        var ivaPorcentaje = 13; // Suponiendo un IVA del 13%
        var ivaVenta = (precioUnitarioVenta * ivaPorcentaje) / 100;
        var precioUnitarioVentaIVA = precioUnitarioVenta + ivaVenta;

        // Calcular IVA unitario y total
        var ivaUnitario = precioUnitarioVentaIVA - precioUnitarioVenta;
        var ivaTotal = ivaUnitario * cantidadProducto;

        $('#precioUnitarioVentaIVA').text(precioUnitarioVentaIVA.toFixed(2));
        $('#ivaUnitario').val(ivaUnitario.toFixed(2));
        $('#ivaTotal').val(ivaTotal.toFixed(2));

        // Calcular el total de la reserva
        var totalDetalle = precioUnitarioVenta * cantidadProducto;
        $('#totalDetalle').val(totalDetalle.toFixed(2));

        var totalDetalleIVA = precioUnitarioVentaIVA * cantidadProducto;
        $('#totalDetalleIVA').text(totalDetalleIVA.toFixed(2));
    }

    // Enviar el formulario vía AJAX
    $("#frmModal").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#modalProductoNotaCredito').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo $mensajeAlerta; ?>',
                        text: response.mensaje
                    }).then((result) => {
                        $("#tablaContinuarNotaCredito").DataTable().ajax.reload(null, false);
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

    // Inicializar los precios cuando se carga el formulario
    actualizarPrecios();
    $("#productoId").val(<?= $campos["productoId"]; ?>).trigger('change');
});

</script>
