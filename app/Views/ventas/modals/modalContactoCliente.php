<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalContactoCliente" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contacto del cliente: <?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContactoCliente" id="selectTipoContactoCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Telefono</option>
                                    <option value="2">Correo electronico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="tipoContactoCliente" name="tipoContactoCliente" class="form-control " min ="0" required>
                                <label class="form-label" for="tipoContactoCliente">Contacto</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type= "submit" id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="">
                                <i class="fas fa-save"></i>
                                Nuevo Contacto
                            </button>
                        </div>
                    </div>    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaContactoCliente" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contacto</th>
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
        $("#selectTipoContactoCliente").select2({
            placeholder: "Tipo contacto"
        });

        $("#selectTipoContactoCliente").change(function(e) {
            if($(this).val() == "1") {
                $('#tipoContactoCliente').inputmask('9999-9999');
            } else if($(this).val() == "2") {
                $('#tipoContactoCliente').inputmask('email');
            } else {

            }
        });

        $('#tablaContactoCliente').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-clientes/tabla/contacto/cliente'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "30%", "targets": 2 }
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
