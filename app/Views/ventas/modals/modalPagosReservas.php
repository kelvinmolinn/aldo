<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalPagoReservas" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">pago de la reserva - Número de reserva: 1<?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectFormaPago" id="selectFormaPago" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Efectivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="numeroComprobante" name="numeroComprobante" class="form-control active" required>
                                <label class="form-label" for="numeroComprobante">Número de comprobante</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="numeroComprobante" name="numeroComprobante" class="form-control active" required>
                                <label class="form-label" for="numeroComprobante">Monto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaPago" name="fechaPago" class="form-control active" required>
                                <label class="form-label" for="fechaPago">Fecha de pago</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="comentarioPago" name="comentarioPago" class="form-control active" required>
                                <label class="form-label" for="comentarioPago">Comentario</label>
                            </div>
                        </div>
                    </div>      
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaPagoReserva" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pago</th>
                                    <th>fecha</th>
                                    <th>Monto</th>
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
        $("#selectFormaPago").select2({
            placeholder: "Forma pago"
        });

        $('#tablaPagoReserva').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-reservas/tabla/pago/ventas'); ?>',
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
