<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalEmitirDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Complemento del DTE - Núm. DTE 1 
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectTipoComplemento" id="selectTipoComplemento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Orden de compra</option>
                                    <option value="2">Complemento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="DescripcionComplemento" name="DescripcionComplemento" class="form-control numero" value="" required>
                                <label class="form-label" for="DescripcionComplemento">Descripción</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">
                                <i class="fas fa-save"></i>
                                Agregar complemento
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaComplemento" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Complemento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>                             
                    <div class="modal-footer">
                        <button type="submit" id="btnguardarCliente" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Agregar
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

        $("#selectTipoComplemento").select2({
            placeholder: 'Tipo complemento',
            dropdownParent: $('#modalEmitirDTE')
        });
   

        $('#tablaComplemento').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/complemento/dte'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "20%", "targets": 0 }, 
                { "width": "36%", "targets": 1 }, 
                { "width": "19%", "targets": 2 }
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
