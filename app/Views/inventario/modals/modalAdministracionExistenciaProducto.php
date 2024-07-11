<form id="frmModal">
    <div id="modalExistenciaProducto" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('Existencia de productos'); ?></h5>
                </div>
                <div class="modal-body">

                <div class= "table-responsive">
                    <table id="tblExistenciaProducto" name = "tblExistenciaProducto" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sucursal</th>
                                <th>Existencia</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
        $('#tblExistenciaProducto').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/existenciaProducto'); ?>',
                "data": {
                    productoId: '<?= $productoId; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "45%"}, 
                { "width": "45%"}
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>