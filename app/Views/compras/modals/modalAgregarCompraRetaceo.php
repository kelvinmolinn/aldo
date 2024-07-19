<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-retaceo/operacion/compra/retaceo'); ?>">
    <div id="modalNuevoRetaceoCompra" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compra - Retaceo</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="retaceoId" id="retaceoId" value="<?= $retaceoId ?>">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectCompraRetaceo[]" id="selectCompraRetaceo" multiple style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($campos as $campos){ ?>
                                        <option value="<?php echo $campos['compraId']; ?>"><?php echo $campos['numFactura']; ?> - $<?php echo $campos['totalCompraDetalle']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarCompraRetaceo" class="btn btn-primary">
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
    $(document).ready(function(){
        $("#selectCompraRetaceo").select2({
            placeholder: 'Compras',
            dropdownParent: $('#modalNuevoRetaceoCompra')
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
                        $('#modalNuevoRetaceoCompra').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Compra agregada al retaceo con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaContinuarRetaceo").DataTable().ajax.reload(null, false);
                            calcularRetaceo();
                        });
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
        /*
            Va a programar el submit de Guardar para hacer INSERT a retaceo detalle con los campos en el Controller:
            En el controller hará un foreach de la compraId que se quiere agregar (POST selectCompraRetaceo) para traer los campos de compras_detalle
            retaceoId = POST, 
            compraDetalleId = compras_detalle compraDetalleId, 
            cantidadProducto = compras_detalle cantidadProducto, 
            precioFOBUnitario = compras_detalle precioUnitario
            importe = compras_detalle totalCompraDetalle (precioUnitario x Cantidad)

            Luego el Ajax de acá, debe mandar a llamar la function calcularRetaceo (que está en la pageContinuarRetaceo y la podemos utilizar aquí)
        */
    })
</script>