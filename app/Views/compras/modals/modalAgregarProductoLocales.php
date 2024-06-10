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
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar producto nacional' : 'Agregar producto nacional');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">

                            <input type="hidden" id="operacion" name="operacion" value="<?php echo $operacion;?>">
                            <input type="hidden" id="ivaMultiplicar" name="ivaMultiplicar" value="<?php echo $ivaMultiplicar;?>">
                            <input type="hidden" id="compraDetalleId" name="compraDetalleId" value="<?php echo $compraDetalleId;?>">
                            <input type="hidden" id="compraId" name="compraId" value="<?php echo $compraId;?>">
                            <input type="hidden" id = "paisId" name="paisId" value="<?php echo $paisId;?>">

                            <div class="form-select-control">
                                <select name="selectProductos" id="selectProductos" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($producto as $producto){ ?>
                                        <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="costoUnitario" name="costoUnitario" class="form-control" value="<?= $campos['precioUnitario']; ?>" required>
                                <label class="form-label" for="costoUnitario">Costo unitario</label>
                            </div>
                            <div class="text-right">
                                <small>Con IVA: $ <span id="precioUnitarioIVA"><?= $campos['precioUnitarioIVA']; ?></span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control" value="<?= $campos['cantidadProducto']; ?>" required>
                                <label class="form-label" for="cantidadProducto">Cantidad</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="IVATotal" name="IVATotal" class="form-control" value="<?= $campos['ivaTotal']; ?>" required readonly>
                                <label class="form-label" for="IVATotal">IVA Total</label>
                            </div>
                             <div class="text-right">
                                <small>IVA unitario: $ <span id="ivaUnitario"> <?= $campos['ivaUnitario']; ?></span></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="costoTotal" name="costoTotal" class="form-control" value="<?= $campos['totalCompraDetalleIVA']; ?>" required readonly>
                                <label class="form-label" for="costoTotal">Costo total</label>
                            </div>
                            <div class="text-right">
                                <small>Sin IVA: $ <span id="costoTotalSinIva"><?= $campos['totalCompraDetalle']; ?></span></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php 
                            if($operacion == "editar"){
                        ?>
                        <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Actualizar producto
                        </button>
                        <?php 
                            } else {
                        ?>
                        <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Agregar producto
                        </button>
                        <?php 
                            } 
                        ?>
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
    function calcularTotales(precioIVA, ivaUnitario, cantidad) {
        if(cantidad == "") {
            $("#IVATotal").val('0.00');
            $("#costoTotal").val('0.00');
            $("#costoTotalSinIva").html('0.00');
        } else if(cantidad < 0) {
            $("#IVATotal").val('0.00');
            $("#costoTotal").val('0.00');
            $("#costoTotalSinIva").html('0.00');
        } else {
            let costoTotal = parseFloat(precioIVA * cantidad);
            let ivaTotal = parseFloat(ivaUnitario * cantidad);
            let costoTotalSinIva = costoTotal / <?= $ivaMultiplicar; ?>;
            
            $("#IVATotal").val(ivaTotal.toFixed(2));
            $("#costoTotal").val(costoTotal.toFixed(2));
            $("#costoTotalSinIva").html(costoTotalSinIva.toFixed(2));
        }
    }

    $(document).ready(function() {
        $("#selectProductos").select2({
            placeholder: 'Productos',
            dropdownParent: $('#modalAgregarProducto')
        });    

        $("#costoUnitario").keyup(function(e) {
            if($(this).val() == "") {
                $("#precioUnitarioIVA").html('0.00');
                $("#ivaUnitario").html('0.00');
                $("#costoTotalSinIva").html('0.00');

            } else if($(this).val() < 0) {
                $("#precioUnitarioIVA").html('0.00');
                $("#ivaUnitario").html('0.00');
                $("#costoTotalSinIva").html('0.00');

            } else {
                let precioUnitarioIVA = parseFloat($(this).val() * <?= $ivaMultiplicar; ?>);
                let ivaUnitario = parseFloat(precioUnitarioIVA - $(this).val());



                $("#precioUnitarioIVA").html(precioUnitarioIVA.toFixed(2));
                $("#ivaUnitario").html(ivaUnitario.toFixed(2));


                calcularTotales(precioUnitarioIVA, ivaUnitario, $("#cantidadProducto").val());
            }
        });
        
        $("#cantidadProducto").keyup(function(e) {
            let precioUnitarioIVA = parseFloat($("#costoUnitario").val() * <?= $ivaMultiplicar; ?>);
            let ivaUnitario = parseFloat(precioUnitarioIVA - $("#costoUnitario").val());
            
            calcularTotales(precioUnitarioIVA, ivaUnitario, $(this).val());
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
