<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Compra actualizada con éxito";
    } else {
        $mensajeAlerta = "Compra creada con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-compras/operacion/guardar/productos'); ?>">
    <div id="modalAgregarProducto" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar producto internacional' : 'Agregar producto internacional');?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="paisId" name="paisId" value="<?php echo $paisId;?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?php echo $operacion;?>">
                    <input type="hidden" id="compraDetalleId" name="compraDetalleId" value="<?php echo $compraDetalleId;?>">
                    <input type="hidden" id="compraId" name="compraId" value="<?php echo $compraId;?>">

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectProductos" id="selectProductos" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($producto as $producto){ ?>
                                        <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="costoUnitario" name="costoUnitario" class="form-control" value="<?= $campos['precioUnitario']; ?>" required>
                                <label class="form-label" for="costoUnitario">Costo unitario</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control" value="<?= $campos['cantidadProducto']; ?>" required>
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="costoTotal" name="costoTotal" class="form-control" value="<?= $campos['totalCompraDetalle']; ?>" required readonly>
                                <label class="form-label" for="costoTotal">Costo total</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Agregar producto
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i>
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>

    function calcularTotales(precioUnitario, cantidad) {
        if(cantidad == "") {
            $("#costoTotal").val('0.00');
        } else if(cantidad < 0) {
            $("#costoTotal").val('0.00');
        } else {
            let costoTotal = parseFloat(precioUnitario * cantidad);

            $("#costoTotal").val(costoTotal.toFixed(2));
        }
    }

    $(document).ready(function() {
        $("#selectProductos").select2({
            placeholder: 'Productos',
            dropdownParent: $('#modalAgregarProducto')
        });    

        $("#cantidadProducto").keyup(function(e) {
            calcularTotales($("#costoUnitario").val(), $(this).val());
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
                        $('#modalAgregarProducto').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaContinuarCompra").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.compraDetalleId);
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
        
        $("#selectProductos").val('<?= $campos['productoId']; ?>').trigger("change");
    });
</script>
