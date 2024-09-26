
<form id="frmModal" method="post" action="">
    <div id="modalConsolidadoCompras" class="modal fade modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalLabel">Consolidado de  compras</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
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
    function ajustarAlturaModal() {
        var alturaModal = $('#modalConsolidadoCompras .modal-dialog').height(); // Obtener la altura del modal
        var alturaModalHeader = $('#modalConsolidadoCompras .modal-header').outerHeight(); // Obtener la altura del header del modal
        var alturaModalFooter = $('#modalConsolidadoCompras .modal-footer').outerHeight(); // Obtener la altura del footer del modal

        // Calcular la altura disponible para el iframe dentro del modal
        var alturaDisponible = alturaModal - alturaModalHeader - alturaModalFooter - 30; // Ajuste de margen
        $('#pdfFrame').height(alturaDisponible);
    }

    $(document).ready(function() {
        // Ajustar altura al mostrar el modal
        $('#modalConsolidadoCompras').on('shown.bs.modal', function () {
            ajustarAlturaModal();
        });

        // Ajustar altura si el tamaño de la ventana cambia mientras el modal está abierto
        $(window).resize(function() {
            if ($('#modalConsolidadoCompras').is(':visible')) {
                ajustarAlturaModal();
            }
        });
    });
</script>




