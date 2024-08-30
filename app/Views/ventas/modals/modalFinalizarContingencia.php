<form id="frmModal" method="post" action="">
    <div id="modalFinalizarContingencia" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Emitir DTE 
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" required>
                                <label class="form-label" for="fechaInicio">Fecha de inicio</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="time" id="horaInicio" name="horaInicio" class="form-control" required>
                                <label class="form-label" for="horaInicio">Hora de inicio</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaFin" name="fechaFin" class="form-control" required>
                                <label class="form-label" for="fechaFin">Fecha de finalización</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="time" id="horaFin" name="horaFin" class="form-control" required>
                                <label class="form-label" for="horaFin">Hora de finalización</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="tipoContingenciaId" id="tipoContingenciaId" class="form-control" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <textarea name="motivoContingencia" id="motivoContingencia" class="form-control" style="width: 100%;" required></textarea>
                                <label class="form-label" for="motivoContingencia">Motivo de la contingencia</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tablaContingencia" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DTE</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarCliente" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
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
        $("#tipoContingenciaId").select2({
            placeholder: 'Tipo contingencia',
            dropdownParent: $('#modalFinalizarContingencia')
        });

        $('#tablaContingencia').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/facturacion'); ?>',
                "data": function() { 
                    return {
                        x:''
                    }
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0 },   
                { "width": "30%", "targets": 1 }, 
                { "width": "15%", "targets": 2 }, 
                { "width": "20%", "targets": 3 }, 
                { "width": "20%", "targets": 4 }
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



