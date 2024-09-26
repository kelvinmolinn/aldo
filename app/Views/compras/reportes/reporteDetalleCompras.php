<style>
    /* Definir una altura máxima para el modal */
    .modal-fullscreen .modal-body {
        max-height: calc(100vh - 200px); /* Ajusta este valor según lo que necesites */
        overflow-y: auto; /* Permite el desplazamiento vertical */
    }
</style>
<form id="frmModal" method="post" action="">
    <div id="modalDetalleCompras" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalLabel">Consolidado de  compras</h5>
                    <style>
                        table {
                            table-layout: fixed; /* Fija el ancho de las columnas */
                            word-wrap: break-word; /* Permite romper las palabras si es necesario */
                        }
                    </style>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <button id="btnDetalleCompras" type="button" class="btn btn-success" onclick="">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>  
                        </div>
                        <div class="col-10">
                            <div class= "table-responsive">
                                <table id="tblDetalleCompras" name = "tblDetalleCompras" class="table table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo de documento</th>
                                            <th>Número de documento</th>
                                            <th>Fecha de documento</th>
                                            <th>Proveedor</th>
                                            <th>Código del producto</th>
                                            <th>Nombre del producto</th>
                                            <th>Precio unitario</th>
                                            <th>Precio con IVA</th>
                                            <th>Cantidad</th>
                                            <th>IVA 13%</th>
                                            <th>Total</th>
                                            <th>Total con IVA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $n = 0;

                                            foreach ($detalles AS $detalleCompra) {
                                                $n++;
                                                echo '
                                                    <tr>
                                                        <td>'.$n.'</td>                       
                                                        <td>'.$detalleCompra['tipoDocumentoDTE'].'</td>
                                                        <td>'.$detalleCompra['numFactura'].'</td>
                                                        <td>'.$detalleCompra['fechaDocumento'].'</td>
                                                        <td>'.$detalleCompra['proveedor'].'</td>
                                                        <td>'.$detalleCompra['codigoProducto'].'</td>
                                                        <td>'.$detalleCompra['producto'].'</td>
                                                        <td>$ '.number_format($detalleCompra['precioUnitario'], 2, '.', ',').'</td>
                                                        <td>$ '.number_format($detalleCompra['precioUnitarioIVA'], 2, '.', ',').'</td>
                                                        <td>'.$detalleCompra['cantidadProducto'].'</td>
                                                        <td>$ '.number_format($detalleCompra['ivaUnitario'], 2, '.', ',').'</td>
                                                        <td>$ '.number_format($detalleCompra['totalCompraDetalle'], 2, '.', ',').'</td>
                                                        <td>$ '.number_format($detalleCompra['totalCompraDetalleIVA'], 2, '.', ',').'</td>
                                                ';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
        var alturaModal = $('#modalDetalleCompras .modal-dialog').height(); // Obtener la altura del modal
        var alturaModalHeader = $('#modalDetalleCompras .modal-header').outerHeight(); // Obtener la altura del header del modal
        var alturaModalFooter = $('#modalDetalleCompras .modal-footer').outerHeight(); // Obtener la altura del footer del modal

        // Calcular la altura disponible para el iframe dentro del modal
        var alturaDisponible = alturaModal - alturaModalHeader - alturaModalFooter - 30; // Ajuste de margen
        $('#pdfFrame').height(alturaDisponible);
    }

    $(document).ready(function() {
        $("#btnDetalleCompras").click(function(e) {
            $("#tblDetalleCompras").table2excel({
                name: `<?php echo 'Detalle de compras'; ?>`,
                filename: `<?php echo 'Detalle de compras'; ?>`
            });
        });

        // Ajustar altura al mostrar el modal
        $('#modalDetalleCompras').on('shown.bs.modal', function () {
            ajustarAlturaModal();
        });

        // Ajustar altura si el tamaño de la ventana cambia mientras el modal está abierto
        $(window).resize(function() {
            if ($('#modalDetalleCompras').is(':visible')) {
                ajustarAlturaModal();
            }
        });
    });
</script>




