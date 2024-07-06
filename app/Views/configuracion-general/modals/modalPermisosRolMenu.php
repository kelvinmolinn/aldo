<form id="frmModal">
    <div id="modalPermisosRolMenu" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Permiso del rol: <?= $rol; ?> en el menu: <?php echo $menu; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="menu" name="menu" value="<?= $menuId; ?>">
                    <input type="hidden" name="rol" id="rol" value="<?= $rol;?>">
                    <input type="hidden" name="rolId" id="rolId" value="<?= $rolId;?>">
                    <div class="row mb-4">
                        <div class="col-md-9">
                            <div class="form-select-control">
                                <select name="menuPermiso[]" id="menuPermiso" style = "width: 100%;" multiple required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btnguardar" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Agregar permisos
                            </button>
                        </div>
                    </div>
                    <table id="tblPermisosRolMenu" class="table table-hover mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Permiso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
            data: {menuId: '<?= $menuId; ?>', rolId: '<?= $rolId; ?>'}
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
    }

    function eliminarPermisoRolMenu(id) {
        Swal.fire({
            title: '¿Estás seguro que desea eliminar el permiso del rol?',
            text: "Se eliminará el permiso y los usuarios con el rol ya no podrán realizar esa acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('conf-general/admin-roles/operacion/eliminar/permisos-rol-menu'); ?>',
                    type: 'POST',
                    data: {
                        rolMenuId: id
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Operación realizada:',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tblPermisosMenus").DataTable().ajax.reload(null, false);
                                $("#tblPermisosRolMenu").DataTable().ajax.reload(null, false);
                                cargarPermisosMenu();
                            });
                        } else {
                            // Insert fallido, mostrar mensaje de error
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
            }
        });
    }

    $(document).ready(function() {
        $("#menuPermiso").select2({
            placeholder: 'Permisos del menú'
        });

        cargarPermisosMenu();

        $('#btnguardar').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-roles/operacion/insert/permisos/menus'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                        $('#modalNuevoPermisos').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Permisos asignados al rol con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblPermisosMenus").DataTable().ajax.reload(null, false);
                            $("#tblPermisosRolMenu").DataTable().ajax.reload(null, false);
                            $("#menuPermiso").val([]).trigger("change");
                            cargarPermisosMenu();
                        });
                    } else {
                        // Insert fallido, mostrar mensaje de error con Sweet Alert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Aviso:',
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

        $('#tblPermisosRolMenu').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-roles/tabla/permisos-rol-menu'); ?>',
                "data": {
                    menuId: '<?= $menuId; ?>',
                    rolId: '<?= $rolId; ?>'
                }
            },
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "70%"}, 
                { "width": "20%"}
            ]
        });
    });
</script>
