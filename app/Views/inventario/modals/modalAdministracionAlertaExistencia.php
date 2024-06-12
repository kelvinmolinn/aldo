<form id="frmModal">
    <div id="modalAlertaExistencia" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('EXISTENCIAS MINIMAS DE PRODUCTOS'); ?></h5>
                </div>
                <div class="modal-body">

                <div class= "table-responsive">
                    <table id="tblExistenciasMinimas" name = "tblExistenciasMinimas" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Productos</th>
                                <th>Existencia Actual</th>
                                <th>Existencia Minima</th>
                                <th>Detalle existencia</th>
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
        $('#tblExistenciasMinimas').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/AlertaExistenciaMinima'); ?>',
                "data": {
                    productoId: '<?= $productoId; ?>'
                }
                
            },

            "columnDefs": [
                { "width": "10%"}, 
                { "width": "30%"}, 
                { "width": "30%"},
                { "width": "15%"},
                { "width": "15%"} 
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