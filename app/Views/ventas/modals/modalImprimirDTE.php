<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalImprimirDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imprimir DTE <?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-select-control">
                                <select name="selectSucursal" id="selectSucursal" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Aldo Games Store (Principal)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-outline">
                                <input type="date" id="fechaDTE" name="fechaDTE" class="form-control active" required>
                                <label class="form-label" for="fechaDTE">Fecha del DTE</label>
                            </div>
                        </div>
                    </div> 
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-select-control">
                                <select name="selectDTEImprimir" id="selectDTEImprimir" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Factura (01)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                    </div>
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div>      
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 
                    <div class="row mb-4">
                    </div> 

                             
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-print"></i>
                        Imprimir DTE
                    </button>
                <div class="modal-footer">
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
        $("#selectSucursal").select2({
            placeholder: "Sucursal",
            dropdownParent: $('#modalImprimirDTE')
        });
        $("#selectDTEImprimir").select2({
            placeholder: "DTE a imprimir",
            dropdownParent: $('#modalImprimirDTE')
        });

    });
</script>
