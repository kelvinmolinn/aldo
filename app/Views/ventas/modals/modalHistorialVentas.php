<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalHistorialVentas" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historial ventas - Cliente: <?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectEstadoFacturas" id="selectEstadoFacturas" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Finalizadas</option>
                                    <option value="2">Anuladas</option>
                                </select>
                            </div>
                        </div>
                    </div>    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaHistorialVentas" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>DTE</th>
                                    <th>Certificación</th>
                                    <th>fecha</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
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
        $("#selectEstadoFacturas").select2({
            placeholder: "Estado de Facturas"
        });

        $('#tablaHistorialVentas').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-clientes/tabla/historial/ventas'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "20%", "targets": 0 }, 
                { "width": "36%", "targets": 1 }, 
                { "width": "19%", "targets": 2 },
                { "width": "10%", "targets": 3 },
                { "width": "15%", "targets": 4 }
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
