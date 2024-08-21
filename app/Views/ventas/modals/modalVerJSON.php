<form id="frmModal">
    <div id="modalVerJSON" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="facturaId" name="facturaId" value="<?= $facturaId; ?>">
                      <h2>Visualización del JSON N°: <?php echo $facturaId;?> </h2>
                      </div>
                <div class="modal-body">
                    <h1>Aqui va el JSON</h1>
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
    </div>
</form>
<script>
    $(document).ready(function() {
                $('#tablaVerDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/ver/json'); ?>',
                "data": {
                    facturaId: '<?= $facturaId; ?>'
                }
            },
        }); 
    });
</script>
