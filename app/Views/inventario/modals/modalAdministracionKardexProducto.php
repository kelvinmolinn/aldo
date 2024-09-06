<form id="frmModal">
    <div id="modalKardexProducto" class="modal " tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('Kardex de productos'); ?></h5>
                </div>
                <div class="modal-body">
                    <!-- Select de Sucursal -->
                    <div class="form-group">
                        <label for="sucursalSelect">Seleccionar Sucursal:</label>
                            <select id="sucursalSelect" class="form-control">
                                <option value="">Todas las Sucursales</option>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?= $sucursal['sucursalId']; ?>"><?= $sucursal['sucursal']; ?></option>
                                <?php endforeach; ?>
                            </select>

                    </div>
                    <div class= "table-responsive">
                        <table id="tblExistenciaProducto" name="tblExistenciaProducto" class="table table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sucursal</th>
                                    <th>Descripción y tipo de movimiento</th>
                                    <th>Cantidad Antes</th>
                                    <th>Cantidad movimiento</th>
                                    <th>Cantidad despues</th>
                                    <th>Fecha del movimiento</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Función para cargar el contenido de la tabla según la sucursal seleccionada
        function cargarTablaKardex(productoId, sucursalId = '') {
            $('#tblExistenciaProducto').DataTable({
                "destroy": true, // Permitir reconstruir la tabla con nuevos datos
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-producto/tabla/kardexProducto'); ?>',
                    "data": {
                        productoId: productoId,
                        sucursalId: sucursalId // Enviar sucursalId al servidor
                    }
                },
                "columnDefs": [
                    { "width": "10%" }, 
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "10%" }, 
                    { "width": "10%" }, 
                    { "width": "10%" }, 
                    { "width": "10%" }
                ],
                "language": {
                    "url": "../assets/plugins/datatables/js/spanish.json"
                },
                "drawCallback": function(settings) {
                    // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                    $('[data-toggle="tooltip"]').tooltip();
                },
            });
        }

        // Cargar la tabla por defecto sin filtro de sucursal
        cargarTablaKardex('<?= $productoId; ?>');

        // Evento para cambiar la sucursal y recargar la tabla
        $('#sucursalSelect').on('change', function() {
            var sucursalId = $(this).val(); // Obtener el valor del select
            cargarTablaKardex('<?= $productoId; ?>', sucursalId);
        });
    });
</script>
