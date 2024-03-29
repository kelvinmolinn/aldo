<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    //$param1 = $request->getGet('modulo');
?>
<h2>Asignación de menús a módulo: <?php echo  $modulo;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarModulo" class="btn btn-secondary estilo-btn">
            <i class="fas fa-angle-double-left"> </i>
            Volver a Módulo
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary estilo-btn" onclick="modalMenu(0, 'insertar');">
            <i class="fas fa-save"></i>
            Nuevo menú
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tblMenus" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Menú</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $n = 0;
               
                foreach($menus as $menus){ 
                    $n++;
                    $menuId = $menus['menuId'];
                    $menu = $menus['menu'];
                    $iconoMenu = $menus['iconoMenu'];
                    $urlMenu = $menus['urlMenu'];
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><b>Módulo: </b><?php echo $menu; ?><br>
                    </td>
                    <td>
                        <b>Url: </b><?php echo $menus['urlMenu']; ?>  <br>
                    </td>
                    <td>

                    <button class="btn btn-primary mb-1" onclick="modalMenu('<?= $menuId; ?>', 'editar');" data-toggle="tooltip" data-placement="top" title="Editar menú">
                        <span></span>
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                        <a href="<?= site_url('conf-general/page-permisos-menus/' . $menuId . '/' . $menu); ?>" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="0 permisos">
                            <i class="fas fa-bars nav-icon"></i>
                        </a>

                        <button class="btn btn-danger mb-1" onclick="eliminarMenu(`<?= $menus['menuId']; ?>`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>

function eliminarMenu(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el menú?',
                text: "Se eiminara el menú seleccionado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '<?php echo base_url('administracion-modulos/eliminar-menu'); ?>',
                            type: 'POST',
                            data: {
                                menuId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Menú eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        // Recargar la DataTable después del insert
                                        window.location.href = "<?= site_url('conf-general/administracion-menus'); ?>";
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
        /*
            data: {
                sucursalUsuarioId: id
            }
        */
    }
    function modalMenu(menuId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
            url: '<?php echo base_url('conf-general/administracion-menus/menu'); ?>',
            type: 'POST',
            data: { menuId: menuId, operacion: operacion}, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalMenus').modal('show');
            },
        error: function(xhr, status, error) {
            // Manejar errores si los hay
            console.error(xhr.responseText);
        }
    });
    }
     $(document).ready(function() {
        $('#miTabla').DataTable({
        "language": {
            "url": "../../assets/plugins/datatables/js/spanish.json"
        },
        "columnDefs": [
            { "width": "10%", "targets": 0 }, 
            { "width": "40%", "targets": 1 }, 
            { "width": "35%", "targets": 2 }, 
            { "width": "15%", "targets": 3 }  
        ]
    });
    });
</script>
<?= $this->endSection(); ?>