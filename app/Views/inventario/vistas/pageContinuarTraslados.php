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
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalAdministracionNuevaSalida(0, 'insertar');">
        <i class="fas fa-save nav-icon "></i>
            Actualizar
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
</div>
    <br>
    <div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "submit" id="btnFinalizar" class="btn btn-primary" onclick="finalizarDescargo();">
        <i class="fas fa-save nav-icon "></i>
            Enviar Solicitud
        </button>
    </div>
</div>

<script>
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