<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-reservas/operacion/finalizar/reserva-facturar'); ?>">
    <div id="modalAdministracionFacturarReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="reservaId" name="reservaId" value="<?= $reservaId; ?>">
                    <h2>Reserva a facturar N°: <?php echo $reservaId;?> </h2>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaFacturarReserva" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Precio total</th>
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
                    <hr>
                    <h4>Facturación de reserva</h4>
                    <div class="row mt-4">
                        <div class="col-md-4 mb-4">
                            <div class="form-outline">
                                <input type="date" id="fechaEmision" name="fechaEmision" class="form-control numero" value="<?php echo date('Y-m-d'); ?>" readonly required>
                                <label class="form-label" for="fechaEmision">Fecha emisión</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-select-control">
                                <select name="tipoDTEId" id="tipoDTEId" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($tipoDTE as $tipoDTE) : ?>
                                        <option value="<?php echo $tipoDTE['tipoDTEId']; ?>"><?php echo $tipoDTE['tipoDocumentoDTE']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-select-control">
                                <select name="empleadoIdVendedor" id="empleadoIdVendedor" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($empleados as $empleado) : ?>
                                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-money-check-alt"></i> Facturar
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
    $(document).ready(function() {
        $("#tipoDTEId").select2({
            placeholder: 'Tipo DTE',
            dropdownParent: $('#modalAdministracionFacturarReserva')
        });  

        $("#empleadoIdVendedor").select2({
            placeholder: 'Vendedor',
            dropdownParent: $('#modalAdministracionFacturarReserva')
        });

        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalAdministracionFacturarReserva').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Operación completada:',
                            text: response.mensaje
                        }).then((result) => {
                            cambiarInterfaz('ventas/admin-facturacion/vista/continuar/dte', {facturaId: response.facturaId, contigencia: response.contigencia});
                        });
                    } else {
                        // Insert fallido, mostrar mensaje de error con Sweet Alert
                        let errorMessage = '<ul>';
                        $.each(response.errors, function(key, value) {
                            errorMessage += '<li>' + value + '</li>';
                        });
                        errorMessage += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: 'Existen errores al intentar trasladara la reserva'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });

        $('#tablaFacturarReserva').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-reservas/tabla/verReserva'); ?>',
                "data": {
                    reservaId: '<?= $reservaId; ?>'
                }
            },
            "footerCallback": function(tfoot) {
                var response = this.api().ajax.json();
                if (response && Object.keys(response.footer).length !== 0) {
                    var td = $(tfoot).find('td');
                    td.eq(1).html(response["footer"][0]);
                    td.eq(3).html(response["footer"][1]);
                    td.eq(4).html(response["footer"][2]);
                    td.eq(5).html(response["footer"][3]);
                    $("#tdFooterTotales").html(response["footerTotales"]);
                } else {
                    var td = $(tfoot).find('td');
                    td.eq(1).html('<b>Sumas</b>');
                    td.eq(2).html('<div class="text-right"><b></b></div>');
                    td.eq(3).html('<div class="text-right"><b></b></div>');
                    td.eq(4).html('<div class="text-right"><b></b></div>');
                    $("#tdFooterTotales").html(``);
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": "text-left" },
                { "width": "9%", "targets": 1, "className": "text-left" },
                { "width": "9%", "targets": 2, "className": "text-left" },
                { "width": "9%", "targets": 3, "className": "text-left" },
                { "width": "9%", "targets": 4, "className": "text-left" },
                { "width": "9%", "targets": 5, "className": "text-left" }
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
