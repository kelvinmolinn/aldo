<form id="frmModal">
    <div id="modalVerJSON" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
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
        function modalVerJSON(facturaId) {
            $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/tabla/ver/json'); ?>', // URL correcta
                type: 'POST',
                data: { facturaId: facturaId }, // Enviar el facturaId como parámetro
                success: function(response) {
                    // Formatear y mostrar el JSON en el modal
                    //$('#divModalContent').html(response);
                    $('#jsonContent').text(JSON.stringify(response, null, 4));
                    $('#modalVerJSON').modal('show');
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error('Error al cargar el JSON:', xhr.responseText);
                }
            });
        }

</script>
