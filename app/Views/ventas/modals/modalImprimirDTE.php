<form id="frmModal" method="post" action="">
    <div id="modalImprimirDTE" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalLabel">DTE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <button id="btnEnviarCorreo" type="button" class="btn btn-primary btn-sm" onclick="enviarDTE(<?= $facturaId; ?>);">
                                <i class="fas fa-envelope-open-text"></i> Enviar correo
                            </button>                            
                        </div>
                        <div class="col-10">
                            <div id="divModalContent">
                                <iframe id="pdfFrame" src="" width="100%"></iframe>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function enviarDTE(id) {
        $.ajax({
            url: '<?php echo base_url('correos/envio/dte'); ?>', // URL correcta
            type: 'POST',
            data: { facturaId: id }, // Enviar el facturaId como parámetro
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Correo enviado con éxito',
                    text: 'El DTE se envió con éxito'
                });
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error('Error al cargar el JSON:', xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        function ajustarAlturaModal() {
            var alturaModal = $('#modalImprimirDTE .modal-dialog').height(); // Obtener la altura del modal
            var alturaModalHeader = $('#modalImprimirDTE .modal-header').outerHeight(); // Obtener la altura del header del modal
            var alturaModalFooter = $('#modalImprimirDTE .modal-footer').outerHeight(); // Obtener la altura del footer del modal

            // Calcular la altura disponible para el iframe dentro del modal
            var alturaDisponible = alturaModal - alturaModalHeader - alturaModalFooter - 30; // Ajuste de margen
            $('#pdfFrame').height(alturaDisponible);
        }

        // Ajustar altura al mostrar el modal
        $('#modalImprimirDTE').on('shown.bs.modal', function () {
            ajustarAlturaModal();
        });

        // Ajustar altura si el tamaño de la ventana cambia mientras el modal está abierto
        $(window).resize(function() {
            if ($('#modalImprimirDTE').is(':visible')) {
                ajustarAlturaModal();
            }
        });
    });
</script>




