<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-compras/operacion/guardar/compra'); ?>">
    <div id="modalNuevaCompra" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva compra</h5>
                </div>
                <div class="modal-body">
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
                                <input type="number" id="numeroFactura" name="numeroFactura" class="form-control" required>
                                <label class="form-label" for="numeroFactura">Numero de factura</label>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control" required>
                                <label class="form-label" for="fechaFactura">Fecha Factura</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectProveedor" id="selectProveedor" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($selectProveedor as $selectProveedor){ ?>
                                        <option value="<?php echo $selectProveedor['proveedorId']; ?>"><?php echo $selectProveedor['proveedor']; ?></option>
                                    <?php } ?>
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
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Generar compra
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i>
                        Cerrar
                    </button>
                </div>
          </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){

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


        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalNuevaCompra').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Compra realizada con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaCompras").DataTable().ajax.reload(null, false);
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
    })
</script>