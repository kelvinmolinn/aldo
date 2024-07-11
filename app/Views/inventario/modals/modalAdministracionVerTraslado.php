
<form id="frmModal">
    <div id="modalAdministracionVerTraslado" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="trasladosId" name="trasladosId" value="<?= $trasladosId; ?>">
                    <h2>Visualización del traslado N°: <?php echo $trasladosId;?> </h2>
                </div>
                <div class="modal-body">

                <div class= "table-responsive">
                    <table id="tblVerTraslado" name = "tblVerTraslado" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Motivo/Justificación</th>
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
        $('#tblVerTraslado').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-traslados/tabla/verTraslado'); ?>',
                "data": {
                     trasladosId: '<?= $trasladosId; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "30%"}, 
                { "width": "30%"},
                { "width": "30%"} 
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