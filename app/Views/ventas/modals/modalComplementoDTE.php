<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/complementoDTE'); ?>">
    <div id="modalComplementoDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complemento del DTE - Número de DTE:  <?php echo $facturaId; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="facturaId" name="facturaId" value="<?= $facturaId; ?>">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="tipoComplemento" id="tipoComplemento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Orden de compra">Orden de compra</option>
                                    <option value="Complemento">Complemento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="complementoFactura" name="complementoFactura" class="form-control active"  required>
                                <label class="form-label" for="complementoFactura">Descripción de complemento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <button type="submit"id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="" >
                                <i class="fas fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </div>      
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaComplementoDTE" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>                             
                </div>
                <div class="modal-footer">
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
    function eliminarDTEComplemento(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el complemento del dte?',
                text: "Se eiminará el complemento seleccionado.",
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
                            url: '<?php echo base_url('ventas/admin-facturacion/operacion/eliminar/complemento'); ?>',
                            type: 'POST',
                            data: {
                                facturaComplementoId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'complemento Eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tablaComplementoDTE").DataTable().ajax.reload(null, false);
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
    $(document).ready(function() {
        $("#tipoComplemento").select2({
            placeholder: "Tipo de complemento"
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Complemento agregado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaComplementoDTE").DataTable().ajax.reload(null, false);
                            $("#tablaContinuarDTE").DataTable().ajax.reload(null, false);
                        
                            // Limpiar el select y el input
                            $('#tipoComplemento').val(null).trigger('change');
                            $('#complementoFactura').val('');
                        });
                        console.log("Último ID insertado:", response.facturaComplementoId);
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

        $('#tablaComplementoDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/complemento/dte'); ?>',
                "data": {
                     facturaId:<?php echo $facturaId;?>
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "70%", "targets": 1 }, 
                { "width": "20%", "targets": 2 }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });

    });
</script>
