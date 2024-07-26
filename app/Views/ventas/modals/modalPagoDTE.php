<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/pagoDTE'); ?>">
    <div id="modalPagoDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">pagos del DTE - Número de DTE:  <?php echo $facturaId; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="facturaId" name="facturaId" value="<?= $facturaId; ?>">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="formaPagoMHId" id="formaPagoMHId" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($formaPago as $pago) : ?>
                                        <option value="<?php echo $pago['formaPagoMHId']; ?>"><?php echo $pago['formaPago']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" id="totalPago" name="totalPago" class="form-control active number-input" min="0.01" step="0.01" required>
                                <label class="form-label" for="totalPago">Monto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="descripcionPago" name="descripcionPago" class="form-control active" required>
                                <label class="form-label" for="descripcionPago">Descripión</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit"id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="" >
                                <i class="fas fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </div>      
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaPagoDTE" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pago</th>
                                    <th>Monto</th>
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
    function eliminarDTEPago(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el pago del dte?',
                text: "Se eiminara el pago seleccionado.",
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
                            url: '<?php echo base_url('ventas/admin-facturacion/operacion/eliminar/pago'); ?>',
                            type: 'POST',
                            data: {
                                facturaPagoId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pago Eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tablaPagoDTE").DataTable().ajax.reload(null, false);
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
        $("#formaPagoMHId").select2({
            placeholder: "Forma pago"
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
                            title: 'Pago agregado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaPagoDTE").DataTable().ajax.reload(null, false);
                            $("#tablaContinuarDTE").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.facturaPagoId);
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

        $('#tablaPagoDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/pago/dte'); ?>',
                "data": {
                     facturaId:<?php echo $facturaId;?>
                }
            },
            "columnDefs": [
                { "width": "20%", "targets": 0 }, 
                { "width": "36%", "targets": 1 }, 
                { "width": "19%", "targets": 2 },
                { "width": "10%", "targets": 3 }
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
