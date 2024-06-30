<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalEmitirDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Emitir DTE 
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectSucursalDTE" id="selectSucursalDTE" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="sucursal">Aldo Games Store (Principal)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoDTE" id="selectTipoDTE" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="DTE">Factura</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaDTE" name="fechaDTE" class="form-control numero" value="" required>
                                <label class="form-label" for="fechaDTE">Fecha emoción</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectCliente" id="selectCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="sucursal">Cliente prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectEmpleado" id="selectEmpleado" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="sucursal">Empleado prueba</option>
                                </select>
                            </div>
                        </div>
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

        $("#selectSucursalDTE").select2({
            placeholder: 'Sucursal',
            dropdownParent: $('#modalEmitirDTE')
        });

        $("#selectTipoDTE").select2({
            placeholder: 'Tipo DTE',
            dropdownParent: $('#modalEmitirDTE')
        });     
        $("#selectCliente").select2({
            placeholder: 'Cliente',
            dropdownParent: $('#modalEmitirDTE')
        });
        $("#selectEmpleado").select2({
            placeholder: 'Vendedor',
            dropdownParent: $('#modalEmitirDTE')
        });
    });
</script>
