<h2>Nueva compra</h2>
<hr>
<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="tipoDocumento" id="tipoDocumento" style="width: 100%;" required>
                    <option value=""></option>
                    <?php foreach ($tipoDTE as $tipoDTE){ ?>
                        <option value="<?php echo $tipoDTE['tipoDTEId']; ?>"><?php echo $tipoDTE['tipoDocumentoDTE']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="number" id="numeroFactura" name="numeroFactura" class="form-control" required>
                <label class="form-label" for="numeroFactura">Numero de factura</label>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control" required>
                <label class="form-label" for="fechaFactura">Fecha Factura</label>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="selectTipoDTE" id="selectTipoDTE" style="width: 100%;" required>
                    <option value=""></option>
                    <?php foreach ($selectProveedor as $selectProveedor){ ?>
                        <option value="<?php echo $selectProveedor['proveedorId']; ?>"><?php echo $selectProveedor['proveedor']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="selectPais" id="selectPais" style="width: 100%;" required>
                    <option value=""></option>
                    <?php foreach ($selectPais as $selectPais){ ?>
                        <option value="<?php echo $selectPais['paisId']; ?>"><?php echo $selectPais['pais']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="selectRetaceo" id="selectRetaceo" style="width: 100%;" required>
                    <option value=""></option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Generar compra
        </button>
    </div>
</form>
<script>
    $(document).ready(function(){

    	$("#tipoDocumento").select2({
            placeholder: 'Tipo documento'
        });
        $("#selectTipoDTE").select2({
            placeholder: 'Tipo de factura'
        });
        $("#selectPais").select2({
            placeholder: 'Pais'
        });
        $("#selectRetaceo").select2({
            placeholder: 'Aplica retaceo'
        });        
    })
</script>