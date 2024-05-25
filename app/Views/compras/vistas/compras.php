<h2>Compras</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="">
            <i class="fas fa-save"></i>
            Nueva compra
        </button>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroNumFactura" name="filtroNumFactura" class="form-control ">
            <label class="form-label" for="filtroNumFactura">Numero de factura</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="date" id="filtroFechaDocumento" name="filtroFechaDocumento" class="form-control ">
            <label class="form-label" for="filtroFechaDocumento">Fecha del documento</label>

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroProveedor" name="filtroProveedor" class="form-control ">
            <label class="form-label" for="Proveedor">Proveedor</label>
        </div>
    </div>
</div>
<div class="text-right mb-4">
    <button type= "button" id="btnBuscarCompra" class="btn btn-primary estilo-btn" onclick="$('#tablaProveedores').DataTable().ajax.reload(null, false);">
        <i class="fas fa-search"></i>
        Buscar
    </button>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaProveedores" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Documentos</th>
                <th>Proveedores</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
    $('input, textarea').on('focus', function() {
        $(this).addClass('active');
    });

    // Remover clase 'active' si el input está vacío al perder el foco
    $('input, textarea').on('blur', function() {
        if ($(this).val().trim() === '') {
            $(this).removeClass('active');
        }
    });
        tituloVentana("Proveedores");
        $('#tablaProveedores').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-compras/tabla/compras'); ?>',
                "data": function() { 
                    return {
                        "numFactura": $("#filtroNumFactura").val(),
                        "fechaFactura": $("#filtroFechaDocumento").val(),
                        "nombreProveedor": $("#filtroProveedor").val()
                    }
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "30%", "targets": 2 }, 
                { "width": "20%", "targets": 3 }, 
                { "width": "20%", "targets": 4 } 
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
