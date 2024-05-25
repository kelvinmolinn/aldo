<h2>Nueva compra</h2>
<hr>
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
                <input type="number" id="numeroFactura" name="numeroFactura" class="form-control active" required>
                <label class="form-label" for="numeroFactura">Numero de factura</label>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control active" required>
                <label class="form-label" for="fechaFactura">Fecha Factura</label>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="selectProveedor" id="selectProveedor" style="width: 100%;" required>
                    <option value=""></option>
                    <?php 
                        foreach ($selectProveedor as $selectProveedor){ 
                    ?>
                        <option value="<?php echo $selectProveedor['proveedorId']; ?>"><?php echo $selectProveedor['proveedor']; ?></option>
                    <?php 
                        } 
                    ?>
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
            Actualizar compra
        </button>
    </div>
<script>
    $(document).ready(function(){

    	$("#tipoDocumento").select2({
            placeholder: 'Tipo documento'
        });
        $("#selectProveedor").select2({
            placeholder: 'Proveedor'
        });
        $("#selectPais").select2({
            placeholder: 'Pais'
        });
        $("#selectRetaceo").select2({
            placeholder: 'Aplica retaceo'
        });    
        $("#fechaFactura").val('<?= $camposEncabezado["fechaDocumento"]; ?>');
        $("#numeroFactura").val(<?= $camposEncabezado["numFactura"]; ?>);

        $("#selectProveedor").val(<?= $camposEncabezado["proveedorId"]; ?>).trigger('change');    
        $("#tipoDocumento").val(<?= $camposEncabezado["tipoDTEId"]; ?>).trigger('change');    
        $("#selectPais").val(<?= $camposEncabezado["paisId"]; ?>).trigger('change');    
        $("#selectRetaceo").val('<?= $camposEncabezado["flgRetaceo"]; ?>').trigger('change');    
    })
</script>