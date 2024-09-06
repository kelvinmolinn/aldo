<form id="frmModal">
    <div id="modalVerJSONContingencia" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Visualización de JSON </h2>
                </div>
                <div class="modal-body">
                    <pre id="jsonContent">Cargando...</pre> <!-- Mostrar el JSON aquí -->
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
        cargarJSONContingencia('<?= $facturaContingenciaId; ?>');

    });
</script>
