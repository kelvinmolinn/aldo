<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/pdf/generate'); ?>">
    <div id="modalImprimirDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ver DTE <?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <button type= "submit" id="btnPDF" class="btn btn-primary estilo-btn mb-4">
                            <i class="fas fa-save"></i>
                                PDF
                        </button>
                    </div>
                             
                </div>
                
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

    });
</script>
