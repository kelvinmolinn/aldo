<form id="frmModal">
    <div id="modalVerDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="facturaId" name="facturaId" value="<?= $facturaId; ?>">
                      <h2>Visualización del DTE N°: <?php echo $facturaId;?> </h2>
                      </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaVerDTE" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Descuento</th>
                                    <th>Precio venta</th>
                                    <th>Cantidad</th>
                                    <th>IVA 13%</th>
                                    <th>Precio total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="9"></td>
                                </tr>
                                <tr>
                                    <td id="tdFooterTotales" colspan="8"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
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
    </div>
</form>
<script>
    $(document).ready(function() {
                $('#tablaVerDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/ver/dte'); ?>',
                "data": {
                    facturaId: '<?= $facturaId; ?>'
                }
            },
            "footerCallback": function(tfoot) {    
                var response = this.api().ajax.json();
                if(response && Object.keys(response.footer).length !== 0) {
                    var td = $(tfoot).find('td');
                    td.eq(1).html(response["footer"][0]);
                    td.eq(3).html(response["footer"][1]);
                    td.eq(4).html(response["footer"][2]);
                    $("#tdFooterTotales").html(response["footerTotales"]);
                } else {
                    var td = $(tfoot).find('td');
                    td.eq(1).html('<b>Sumas</b>');
                    td.eq(2).html('<div class="text-right"><b></b></div>');
                    td.eq(3).html('<div class="text-right"><b></b></div>');
                    $("#tdFooterTotales").html(``);
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0,  "className": "text-left" }, 
                { "width": "9%", "targets": 1,  "className": "text-left" }, 
                { "width": "9%", "targets": 2,  "className": "text-left" }, 
                { "width": "9%", "targets": 3,  "className": "text-left" },
                { "width": "9%", "targets": 4,  "className": "text-left" },
                { "width": "9%", "targets": 5,  "className": "text-left" },
                { "width": "9%", "targets": 6,  "className": "text-left" },
                { "width": "9%", "targets": 7,  "className": "text-left" },
                { "width": "9%", "targets": 8,  "className": "text-left" }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
        }); 
    });
</script>
