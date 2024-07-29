<form id="frmContinuarDTE" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/actualizar/dte'); ?>">
    <input type="hidden" id="facturaId" name="facturaId" value="<?= $facturaId; ?>">
    <h2>Continuar DTE</h2>
    <hr>
    <button type="button" id="btnRegresarReserva" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i> Volver a facturación
    </button>
    <div class="row mb-2">
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
                <select name="tipoDTEId" id="tipoDTEId" class="form-control" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($tipoDTE as $tipoDTE) : ?>
                        <option value="<?php echo $tipoDTE['tipoDTEId']; ?>"><?php echo $tipoDTE['tipoDocumentoDTE']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="date" id="fechaEmision" name="fechaEmision" class="form-control active" value="<?= $campos['fechaEmision']; ?>" readonly>
                <label class="form-label" for="fechaEmision">Fecha de emisión</label>
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <div class="col-md-6">
            <div class="form-select-control">
                <select name="clienteId" id="clienteId" class="form-control" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($clientes as $cliente) : ?>
                        <option value="<?php echo $cliente['clienteId']; ?>"><?php echo $cliente['cliente']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-select-control">
                <select name="empleadoId" id="empleadoId" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>         
    </div>
    <div class="text-right">
        <button type="submit" id="btnguardarReserva" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Actualizar DTE
        </button>
    </div>
</form>
<hr>
<div class="text-right mb-4">
    <button type="button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="modalProductoDTE(0, 'insertar')">
        <i class="fas fa-save"></i> Agregar producto
    </button>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaContinuarDTE" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Precio venta</th>
                <th>Cantidad</th>
                <th>IVA 13%</th>
                <th>Precio total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9"></td>
            </tr>
            <tr>
                <td id="tdFooterTotales" colspan="8"></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>

    function eliminarDTE(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el producto?',
                text: "Se eiminara el producto del DTE.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '<?php echo base_url('ventas/admin-facturacion/operacion/eliminar/dte'); ?>',
                            type: 'POST',
                            data: {
                                facturaDetalleId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Producto eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tablaContinuarDTE").DataTable().ajax.reload(null, false);
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

        function modalPagoDTE(facturaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/pago/dte'); ?>',
                type: 'POST',
                data: {facturaId : facturaId}, // Pasar el ID del módulo como parámetro
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

            function modalConceptoDTE(facturaDetalleId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/concepto/dte'); ?>',
                type: 'POST',
                data: {facturaDetalleId : facturaDetalleId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalConceptoDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

                function modalComplementoDTE(facturaId, facturaComplementoId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/complemento/dte'); ?>',
                type: 'POST',
                data: {facturaComplementoId : facturaComplementoId, facturaId : facturaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalComplementoDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }



    function modalProductoDTE(facturaDetalleId, operacion) {
        $.ajax({
            url: '<?php echo base_url('ventas/admin-facturacion/modal/nuevo/dte'); ?>',
            type: 'POST',
            data: { facturaDetalleId: facturaDetalleId, operacion: operacion, facturaId: <?= $facturaId; ?>},
            success: function(response) {
                $('#divModalContent').html(response);
                $('#modalProductosDTE').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function(){
        tituloVentana("Continuar DTE");
        $('#btnRegresarReserva').on('click', function() {
            cambiarInterfaz('ventas/admin-facturacion/index', {renderVista: 'No'});
        });

        $("#sucursalId").select2({
            placeholder: 'Sucursal'
        });

        $("#tipoDTEId").select2({
            placeholder: 'Tipo DTE'
        });
        
        $("#clienteId").select2({
            placeholder: 'Cliente'
        });

        $("#empleadoId").select2({
            placeholder: 'Empleado'
        });

        $("#frmContinuarDTE").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'DTE actualizado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaDTE").DataTable().ajax.reload(null, false);
                            $("#tablaContinuarDTE").DataTable().ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#tablaContinuarDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/continuar/dte'); ?>',
                "data": {
                    facturaId: '<?= $facturaId; ?>'
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
                { "width": "9%", "targets": 6,  "className": "text-left" },
                { "width": "9%", "targets": 7,  "className": "text-left" },
                { "width": "9%", "targets": 8,  "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
        }); 

        $("#sucursalId").val(<?= $campos["sucursalId"]; ?>).trigger('change');       
        $("#clienteId").val(<?= $campos["clienteId"]; ?>).trigger('change'); 
        $("#tipoDTEId").val(<?= $campos["tipoDTEId"]; ?>).trigger('change');  
        $("#empleadoId").val(<?= $campos["empleadoId"]; ?>).trigger('change');   
    })
</script>
