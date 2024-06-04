
<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalAgregarProducto" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar producto nacional' : 'Agregar producto nacional');?></h5>
                </div>
                <div class="modal-body">
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
    $(document).ready(function() {
        $("#selectProductos").select2({
            placeholder: 'Productos',
            dropdownParent: $('#modalAgregarProducto')
        });    

        
        $("#selectProductos").val('<?= $campos['productoId']; ?>').trigger("change");

        function actualizarCosto() {
            var costoUnitario = parseFloat($("#costoUnitario").val());
            var cantidad = parseFloat($("#cantidadProducto").val());

            
            if (!isNaN(costoUnitario) && costoUnitario !== 0) {
                var iva = costoUnitario * 1.13; // Suponiendo que el IVA es del 16%
                var costoConIVA = costoUnitario + iva;
                $("#precioUnitarioIVA").text(costoConIVA.toFixed(2));
            } else{
                $("#precioUnitarioIVA").text("0.00");
            }
        }

        // Actualizar cuando se cambia el valor de costoUnitario
        $("#costoUnitario").on('input', function() {
            actualizarCosto();
        });

        // Inicializar el valor al cargar
        actualizarCosto();
        
    });
</script>
