<h2>Nueva compra</h2>
<hr>
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-select-control">
            <select name="tipoDocumento" id="tipoDocumento" style="width: 100%;" required>
                <option value=""></option>
                <option value="factura">Factura</option>
                <option value="otro">otro</option>
            </select>
            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="number" id="numeroFactura" name="numeroFactura" class="form-control">
            <label class="form-label" for="numeroFactura">Numero de factura</label>

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="date" id="fechaFactura" name="fechaFactura" class="form-control ">
            <label class="form-label" for="fechaFactura">Fecha Factura</label>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

    	$("#tipoDocumento").select2({
            placeholder: 'Tipo documento'
        });

    })
</script>