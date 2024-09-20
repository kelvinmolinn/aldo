<h2>Reportes</h2>
<hr>
<div class="row">
    <div class="col-md-2">
        <button type="button" id="" class="btn btn-primary" onclick="catalogoProductos();">
            Cátalogo de productos
        </button>
    </div>
    <div class="col-md-2">
        <button type="button" id="" class="btn btn-primary" onclick="reportePreciosProducto();">
            Precio de productos
        </button>
    </div>
</div>
<script>
    function reportePreciosProducto() {
        $.ajax({
            url: '<?php echo base_url('inventario/admin-reportes/modal/precio/productos'); ?>',
            type: 'POST',
            data: {}, // Pasar el ID de la factura como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);

                // Asumimos que el modal ya tiene un iframe con el ID `pdfFrame`
                var pdfUrl = '<?php echo base_url("inventario/admin-reportes/reporte/pdf/precios/productos"); ?>';
                $('#pdfFrame').attr('src', pdfUrl);

                // Mostrar la modal
                $('#modalPreciosProducto').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function catalogoProductos(){
        $.ajax({
                url: '<?php echo base_url('inventario/admin-reportes/reporte/catalogoProductos'); ?>',
                type: 'POST',
                data: { }, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalReporteCatalogoProducto').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function() {

        tituloVentana("Reportes");

    });
</script>