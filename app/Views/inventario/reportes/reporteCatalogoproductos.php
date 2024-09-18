<form id="frmModal" method="post" action="">
    <div id="modalReporteCatalogoProducto" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalLabel">Cátalogo de productos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <button id="btnReporteExcel" type="button" class="btn btn-success btn-sm" onclick="">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>                            
                        </div>
                        <div class="col-10">
                            <div class= "table-responsive">
                                <table id="tblReporteCatalogoProductos" name = "tblReporteCatalogoProductos" class="table table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Descripción</th>
                                            <th>Plataforma</th>
                                            <th>Categoría</th>
                                            <th>Unidad de medida</th>
                                            <th>Producto para venta</th>
                                            <th>Existencia mínima</th>
                                            <th>Sucursal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo
                                            $n = 0; 
                                            foreach ($datos AS $productos) {
                                                $n++;

                                                if($productos['flgProductoVenta'] == 1){
                                                    $productoVenta = "Si";
                                                }else{
                                                    $productoVenta = "No";
                                                }
                                                
                                                echo '
                                                    <tr>
                                                        <td>'.$n.'</td>                        
                                                        <td>"'.$productos['codigoProducto'].'"</td>
                                                        <td>'.$productos['producto'].'</td>
                                                        <td>'.$productos['descripcionProducto'].'</td>
                                                        <td>'.$productos['productoPlataforma'].'</td>
                                                        <td>'.$productos['productoTipo'].'</td>
                                                        <td>'.$productos['unidadMedida'].'</td>
                                                        <td>'.$productoVenta.'</td>
                                                        <td>'.$productos['existenciaMinima'].'</td>
                                                        <td>'.$productos['codigoProducto'].'</td>
                                                    </tr>
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
        var alturaModal = $('#modalReporteCatalogoProducto .modal-dialog').height(); // Obtener la altura del modal
        var alturaModalHeader = $('#modalReporteCatalogoProducto .modal-header').outerHeight(); // Obtener la altura del header del modal
        var alturaModalFooter = $('#modalReporteCatalogoProducto .modal-footer').outerHeight(); // Obtener la altura del footer del modal

        // Calcular la altura disponible para el iframe dentro del modal
        var alturaDisponible = alturaModal - alturaModalHeader - alturaModalFooter - 30; // Ajuste de margen
        $('#pdfFrame').height(alturaDisponible);
    }

    $(document).ready(function() {
        $("#btnReporteExcel").click(function(e) {
            $("#tblReporteCatalogoProductos").table2excel({
                name: `<?php echo 'Nombre'; ?>`,
                filename: `<?php echo 'Otro'; ?>`
            });
        });

        // Ajustar altura al mostrar el modal
        $('#modalReporteCatalogoProducto').on('shown.bs.modal', function () {
            ajustarAlturaModal();
        });

        // Ajustar altura si el tamaño de la ventana cambia mientras el modal está abierto
        $(window).resize(function() {
            if ($('#modalReporteCatalogoProducto').is(':visible')) {
                ajustarAlturaModal();
            }
        });
    });
</script>




