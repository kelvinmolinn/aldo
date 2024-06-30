<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalErrorDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Error en el DTE - Núm. DTE: 1
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label ><b>Error al certificar:</b> </label> Documento de identidad  erroneo, verifique los datos del cliente
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
