<form id="frmModal">
    <div id="modalUsuariosPermisos" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios con el permiso</h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaUsuariosPermisos" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empleados</th>
                                    <th>Roles</th>
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
        $('#tablaUsuariosPermisos').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-permisos/tabla/modulos/permiso/usuarios'); ?>',
                "data": {
                    menuPermisoId : '<?= $menuPermisoId; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 } 
            ],
            "language": {
                "url": "../../../../../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
