<form id="frmModal">
    <div id="modalNuevoPermisos" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo permiso del rol: <?= $rol;?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="rol" id="rol" value="<?= $rol;?>">
                    <input type="hidden" name="rolId" id="rolId" value="<?= $rolId;?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="menu" id="menu" style="width: 100%;">
                                        <option value=""></option>
                                    <?php foreach ($menu as $menu) { ?>
                                        <option value="<?= $menu['menuId'];?>"><?= $menu['menu'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="menuPermiso[]" id="menuPermiso" style="width: 100%;" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnguardar" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
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
        $("#menu").select2({
            placeholder: 'Menus'
        });
        $("#menuPermiso").select2({
            placeholder: 'Permisos'
        });

        $('#menu').on('change', function() {
            var menuId = $(this).val();
            if (menuId) {
                // Realizar una petición AJAX para obtener los permisos relacionados con el menú seleccionado
                $.ajax({
                    url: '<?php echo base_url('conf-general/admin-permisos/obtener/permisos/select'); ?>',
                    type: "POST",
                    dataType: "json",
                    data: {menuId: menuId}
                }).done(function(data){
                    $(`#menuPermiso`).empty();
                    $(`#menuPermiso`).append("<option></option>");
                    for (let i = 0; i < data.length; i++){
                        $(`#menuPermiso`).append($('<option>', {
                            value: data[i]['valor'],
                            text: data[i]['texto']
                        }));
                    }
                });
            } else {
                // Limpiar el select de permisos si no se ha seleccionado ningún menú
                $('#menuPermiso').html('<option value=""></option>');
            }
        });

        $('#btnguardar').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-permisos/operacion/insert/permisos/menus'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                        $('#modalNuevoPermisos').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Agregado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaPermisos").DataTable().ajax.reload(null, false);
                        });
                    } else {
                        // Insert fallido, mostrar mensaje de error con Sweet Alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje
                        });
                    }
                },

                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
