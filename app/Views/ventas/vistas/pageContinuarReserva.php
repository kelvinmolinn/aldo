<form id="frmActualizarRetaceo" method="post" action="<?php echo base_url(''); ?>">
    <h2>Continuar reserva - Número de reserva: 1</h2>
    <hr>
    <button type= "button" id="btnRegresarReserva" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a reserva
    </button>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="selectSucursalReserva" id="selectSucursalReserva" style="width: 100%;" required>
                        <option value=""></option>
                        <option value="1">Aldo Games Store (Principal)</option>
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
<form id="frmContinuarReserva" method="post" action="">
        
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="modalProductoReserva()">
            <i class="fas fa-save"></i>
            Agregar producto
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tablaContinuarReserva" style="width: 100%;">
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
                <textarea name="observacionFinalizarReserva" id="observacionFinalizarReserva" class="form-control" style="width: 100%;" required></textarea>
                <label class="form-label" for="">Observación de la reserva</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-right">
                <button type="submit" id="btnFinalizarReserva" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Finalizar reserva
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    
    function modalPagoReserva() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/pago/reserva'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalPagoReservas').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalProductoReserva() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/modal/nuevo/reserva'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalProductosReserva').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function(){
        $("#selectSucursalReserva").select2({
            placeholder: 'Sucursal'
        });

        $("#selectNombreClienteReserva").select2({
            placeholder: 'Cliente'
        });

        tituloVentana("Continuar reserva");

        $('#btnRegresarReserva').on('click', function() {
            cambiarInterfaz('ventas/admin-reserva/index', {renderVista: 'No'});
        });

        $('#tablaContinuarReserva').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-reservas/tabla/continuar/reserva'); ?>',
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
                    td.eq(2).html('<div class="text-right"><b></b></div>');
                    td.eq(3).html('<div class="text-right"><b></b></div>');
                    $("#tdFooterTotales").html(``);
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0,  "className": "text-left" }, 
                { "width": "9%", "targets": 1,  "className": "text-left" }, 
                { "width": "9%", "targets": 2,  "className": "text-left" }, 
                { "width": "9%", "targets": 3,  "className": "text-left" },
                { "width": "9%", "targets": 4,  "className": "text-left" },
                { "width": "9%", "targets": 5,  "className": "text-left" }
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