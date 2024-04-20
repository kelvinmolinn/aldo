<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Usuarios con el permiso: </h2>
<hr>
<form id="frmModal">
    <div id="modalUsuariosPermisos" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios con el permiso</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="menuPermisoId" name="menuPermisoId" value="<?= $campos['menuPermisoId']; ?>">
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
                                <?php 
                                    for ($i=0; $i < count($data); $i++) { 
                                        echo "
                                            <tr>
                                                <td>".$data[$i][0]."</td>
                                                <td>".$data[$i][1]."</td>
                                                <td>".$data[$i][2]."</td>
                                            </tr>
                                        ";
                                    } 
                                ?>
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
            /*
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-permisos/tabla/usuarios/permiso'); ?>',
                "data": {
                    x : 0
                }
            },
            */
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
<?= $this->endSection(); ?>