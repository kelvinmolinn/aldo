<form id="frmModal">
    <div id="modalPrecios" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('Actualizar precio de venta'); ?></h5>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalNuevoPrecio(0, 'insertar');">
                            <i class="fas fa-save"></i>
                            Nuevo precio 
                        </button>
                    </div>
                </div>
                <hr>
                <div class= "table-responsive">
                    <table id="tblPrecio" name = "tblPrecio" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Costos anteriores</th>
                                <th>Precios anteriores</th>
                                <th>Fecha y hora del cambio</th>
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
    $('#tblPrecio').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/precio'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "30%"}, 
                { "width": "30%"},
                { "width": "30%"} 
            ],
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>