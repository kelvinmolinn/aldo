<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-retaceo/operacion/guardar/retaceo'); ?>">
    <div id="modalNuevoRetaceo" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Retaceo</h5>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="numeroFactura" name="numeroFactura" class="form-control" required>
                                <label class="form-label" for="numeroFactura">Numero de documento</label>

                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="date" id="fechaRetaceo" name="fechaRetaceo" class="form-control" required>
                                <label class="form-label" for="fechaRetaceo">Fecha del retaceo</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="number" id="fleteRetaceo" name="fleteRetaceo" class="form-control" required>
                                    <label class="form-label" for="fleteRetaceo">Flete</label>

                                </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="number" id="GastosRetaceo" name="GastosRetaceo" class="form-control" required>
                                        <label class="form-label" for="GastosRetaceo">Gastos</label>
                                    </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <textarea name="observacionRetaceo" id="observacionRetaceo" class="form-control" style="width: 100%;" required></textarea>
                                <label class="form-label" for="observacionRetaceo">Observación</label>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarRetaceo" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Generar retaceo
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
                        $('#modalNuevoRetaceo').modal('hide');
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