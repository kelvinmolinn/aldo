<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalAgregarDAI" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DAI</h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <b><span>Número de documento: </span></b><?php echo $numDocumento?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                        <b><span>Producto: </span></b>(<?php echo $codigoProducto?>) <?php echo $producto?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" id="DAI" name="DAI" class="form-control active" required>
                                <label class="form-label" for="DAI">DAI</label>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
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
        $("#selectCompraRetaceo").select2({
            placeholder: 'Compras',
            dropdownParent: $('#modalNuevoRetaceo')
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
                        $('#modalAgregarDAI').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'DAI agregado al retaceo con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaContinuarRetaceo").DataTable().ajax.reload(null, false);
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
    })
</script>