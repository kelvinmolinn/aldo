<h2>Reportes</h2>
<hr>
<div class="row">
    <div class="col-md-2">
        <div class="form-outline">
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control">
            <label class="form-label" for="fechaInicio">Fecha inicio</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-outline">
            <input type="date" id="fechaFin" name="fechaFin" class="form-control">
            <label class="form-label" for="fechaFin">Fecha fin</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-select-control">
            <select name="reporte" id="reporte" style="width: 100%;">
                <option value=""></option>
                <option value="consolidadoVentas">Consolidado de ventas</option>
                <option value="detalleVentas">Detalle de ventas</option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-3">
        <button type="button" id="btnReporte" class="btn btn-primary">Generar reporte</button>
    </div>
</div>
<script>
    function reporteConsolidadoVentas() {
            var fechaInicio = $("#fechaInicio").val();
            var fechaFin = $("#fechaFin").val();
        $.ajax({
            url: '<?php echo base_url('ventas/admin-reportes/modal/consolidado/ventas'); ?>',
            type: 'POST',
            data: {}, // Pasar el ID de la factura como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);

                // Asumimos que el modal ya tiene un iframe con el ID `pdfFrame`
                var pdfUrl = '<?php echo base_url("ventas/admin-reportes/reporte/pdf/consolidado/ventas"); ?>' + '?fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin;
                $('#pdfFrame').attr('src', pdfUrl);

                // Mostrar la modal
                $('#modalConsolidadoVentas').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function reporteDetalleVentas(){
            var fechaInicio = $("#fechaInicio").val();
            var fechaFin = $("#fechaFin").val();
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reportes/reporte/detalle/ventas'); ?>',
                type: 'POST',
                data: { fechaInicio: fechaInicio, fechaFin: fechaFin }, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalDetalleVentas').modal('show');
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

        $("#btnReporte").click(function(e) {
            var fechaInicio = $("#fechaInicio").val();
            var fechaFin = $("#fechaFin").val();
            var tipoReporte = $("#reporte").val();

            if (!fechaInicio) {
                alert("Seleccione la fecha de inicio");
                return;
            }
            
            if (!fechaFin) {
                alert("Seleccione la fecha de fin");
                return;
            }

            if (tipoReporte === "consolidadoVentas") {
                reporteConsolidadoVentas();
            } else if (tipoReporte === "detalleVentas") {
                reporteDetalleVentas();
            } else {
                alert("Reporte no definido");
            }
        });

    });
</script>