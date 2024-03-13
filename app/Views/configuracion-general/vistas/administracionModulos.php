<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de Módulos</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary ttip">
            <i class="fas fa-save"></i>
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
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><b>Módulo: </b><?php echo $modulos['modulo']; ?><br>
                    </td>
                    <td>
                        <b>Url: </b><?php echo $modulos['urlModulo']; ?>  <br>
                    </td>
                    <td>

                    <button class="btn btn-primary mb-1" onclick="modalEditarModulo(`<?= $moduloId; ?>`,`<?= $modulo; ?>`);" data-toggle="tooltip" data-placement="top" title="Editar modulo">
                            <span></span>
                            <i class="fas fa-pencil-alt"></i>
                </button>

                        <a href="<?= site_url('conf-general/page-menus-modulos?modulo=' .$modulos['modulo']); ?>" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="0 Menús">
                            <i class="fas fa-bars nav-icon"></i>
                        </a>

                        <button class="btn btn-danger mb-1" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i> 
                        </button>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function modalEditarModulo(moduloId, modulo) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
            url: '<?php echo base_url('conf-general/editar-modulo'); ?>',
            type: 'POST',
            data: { moduloId: moduloId, modulo: modulo }, // Pasar el ID del módulo como parámetro
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