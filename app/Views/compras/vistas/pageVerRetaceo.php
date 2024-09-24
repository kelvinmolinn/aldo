<form id="frmActualizarRetaceo" method="post" action="">
    <h2>Ver retaceo - Número de retaceo: <?= $camposEncabezado["numRetaceo"]; ?></h2>
    <hr>
    <button type= "button" id="btnRegresarRetaceo" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a retaceo
    </button>
    <input type="hidden" id="retaceoId" name="retaceoId" value="<?= $retaceoId; ?>">

         <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="numeroRetaceo" name="numeroRetaceo" class="form-control active" required disabled>
                    <label class="form-label" for="numeroRetaceo">Numero de retaceo</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="date" id="fechaRetaceo" name="fechaRetaceo" class="form-control active" required disabled>
                    <label class="form-label" for="fechaRetaceo">Fecha de retaceo</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="number" id="fleteContinuarRetaceo" name="fleteContinuarRetaceo" class="form-control active" min = "0.00" required disabled>
                    <label class="form-label" for="fleteContinuarRetaceo">Flete</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="number" id="GastosContinuarRetaceo" name="GastosContinuarRetaceo" class="form-control active" min = "0.00" required disabled>
                    <label class="form-label" for="GastosContinuarRetaceo">Gastos</label>
                </div>
            </div>
        </div>
</form>

<form id="frmFinalizarRetaceo" method="post" action="">

    <input type="hidden" id="retaceoId" name="retaceoId" value="<?= $retaceoId; ?>">

    <div class="table-responsive">
        <table class="table table-hover" id="tablaVerRetaceo" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio FOB unitario</th>
                    <th>Importe</th>
                    <th>Flete</th>
                    <th>Gastos</th>
                    <th>DAI</th>
                    <th>Costo unitario</th>
                    <th>Costo total</th>
                    <th>Precio de venta actual</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td id="tdFooterTotales" colspan="10"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<script>    
    $(document).ready(function(){
        
        tituloVentana("Ver retaceo");

        $('#btnRegresarRetaceo').on('click', function() {
            cambiarInterfaz('compras/admin-retaceo/index', {renderVista: 'No'});
        });


        $('#tablaVerRetaceo').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-retaceo/tabla/ver/retaceo'); ?>',
                "data": {
                        retaceoId:'<?= $retaceoId; ?>'
                }
            },
            "footerCallback": function(tfoot) {    
                var response = this.api().ajax.json();
                if(response && Object.keys(response.footer).length !== 0) {
                    var td = $(tfoot).find('td');
                    td.eq(1).html(response["footer"][0]);
                    td.eq(2).html(response["footer"][1]);
                    td.eq(3).html(response["footer"][2]);
                    td.eq(5).html(response["footer"][3]);
                    td.eq(6).html(response["footer"][4]);
                    td.eq(9).html(response["footer"][5]);
                    $("#tdFooterTotales").html(response["footerTotales"]);
                } else {
                    var td = $(tfoot).find('td');
                    td.eq(1).html('<b>Sumas</b>');
                    td.eq(2).html('');
                    td.eq(3).html('');
                    td.eq(5).html('');
                    td.eq(6).html('');
                    td.eq(9).html('');
                    $("#tdFooterTotales").html(``);
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0,  "className": "text-left" }, 
                { "width": "9%", "targets": 1,  "className": "text-left" }, 
                { "width": "9%", "targets": 2,  "className": "text-left" }, 
                { "width": "9%", "targets": 3,  "className": "text-left" },
                { "width": "9%", "targets": 4,  "className": "text-left" },
                { "width": "9%", "targets": 5,  "className": "text-left" },
                { "width": "9%", "targets": 6,  "className": "text-left" }, 
                { "width": "9%", "targets": 7,  "className": "text-left" }, 
                { "width": "9%", "targets": 8,  "className": "text-left" }, 
                { "width": "9%", "targets": 9,  "className": "text-left" },
                { "width": "5%", "targets": 10, "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });   
        $("#fechaRetaceo").val('<?= $camposEncabezado["fechaRetaceo"]; ?>');
        $("#numeroRetaceo").val(<?= $camposEncabezado["numRetaceo"]; ?>).trigger('change');      
        $("#fleteContinuarRetaceo").val(<?= number_format($camposEncabezado["totalFlete"], 2, ".", ","); ?>).trigger('change');    
        $("#GastosContinuarRetaceo").val('<?= number_format($camposEncabezado["totalGastos"], 2, ".", ","); ?>').trigger('change'); 
    })
</script>