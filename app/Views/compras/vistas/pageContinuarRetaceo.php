<form id="frmActualizarRetaceo" method="post" action="<?php echo base_url(''); ?>">
    <h2>Continuar retaceo - Número de retaceo: 1</h2>
    <hr>
    <button type= "button" id="btnRegresarRetaceo" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a retaceo
    </button>
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="numeroRetaceo" name="numeroRetaceo" class="form-control active" required>
                    <label class="form-label" for="numeroRetaceo">Numero de retaceo</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="date" id="fechaRetaceo" name="fechaRetaceo" class="form-control active" required>
                    <label class="form-label" for="fechaRetaceo">Fecha de retaceo</label>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="number" id="fleteContinuarRetaceo" name="fleteContinuarRetaceo" class="form-control active" required>
                    <label class="form-label" for="fleteContinuarRetaceo">Flete</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="number" id="GastosContinuarRetaceo" name="GastosContinuarRetaceo" class="form-control active" required>
                    <label class="form-label" for="GastosContinuarRetaceo">Gastos</label>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" id="btnguardarRetaceo" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Actualizar compra
            </button>
        </div>
</form>
<hr>
<form id="frmContinuarRetaceo" method="post" action="">
        
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="">
            <i class="fas fa-save"></i>
            Agregar compra
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tablaContinuarRetaceo" style="width: 100%;">
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
            </tfoot>
        </table>
    </div>

    <div class="row mb-4 mt-4">
        <div class="col-md-6">
            <div class="form-outline">
                <textarea name="observacionFinalizarCompra" id="observacionFinalizarCompra" class="form-control" style="width: 100%;" required></textarea>
                <label class="form-label" for="">Observación de la compra</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-right">
                <button type="submit" id="btnFinalizarCompra" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Finalizar compra
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        
        tituloVentana("Continuar retaceo");

        $('#btnRegresarRetaceo').on('click', function() {
            cambiarInterfaz('compras/admin-retaceo/index', {renderVista: 'No'});
        });

        $('#tablaContinuarRetaceo').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-retaceo/tabla/continuar/retaceo'); ?>',
                "data": {
                        x:''

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
                { "width": "14%", "targets": 10, "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });   
    })
</script>