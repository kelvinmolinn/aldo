<h2>Reportes</h2>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="form-select-control mb-4">
            <select name="reporte" id="reporte" style="width: 100%;">
                <option value=""></option>
                <option value="consolidadoCompras">Consolidado de compras</option>
                <option value="detalleCompras">Detalle de compras</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div id="divTipoCompra" class="form-select-control">
            <select name="tipoCompra" id="tipoCompra" style="width: 100%;">
                <option value=""></option>
                <option value="Local">Compra local</option>
                <option value="Internacional">Compra Internacional</option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-3">
        <button type="button" id="btnReporte" class="btn btn-primary">Generar reporte</button>
    </div>
</div>
<script>
    function reporteConsolidadoCompras() {
        var tipoCompra = $("#tipoCompra").val();         
        $.ajax({
            url: '<?php echo base_url('compras/admin-reportes/modal/consolidado/compras'); ?>',
            type: 'POST',
            data: {
                tipoCompra: tipoCompra
            }, // Pasar el ID de la factura como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);

                // Asumimos que el modal ya tiene un iframe con el ID `pdfFrame`
                var pdfUrl = '<?php echo base_url("compras/admin-reportes/reporte/pdf/consolidado/compras"); ?>' + '?tipoCompra=' + tipoCompra;
                $('#pdfFrame').attr('src', pdfUrl);

                // Mostrar la modal
                $('#modalConsolidadoCompras').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function reporteDetalleCompras(){
        var tipoCompra = $("#tipoCompra").val(); 
        $.ajax({
                url: '<?php echo base_url('compras/admin-reportes/reporte/detalle/compras'); ?>',
                type: 'POST',
                data: { tipoCompra: tipoCompra }, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalDetalleCompras').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function() {
        tituloVentana("Reportes");
        $("#reporte").select2({
            placeholder: 'Tipo reporte'
        });

        $("#tipoCompra").select2({
            placeholder: 'Tipo compra'
        });

        $("#divTipoCompra").hide();

        $("#reporte").change(function(e) {
            if($("#reporte").val() == "consolidadoCompras") {
                $("#divTipoCompra").show();

            } else if($("#reporte").val() == "detalleCompras") {
                $("#divTipoCompra").show();

            } else {

                $("#divTipoCompra").hide();
            }
        });

        $("#btnReporte").click(function(e) {
            if($("#reporte").val() == "consolidadoCompras") {
                if($("#tipoCompra").val() != "") {
                    reporteConsolidadoCompras();
                } else {
                    alert("seleccione el tipo de compra");
                }
            } else if($("#reporte").val() == "detalleCompras") {
                if($("#tipoCompra").val() != "") {
                    reporteDetalleCompras();
                } else {
                    alert("seleccione el tipo de compra");
                }
            } else {
                alert("reporte no definido");
            }
        });
    });
</script>