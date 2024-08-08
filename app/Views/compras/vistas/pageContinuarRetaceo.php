<form id="frmActualizarRetaceo" method="post" action="<?php echo base_url('compras/admin-retaceo/operacion/actualizar/retaceo'); ?>">
    <h2>Continuar retaceo - Número de retaceo: <?= $camposEncabezado["numRetaceo"]; ?></h2>
    <hr>
    <button type= "button" id="btnRegresarRetaceo" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a retaceo
    </button>
    <input type="hidden" id="retaceoId" name="retaceoId" value="<?= $retaceoId; ?>">

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
                    <input type="number" id="fleteContinuarRetaceo" name="fleteContinuarRetaceo" class="form-control active" min = "0.00" required>
                    <label class="form-label" for="fleteContinuarRetaceo">Flete</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="number" id="GastosContinuarRetaceo" name="GastosContinuarRetaceo" class="form-control active" min = "0.00" required>
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
<form id="frmContinuarRetaceo" method="post" action="<?php echo base_url('compras/admin-retaceo/operacion/finalizar/retaceo'); ?>">
        
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="modalAgregarCompraRetaceo();">
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
                    <th>Acciones</th>
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
                    <td></td>
                </tr>
                <tr>
                    <td id="tdFooterTotales" colspan="11"></td>
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
    function modalAgregarCompraRetaceo(){
        $.ajax({
            url: '<?php echo base_url('compras/admin-retaceo/form/nueva/compra/retaceo'); ?>',
            type: 'POST',
            data: {retaceoId : <?= $retaceoId; ?>}, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalNuevoRetaceoCompra').modal('show');

                
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalAgregarDAI(jsonDAI){
        $.ajax({
            url: '<?php echo base_url('compras/admin-retaceo/form/agregar/dai'); ?>',
            type: 'POST',
            data: {
                numDocumento :      '<?= $camposEncabezado["numRetaceo"]; ?>', 
                codigoProducto:     jsonDAI.codigoProducto, 
                producto:           jsonDAI.producto, 
                retaceoDetalleId:   jsonDAI.retaceoDetalleId
            }, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalAgregarDAI').modal('show');

                
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    
    function calcularRetaceo() {
        // Ajax hacia un controller que aplique el calculo o recalculo de retaceo
        $.ajax({
            url: '<?php echo base_url('compras/admin-retaceo/calcular/retaceo'); ?>',
            type: 'POST',
            data: {
                retaceoId: '<?= $retaceoId; ?>'
            }, // Pasar el ID del módulo como parámetro
            success: function(response) {
                $("#tablaContinuarRetaceo ").DataTable().ajax.reload(null, false);
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function(){
        
        tituloVentana("Continuar retaceo");

        $('#btnRegresarRetaceo').on('click', function() {
            cambiarInterfaz('compras/admin-retaceo/index', {renderVista: 'No'});
        });

        $("#fechaRetaceo").val('<?= $camposEncabezado["fechaRetaceo"]; ?>');

        $("#frmActualizarRetaceo").submit(function(event) {
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
                            title: 'Retaceo actualizado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            calcularRetaceo();
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

        $('#tablaContinuarRetaceo').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-retaceo/tabla/continuar/retaceo'); ?>',
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
                { "width": "5%", "targets": 10, "className": "text-left" },
                { "width": "9%", "targets": 11, "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });   

        $("#numeroRetaceo").val(<?= $camposEncabezado["numRetaceo"]; ?>).trigger('change');      
        $("#fleteContinuarRetaceo").val(<?= number_format($camposEncabezado["totalFlete"], 2, ".", ","); ?>).trigger('change');    
        $("#GastosContinuarRetaceo").val('<?= number_format($camposEncabezado["totalGastos"], 2, ".", ","); ?>').trigger('change'); 
    })
</script>