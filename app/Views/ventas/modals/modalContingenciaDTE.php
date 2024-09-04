<form id="frmModal" method="post" action="">
    <div id="modalVerDTEContingencia" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DTE certificados en contingencia 
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                        
                        </div>
                        <div class="col-md-4">
        
                        </div>
                        <div class="col-md-4">
        
                        </div>
                    </div>
                </div>
                <hr>
                <h4>DTE emitidos en contingencia</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaContingenciaHistorial" style="width: 100%;">
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

        $('#tablaContingenciaHistorial').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url(''); ?>',
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



