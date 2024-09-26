<style>
    /* Definir una altura máxima para el modal */
    .modal-fullscreen .modal-body {
        max-height: calc(100vh - 200px); /* Ajusta este valor según lo que necesites */
        overflow-y: auto; /* Permite el desplazamiento vertical */
    }
</style>
<form id="frmModal" method="post" action="">
    <div id="modalDetalleVentas" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalLabel">Consolidado de  ventas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <button id="btnDetalleVentas" type="button" class="btn btn-success" onclick="">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>  

                    <div class="table-responsive">
                        <table id="tblDetalleVentas" name = "tblDetalleVentas" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo de documento</th>
                                    <th>Número de documento</th>
                                    <th>Fecha de documento</th>
                                    <th>Cliente</th>
                                    <th>Código del producto</th>
                                    <th>Nombre del producto</th>
                                    <th>Precio unitario</th>
                                    <th>Precio unitario con IVA</th>
                                    <th>Precio de venta unitario</th>
                                    <th>Precio de venta unitario con IVA</th>
                                    <th>Cantidad</th>
                                    <th>Descuento total</th>
                                    <th width="5%">IVA 13%</th>
                                    <th width="5%">Total</th>
                                    <th>Total con IVA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;

                                    foreach ($detalles AS $detalleVentas) {
                                        $n++;
                                        echo '
                                            <tr>
                                                <td>'.$n.'</td>                       
                                                <td>'.$detalleVentas['tipoDocumentoDTE'].'</td>
                                                <td>'.$detalleVentas['facturaId'].'</td>
                                                <td>'.$detalleVentas['fechaEmision'].'</td>
                                                <td>'.$detalleVentas['cliente'].'</td>
                                                <td>'.$detalleVentas['codigoProducto'].'</td>
                                                <td>'.$detalleVentas['producto'].'</td>
                                                <td>$ '.number_format($detalleVentas['precioUnitario'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['precioUnitarioIVA'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['precioUnitarioVenta'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['precioUnitarioVentaIVA'], 2, '.', ',').'</td>
                                                <td>'.$detalleVentas['cantidadProducto'].'</td>
                                                <td>$ '.number_format($detalleVentas['descuentoTotal'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['ivaTotal'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['totalDetalle'], 2, '.', ',').'</td>
                                                <td>$ '.number_format($detalleVentas['totalDetalleIVA'], 2, '.', ',').'</td>
                                            </tr>
                                        ';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function ajustarAlturaModal() {
        var alturaModal = $('#modalDetalleVentas .modal-dialog').height(); // Obtener la altura del modal
        var alturaModalHeader = $('#modalDetalleVentas .modal-header').outerHeight(); // Obtener la altura del header del modal
        var alturaModalFooter = $('#modalDetalleVentas .modal-footer').outerHeight(); // Obtener la altura del footer del modal

        // Calcular la altura disponible para el iframe dentro del modal
        var alturaDisponible = alturaModal - alturaModalHeader - alturaModalFooter - 30; // Ajuste de margen
        $('#pdfFrame').height(alturaDisponible);
    }

    $(document).ready(function() {
        $("#btnDetalleVentas").click(function(e) {
            $("#tblDetalleVentas").table2excel({
                name: `<?php echo 'Detalle de compras'; ?>`,
                filename: `<?php echo 'Detalle de compras'; ?>`
            });
        });

        // Ajustar altura al mostrar el modal
        $('#modalDetalleVentas').on('shown.bs.modal', function () {
            ajustarAlturaModal();
        });

        // Ajustar altura si el tamaño de la ventana cambia mientras el modal está abierto
        $(window).resize(function() {
            if ($('#modalDetalleVentas').is(':visible')) {
                ajustarAlturaModal();
            }
        });
    });
</script>




