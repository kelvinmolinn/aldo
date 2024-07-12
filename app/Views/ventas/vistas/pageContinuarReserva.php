<form id="frmContinuarReserva" method="post" action="<?php echo base_url('ventas/admin-reservas/operacion/actualizar/reserva'); ?>">
    <input type="hidden" id="reservaId" name="reservaId" value="<?= $reservaId; ?>">
    <h2>Continuar reserva - Número de reserva: <?php echo $reservaId;?> </h2>
    <hr>
    <button type= "button" id="btnRegresarReserva" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a reserva
    </button>
        <div class="row ">
            <div class="col-md-4">
            <div class="form-select-control">
                <select name="sucursalId" id="sucursalId" class="form-control" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($sucursales as $sucursal) : ?>
                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            </div>
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="clienteId" id="clienteId" class="form-control" style="width: 100%;" required>
                        <option></option>
                        <?php foreach ($clientes as $cliente) : ?>
                            <option value="<?php echo $cliente['clienteId']; ?>"><?php echo $cliente['cliente']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" id="fechaReserva" name="fechaReserva" class="form-control active" value="<?= $campos['fechaReserva']; ?>" required>
                    <label class="form-label" for="fechaReserva">Fecha de la reserva</label>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 md-4">
                <div class="form-outline">
                    <input type="text" id="comentarioReserva" name="comentarioReserva" class="form-control active" value="<?= $campos['comentarioReserva']; ?>" required>
                    <label class="form-label" for="comentarioReserva">Comentarios</label>
                </div>
            </div>
        </div>
        <br>
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
        $("#sucursalId").select2({
            placeholder: 'Sucursal'
        });

        $("#clienteId").select2({
            placeholder: 'Cliente'
        });

        tituloVentana("Continuar reserva");

        $('#btnRegresarReserva').on('click', function() {
            cambiarInterfaz('ventas/admin-reservas/index', {renderVista: 'No'});
        });


        $("#frmContinuarReserva").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reserva actualizada con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaReserva").DataTable().ajax.reload(null, false);
                            $("#tablaContinuarReserva ").DataTable().ajax.reload(null, false);
                            // Actualizar tabla de contactos
                            // Limpiar inputs con .val(null) o .val('')
                            
                        });
                    } else {
                        // Insert fallido, mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
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
        
        $("#sucursalId").val(<?= $campos["sucursalId"]; ?>).trigger('change');       
        $("#clienteId").val(<?= $campos["clienteId"]; ?>).trigger('change');
    })
</script>