
<form id="frmModal">
    <div id="modalPrecios" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('Actualizar precio de venta'); ?></h5>
                </div>
                <div class="modal-body">

                <div class="container modal-body">
                    <div class="form-outline position-relative">
                        <div class="col-md-4">
                            <input type="number" id="precioVentaNuevo" inputmode="numeric"  class="form-control" name="precioVentaNuevo" placeholder="Nuevo precio de venta" readonly required>
                        </div>
                        <div class="col-md-4 mt-2">
                            <button type="button" class="btn btn-sm btn-primary edit-button" onclick="enableEdit()">Editar</button>
                        </div>
                        <label class="trailing"></label>
                    </div>
                </div>


                <hr>
                <div class= "table-responsive">
                    <table id="tblPrecio" name = "tblPrecio" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Costos anteriores</th>
                                <th>Precios anteriores</th>
                                <th>Fecha y hora del cambio</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
</form>
<script>
    $(document).ready(function() {

            // Función para habilitar la edición del campo precioVentaNuevo
            window.enableEdit = function() {
                var precioVentaNuevo = document.getElementById('precioVentaNuevo');
                precioVentaNuevo.readOnly = false; // Habilita la edición del campo
                precioVentaNuevo.focus(); // Opcional: pone el foco en el campo para que el usuario pueda editar inmediatamente
            };

            const input = document.getElementById('precioVentaNuevo');

                input.addEventListener('keydown', function(event) {
                    if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                        event.preventDefault();
                    }
                });

        $('#tblPrecio').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/precio'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "30%"}, 
                { "width": "30%"},
                { "width": "30%"} 
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