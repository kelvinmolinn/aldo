<form id="frmModal">
    <div id="modalPermisosRolMenu" class="modal" tabindex="-1">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Permiso del rol: en el menu: <?php echo $menuId;?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-select-control">
                                <select name="selectPermisoMenu[]" id="selectPermisoMenu" style = "width: 100%;" multiple required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btnguardar" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Guardar
                            </button>
                        </div>
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
    function cargarPermisosMenu() {
        // Realizar una petición AJAX para obtener los permisos relacionados con el menú seleccionado
        $.ajax({
            url: '<?php echo base_url('conf-general/admin-roles/obtener/permisos/select'); ?>',
            type: "POST",
            dataType: "json",
            data: {menuId: '<?= $menuId; ?>', existePermisos: 'Si'}
        }).done(function(data){
            $(`#selectPermisoMenu`).empty();
            $(`#selectPermisoMenu`).append("<option></option>");
            for (let i = 0; i < data.length; i++){
                $(`#selectPermisoMenu`).append($('<option>', {
                    value: data[i]['valor'],
                    text: data[i]['texto']
                }));
            }
        });
    }
    $(document).ready(function() {
        $("#selectPermisoMenu").select2({
            placeholder: 'Permisos'
        });
        cargarPermisosMenu();
    });
</script>
