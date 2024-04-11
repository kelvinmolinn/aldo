<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>

<h2>Unidades de medida</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalUnidad({usuarioId: '0',empleadoId: '0', operacion: 'insertar'});">
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
    </table>
</div>
<script>
$(document).ready(function() {
        $('#tblUnidades').DataTable({
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "75%"}, 
                { "width": "15%"}  
            ]
        });
    });
</script>

<?= $this->endSection(); ?>