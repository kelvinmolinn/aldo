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
                            <button id="btnEnviarCorreo" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-envelope-open-text"></i> Enviar correo
                            </button>                            
                        </div>
                        <div class="col-10">
                            <div id="divModalContent">
                                <iframe id="pdfFrame" src="" width="100%" height="500px"></iframe>
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
    $(document).ready(function() {

    });
</script>



