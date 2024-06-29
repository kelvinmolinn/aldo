<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Nueva Reserva 
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectSucursalReserva" id="selectSucursalReserva" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Aldo Games Store (Principal)</option>
                                    <option value="2">Sucursal #2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectNombreClienteReserva" id="selectNombreClienteReserva" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Cliente Prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="tedatet" id="fechaReserva" name="fechaReserva" class="form-control numero" value="" required>
                                <label class="form-label" for="fechaReserva">Fecha de la reserva</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="comenteariosReserva" name="comenteariosReserva" class="form-control" value="">
                                <label class="form-label" for="comenteariosReserva">Comentarios</label>
                            </div>
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

        $("#selectSucursalReserva").select2({
            placeholder: 'Sucursal',
            dropdownParent: $('#modalReserva')
        });

        $("#selectNombreClienteReserva").select2({
            placeholder: 'Cliente',
            dropdownParent: $('#modalReserva')
        });

    });
</script>
