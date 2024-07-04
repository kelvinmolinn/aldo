
<form id="frmActualizarCompra" method="post" action="<?php echo base_url('compras/admin-compras/operacion/actualizar/compra'); ?>">
    <input type="hidden" id="compraId" name="compraId" value="<?= $compraId; ?>">
    <h2 id="tituloEncabezadoCompra">Continuar compra - Número de documento: <?php echo $camposEncabezado["numFactura"];?></h2>
    <hr>
    <button type= "button" id="btnRegresarCompra" class="btn btn-secondary estilo-btn mb-4">
        <i class="fas fa-backspace"></i>
            Volver a compra
    </button>
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
                    <input type="text" id="numeroFactura" name="numeroFactura" class="form-control active" required>
                    <label class="form-label" for="numeroFactura">Numero de documento</label>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" id="fechaFactura" name="fechaFactura" class="form-control active" required>
                    <label class="form-label" for="fechaFactura">Fecha documento</label>
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
        <div class="text-right">
            <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Actualizar compra
            </button>
        </div>
</form>
<hr>
<form id="frmContinuarCompra" method="post" action="<?php echo base_url('compras/admin-compras/finalizar/compra'); ?>">
    
    <input type="hidden" id="compraId" name="compraId" value="<?= $compraId; ?>">
    
    <div class="text-right mb-4">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="modalAgregarProducto(0,'insertar')">
            <i class="fas fa-save"></i>
            Agregar producto
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tablaContinuarCompra" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Costo unitario</th>
                    <th>Cantidad</th>
                    <th>Costo total</th>
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
    function eliminarProducto(id) {
        Swal.fire({
            title: '¿Estás seguro que desea eliminar el producto?',
            text: "Se eiminara el producto seleccionado.",
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
                        url: '<?php echo base_url('compras/admin-compras/eliminar/producto/compra'); ?>',
                        type: 'POST',
                        data: {
                            compraDetalleId: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Producto eliminado con Éxito!',
                                    text: response.mensaje
                                }).then((result) => {
                                    $("#tablaContinuarCompra").DataTable().ajax.reload(null, false);
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
    function modalAgregarProducto(compraDetalleId,operacion){
        $.ajax({
            url: '<?php echo base_url('compras/admin-compras/form/producto/compra'); ?>',
            type: 'POST',
            data: {compraId : <?php echo $compraId; ?>, operacion: operacion,  compraDetalleId: compraDetalleId }, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalAgregarProducto').modal('show');

                
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function(){
        
        tituloVentana("Compras");

        $('#btnRegresarCompra').on('click', function() {
            cambiarInterfaz('compras/admin-compras/index', {renderVista: 'No'});
        });

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
        $("#numeroFactura").val('<?= $camposEncabezado["numFactura"]; ?>');

        $("#frmActualizarCompra").submit(function(event) {
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
                            title: 'Compra actualizada con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tituloEncabezadoCompra").html("Continuar compra - Número de documento: " + $("#numeroFactura").val());
                            $("#tablaCompras").DataTable().ajax.reload(null, false);
                            $("#tablaContinuarCompra ").DataTable().ajax.reload(null, false);
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

        $("#frmContinuarCompra").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro que desea finalizar la compra?',
                text: "Se finalizara la compra seleccionada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'), 
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Compra finalizada con éxito',
                                    text: response.mensaje
                                }).then((result) => {

                                    cambiarInterfaz(`compras/admin-compras/index`);
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
                }
            });
        });

        $('#tablaContinuarCompra').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-compras/tabla/continuar/compra'); ?>',
                "data": {
                    compraId: '<?= $compraId; ?>',
                    tipoContribuyenteId: '<?= $tipoContribuyenteId;?>',
                    ivaPercibido: '<?= $ivaPercibido;?>' 

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
                    td.eq(3).html('<div class="text-right"><b>0.00</b></div>');
                    td.eq(4).html('<div class="text-right"><b>$ 0.00</b></div>');
                    $("#tdFooterTotales").html(``);
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0, "className": "text-left" }, 
                { "width": "25%", "targets": 1, "className": "text-left" }, 
                { "width": "20%", "targets": 2, "className": "text-left" }, 
                { "width": "15%", "targets": 3, "className": "text-left" },
                { "width": "20%", "targets": 4, "className": "text-left" },
                { "width": "10%", "targets": 5, "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
        $("#selectProveedor").val(<?= $camposEncabezado["proveedorId"]; ?>).trigger('change');    
        $("#tipoDocumento").val(<?= $camposEncabezado["tipoDTEId"]; ?>).trigger('change');    
        $("#selectPais").val(<?= $camposEncabezado["paisId"]; ?>).trigger('change');    
        $("#selectRetaceo").val('<?= $camposEncabezado["flgRetaceo"]; ?>').trigger('change');    
    })
</script>