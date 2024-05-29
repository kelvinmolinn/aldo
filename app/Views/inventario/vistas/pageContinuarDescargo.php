
<input type="hidden" id="descargosId" name="descargosId" value="<?= $descargosId; ?>">

<h2>Continuar descargo N°: <?php echo $descargosId;?> - <?php echo $dataSucursal['sucursal'];?></h2>
<hr>
<div class="row mb-4">
<div class="col-md-6">
        <button type= "button" id="btnRegresarDescargo" class="btn btn-secondary estilo-btn">
        <i class="fas fa-backspace "></i>
            Volver 
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalContinuarSalida(0, 'insertar');">
        Agregar producto <i class="fas fa-plus nav-icon"></i>
            
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblContinuarSalida" name = "tblContinuarSalida" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Motivo/Justificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
        //pendiente
        function modalContinuarSalida(productoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-salida/form2/Nuevasalida'); ?>',
                type: 'POST',
                data: { productoId: productoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalContinuarSalida').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Descargos/Continuar salida");
        $('#btnRegresarDescargo').on('click', function() {
            cambiarInterfaz('inventario/admin-salida/index', {renderVista: 'No'});
        });
        $('#tblContinuarSalida').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-salida/tabla/ContinuarSalida'); ?>',
                    "data": {
                        descargosId: <?php echo $descargosId ;?>
                    }
                },
                "columnDefs": [
                    { "width": "10%"}, 
                    { "width": "25%"}, 
                    { "width": "25%"},
                    { "width": "25%"},  
                    { "width": "15%"}  
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