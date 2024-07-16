<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalNuevoRetaceo" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compra - Retaceo</h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectCompraRetaceo[]" id="selectCompraRetaceo" multiple style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">1234 - $ 150.60</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        </div>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
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
            dropdownParent: $('#modalNuevoRetaceo')
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