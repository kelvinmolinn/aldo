<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalProductosReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo producto: <?php //echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectProductoReserva" id="selectProductoReserva" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">(PD-001) MARIO KART</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="cantidadReserva" name="cantidadReserva" class="form-control active" required>
                                <label class="form-label" for="cantidadReserva">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="precioReserva" name="precioReserva" class="form-control active" required>
                                <label class="form-label" for="precioReserva">Precio</label>
                            </div>
                        </div>
                    </div>                                  
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarprodutos" class="btn btn-primary">
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
        $("#selectProductoReserva").select2({
            placeholder: "Producto"
        });

    });
</script>
