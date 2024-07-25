<form id="frmContinuarDTE" method="post" action="<?php echo base_url(''); ?>">
    <h2>Continuar DTE</h2>
    <hr>
    <button type= "button" id="btnRegresarReserva" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a facturación
    </button>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="selectSucursalDTE" id="selectSucursalDTE" style="width: 100%;" required>
                        <option value=""></option>
                        <option value="1">Aldo Games Store (Principal)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="selectTipoDTE" id="selectTipoDTE" style="width: 100%;" required>
                        <option value=""></option>
                        <option value="1">Factura</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" id="fechaDTE" name="fechaDTE" class="form-control active" required>
                    <label class="form-label" for="fechaDTE">Fecha del DTE</label>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-md-6">
                <div class="form-select-control">
                    <select name="selectNombreClienteDTE" id="selectNombreClienteDTE" style="width: 100%;" required>
                        <option value=""></option>
                        <option value="1">Cliente Prueba</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-select-control">
                    <select name="selectNombreVendedorDTE" id="selectNombreVendedorDTE" style="width: 100%;" required>
                        <option value=""></option>
                        <option value="1">Empleado Prueba</option>
                    </select>
                </div>
            </div>         
        </div>
        <div class="text-right">
            <button type="submit" id="btnguardarReserva" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Actualizar DTE
            </button>
        </div>
</form>
<hr>
<form id="frmContinuarReserva" method="post" action="">
        
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="">
            <i class="fas fa-save"></i>
            Agregar producto
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tablaContinuarDTE" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Precio venta</th>
                    <th>Cantidad</th>
                    <th>IVA 13%</th>
                    <th>Precio total</th>
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
                    <td></td>
                </tr>
                <tr>
                    <td id="tdFooterTotales" colspan="6"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<script>
    function modalPagoDTE() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/pago/dte'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalPagoDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalComplementoDTE() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/complemento/dte'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalEmitirDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalErrorDTE() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/error/dte'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalErrorDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function CertificarDTE() {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: 'DTE anulado',
                text: "DTE anulado con éxito",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '',
                            type: 'POST',
                            data: {
                                
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'DTE certificado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
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
                }
            });
    }
    $(document).ready(function(){
        $("#selectSucursalDTE").select2({
            placeholder: 'Sucursal'
        });

        $("#selectTipoDTE").select2({
            placeholder: 'Tipo DTE'
        });
        
        $("#selectNombreClienteDTE").select2({
            placeholder: 'Cliente'
        });

        $("#selectNombreVendedorDTE").select2({
            placeholder: 'Empleado'
        });

        tituloVentana("Continuar DTE");
        $('#btnRegresarReserva').on('click', function() {
            cambiarInterfaz('ventas/admin-facturacion/index', {renderVista: 'No'});
        });



        $('#tablaContinuarDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/continuar/dt'); ?>',
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
                { "width": "9%", "targets": 5,  "className": "text-left" },
                { "width": "9%", "targets": 6,  "className": "text-left" }
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