<input type="hidden" id="trasladosId" name="trasladosId" value="<?= $trasladosId; ?>">
<h2>Continuar traslado N°: <?php echo $trasladosId;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarDescargo" class="btn btn-secondary estilo-btn">
        <i class="fas fa-backspace "></i>
            Volver 
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalAdministracionNuevoTraslado(0, 'insertar');">
        <i class="fas fa-save nav-icon "></i>
            Solicitar producto
        </button>
    </div>
</div>
<div class= "table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table id="tblContinuarTraslados" name = "tblContinuarTraslados" class="table table-hover" style="width: 100%;">
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
<hr>
</div>
    <div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "submit" id="btnFinalizar" class="btn btn-primary" onclick="finalizarDescargo();">
        <i class="fas fa-save nav-icon "></i>
            Enviar Solicitud
        </button>
    </div>
</div>

<script>

            //pendiente
            function modalAdministracionNuevoTraslado(descargoDetalleId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-traslados/form2/Nuevasalida'); ?>',
                type: 'POST',
                data: { descargoDetalleId: descargoDetalleId, operacion: operacion, descargosId: <?= $descargosId; ?>}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNuevaSalida').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Traslados");
        $('#btnRegresarDescargo').on('click', function() {
            cambiarInterfaz('inventario/admin-traslados/index', {renderVista: 'No'});
        });
        $('#tblContinuarSalida').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-salida/tabla/ContinuarSalida'); ?>',
                    "data": {
                        trasladosId: <?php echo $trasladosId ;?>
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