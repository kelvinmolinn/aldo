<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalAnularReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Anular reserva - numero de reserva 1 
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-6">
                            <label>Informacion de la reserva</label>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>Sucursal: </label> Aldo Games Store (Principal)
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de la reserva: </label> 25/06/2024
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <label>Cliente: </label> Cliente Prueba
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="motivosAnulacion" name="motivosAnulacion" class="form-control" value="">
                                <label class="form-label" for="motivosAnulacion">Motivo de la anulación</label>
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


    });
</script>
