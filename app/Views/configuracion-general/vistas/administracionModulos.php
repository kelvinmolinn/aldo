<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de Módulos</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary ttip">
            <i class="fas fa-user-plus"></i>
            Nuevo módulo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="miTabla" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Módulo</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $n = 0;
               
                foreach($modulos as $modulos){ 
                    $n++;
                    $moduloId = $modulos['moduloId'];
                    $modulo = $modulos['modulo'];
                    $iconoModulo = $modulos['iconoModulo'];
                    $urlModulo = $modulos['urlModulo'];
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><b>Módulo: </b><?php echo $modulo; ?><br>
                    </td>
                    <td>
                        <b>Url: </b><?php echo $modulos['urlModulo']; ?>  <br>
                    </td>
                    <td>

                    <button class="btn btn-primary mb-1" onclick="modalEditarModulo('<?= $moduloId; ?>', '<?= $modulo; ?>', '<?= $iconoModulo; ?>', '<?= $urlModulo; ?>');" data-toggle="tooltip" data-placement="top" title="Editar modulo">
                        <span></span>
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                        <a href="<?= site_url('conf-general/page-menus-modulos/' . $moduloId . '/' . $modulo); ?>" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="0 Menús">
                            <i class="fas fa-bars nav-icon"></i>
                        </a>

                        <button class="btn btn-danger mb-1" onclick="eliminarModulo(`<?= $modulos['moduloId']; ?>`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
        function eliminarModulo(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el módulo?',
                text: "Se eiminara el módulo seleccionado.",
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
                            url: '<?php echo base_url('administracion-modulos/eliminar-modulo'); ?>',
                            type: 'POST',
                            data: {
                                moduloId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Módulo eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        // Recargar la DataTable después del insert
                                        window.location.href = "<?= site_url('conf-general/administracion-modulos'); ?>";
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

    function modalEditarModulo(moduloId, modulo, iconoModulo, urlModulo) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
            url: '<?php echo base_url('conf-general/editar-modulo'); ?>',
            type: 'POST',
            data: { moduloId: moduloId, 
                    modulo: modulo, 
                    iconoModulo: iconoModulo,
                    urlModulo: urlModulo }, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalModulos').modal('show');
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
        $('#btnAbrirModal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('administracion-modulos/nuevo-modulo'); ?>',
                type: 'GET',
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                   
                    // Mostrar la modal sin permitir cierre al hacer clic fuera o al presionar "Esc"
                    $('#modalModulos').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },

                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });


    });
</script>
<?= $this->endSection(); ?>