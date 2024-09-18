<h2>Reportes</h2>
<hr>
<div class="row">
    <div class="col-md-2">
        <button type="button" id="" class="btn btn-primary" onclick="catalogoProductos();">
            Cátalogo de productos
        </button>
    </div>
    <div class="col-md-2">
        <button type="button" id="" class="btn btn-primary" onclick="">
            Precio de productos
        </button>
    </div>
</div>
<script>

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