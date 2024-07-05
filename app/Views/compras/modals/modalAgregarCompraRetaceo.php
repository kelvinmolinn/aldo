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
                                <select name="selectCompraRetaceo" id="selectCompraRetaceo" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">C0012 - $ 125.00</option>
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

    })
</script>