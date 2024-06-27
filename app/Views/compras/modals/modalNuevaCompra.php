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
                                <input type="text" id="numeroFactura" name="numeroFactura" class="form-control" required>
                                <label class="form-label" for="numeroFactura">Numero de documento</label>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control" required>
                                <label class="form-label" for="fechaFactura">Fecha documento</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectTipoCompra" id="selectTipoCompra" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Local</option>
                                    <option value="Internacional">Internacional</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectSucursal" id="selectSucursal" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($selectSucursal as $selectSucursal){ ?>
                                        <option value="<?php echo $selectSucursal['sucursalId']; ?>"><?php echo $selectSucursal['sucursal']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div id="select" class="form-select-control">
                                <select name="selectVacio" id="selectVacio" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>

                            <div id="proveedorLocal" class="form-select-control">
                                <select name="selectProveedor" id="selectProveedor" style="width: 100%;">
                                    <option value=""></option>
                                    <?php foreach ($selectProveedor as $selectProveedor){ ?>
                                        <option value="<?php echo $selectProveedor['proveedorId']; ?>"><?php echo $selectProveedor['proveedor']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div id="proveedorInternacional" class="form-select-control">
                                <select name="selectProveedorInternacionales" id="selectProveedorInternacionales" style="width: 100%;">
                                    <option value=""></option>
                                    <?php foreach ($selectProveedorInternacionales as $selectProveedorInternacionales){ ?>
                                        <option value="<?php echo $selectProveedorInternacionales['proveedorId']; ?>"><?php echo $selectProveedorInternacionales['proveedor']; ?></option>
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
        
        $("#select").show();
        $("#proveedorLocal").hide();
        $("#proveedorInternacional").hide();
        $("#selectVacio").prop('required', true);

        $("#tipoDocumento").select2({
            placeholder: 'Tipo documento',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectProveedor").select2({
            placeholder: 'Proveedor',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectProveedorInternacionales").select2({
            placeholder: 'Proveedor',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectVacio").select2({
            placeholder: 'Proveedor',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectPais").select2({
            placeholder: 'Pais',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectRetaceo").select2({
            placeholder: 'Aplica retaceo',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectTipoCompra").select2({
            placeholder: 'Tipo de compra',
            dropdownParent: $('#modalNuevaCompra')
        });
        $("#selectSucursal").select2({
            placeholder: 'Sucursal',
            dropdownParent: $('#modalNuevaCompra')
        });


        $("#selectTipoCompra").on('change', function() {
            if ($(this).val() == 'Local') {
                $("#selectPais").val('61').trigger('change');
                $("#selectPais").prop('readonly', true);
                $("#proveedorLocal").show();
                $("#proveedorInternacional").hide();
                $("#select").hide();
                $("#selectVacio").prop('required', false);
                $("#selectProveedor").prop('required', true);
                $("#selectProveedorInternacionales").prop('required', false);
            } else {
                $("#selectPais").val('').trigger('change');
                $("#selectPais").prop('readonly', false);
                $("#proveedorInternacional").show();
                $("#proveedorLocal").hide();
                $("#select").hide();
                $("#selectVacio").prop('required', false);
                $("#selectProveedor").prop('required', false);
                $("#selectProveedorInternacionales").prop('required', true);
            }
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