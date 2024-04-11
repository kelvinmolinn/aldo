<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>

<h2>Unidades de medida</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalUnidades({unidadMedidaId: '0', operacion: 'insertar'});">
            <i class="fas fa-save"></i>
            Nueva UDM
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblUnidades" name = "tblUnidades" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Unidad de medida</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>

function modalUnidades(campos) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-unidades/form/unidades'); ?>',
                type: 'POST',
                data: campos, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalUnidades').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
$(document).ready(function() {
    $('#tblUnidades').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-unidades/tabla/unidades'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "55%", "targets": 1 }, 
                { "width": "35%", "targets": 2 }
            ],
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
        });
    });
</script>

<?= $this->endSection(); ?>