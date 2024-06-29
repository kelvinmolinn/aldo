<form id="frmActualizarRetaceo" method="post" action="<?php echo base_url(''); ?>">
    <h2>Continuar reserva - Número de reserva: 1</h2>
    <hr>
    <button type= "button" id="btnRegresarReserva" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a reserva
    </button>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="text" id="sucursalReserva" name="sucursalReserva" class="form-control active" required>
                    <label class="form-label" for="sucursalReserva">Sucursal</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="text" id="clienteReserva" name="clienteReserva" class="form-control active" required>
                    <label class="form-label" for="clienteReserva">Cliente</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" id="fechaReserva" name="fechaReserva" class="form-control active" required>
                    <label class="form-label" for="fechaReserva">Fecha de la reserva</label>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" id="btnguardarReserva" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Actualizar reserva
            </button>
        </div>
</form>
<hr>
<form id="frmContinuarRetaceo" method="post" action="">
        
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="">
            <i class="fas fa-save"></i>
            Agregar reserva
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tablaContinuarRetaceo" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Precio venta</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
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
                </tr>
                <tr>
                    <td id="tdFooterTotales" colspan="5"></td>
                    <td></td>
                </tr>
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
            "footerCallback": function(tfoot) {    
                var response = this.api().ajax.json();
                if(response && Object.keys(response.footer).length !== 0) {
                    var td = $(tfoot).find('td');
                    td.eq(1).html(response["footer"][0]);
                    td.eq(3).html(response["footer"][1]);
                    td.eq(4).html(response["footer"][2]);
                    $("#tdFooterTotales").html(response["footerTotales"]);
                } else {
                    var td = $(tfoot).find('td');
                    td.eq(1).html('<b>Sumas</b>');
                    td.eq(2).html('<div class="text-right"><b>35</b></div>');
                    td.eq(3).html('<div class="text-right"><b>$ 50.50</b></div>');
                    td.eq(5).html('<div class="text-right"><b>$ 9.54</b></div>');
                    td.eq(6).html('<div class="text-right"><b>$ 7.95</b></div>');
                    td.eq(9).html('<div class="text-right"><b>$ 918.99</b></div>');
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