<?php 
    if($renderVista == "Sí") {
        $this->extend('Panel/plantilla');
        $this->section('contenido');
    }
?>
<form id="frmClave">
    <div class="card mt-4 border-top">
        <div class="card-body mt-2 border-bottom">
            Estimado <?= session('nombreUsuario'); ?>, por motivos de seguridad, solicitamos que cambie su contraseña por una distinta a la asignada por defecto. Mientras no realice dicha acción no podrá realizar ningún movimiento.

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="password" id="nuevaClave" name="nuevaClave" class="form-control " placeholder="Nueva contraseña" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="password" class="form-control " id="confirmarClave" name="confirmarClave" placeholder="Confirmar nueva contraseña" required>
                    </div>
                </div>
            </div>
            <div class="row mt-4" onclick="mostrarPassword();">
                <div class="col-4">
                    <div class="form-check">
                        <label for="mostrarClave" class="form-check-label" style="cursor: pointer;">
                            <input type="checkbox" class="form-check-input" id="mostrarClave" name="mostrarClave"> 
                            <div id="divMostrarClave">
                                <span class="fas fa-eye"></span> Mostrar contraseñas
                            </div>
                        </label>
                    </div>
                </div>
                <div id="divMensajeClave" class="col-5" style="color: red; font-weight: bold;"></div>
                <div class="col-3">
                    <button type="button" id="btnCambiarClave" class="btn btn-primary btn-block">
                        <i class="fas fa-retweet"></i>
                        Cambiar contraseña
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function mostrarPassword() {
        if($('#mostrarClave').is(':checked')) {
            $('#nuevaClave').prop('type','text');
            $('#confirmarClave').prop('type','text');
            $("#divMostrarClave").html(`<span class="fas fa-eye-slash"></span> Ocultar contraseñas`);
        } else {
            $('#nuevaClave').prop('type','password');
            $('#confirmarClave').prop('type','password');
            $("#divMostrarClave").html(`<span class="fas fa-eye"></span> Mostrar contraseñas`);
        }
    }

    $(document).ready(function() {
        $("#nuevaClave, #confirmarClave").keyup(function(e) {
            $("#divMensajeClave").html('');
        });

        $("#btnCambiarClave").click(function(e) {
            if($("#nuevaClave").val() == $("#confirmarClave").val()) {
                Swal.fire({
                    title: '¿Está seguro que desea cambiar la contraseña?',
                    text: 'Recuerde verificar y respaldar en un lugar seguro la contraseña ingresada',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar contraseña',
                    cancelButtonText: 'Cancelar'
                }).then((result) =>{
                    if(result.isConfirmed) {
                        $.ajax({
                            url: '<?php echo base_url('escritorio/operacion/cambiarClave'); ?>',
                            type: 'POST',
                            data: $("#frmClave").serialize(),
                            success: function(response) {
                                if(response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Contraseña actualizada con éxito',
                                        text: 'Por favor vuelva a iniciar sesión utilizando la nueva contraseña',
                                        confirmButtonText: 'Aceptar'
                                    }).then((result) => {
                                        window.location.href = '<?= site_url('cerrarSession'); ?>';
                                    });
                                } else {
                                    // Insert fallido, mostrar mensaje de error con Sweet Alert
                                    $("#divMensajeClave").html(response.mensaje);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Manejar errores si los hay
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            } else {
                $("#divMensajeClave").html('Las contraseñas no coinciden');
            }
        });
    });
</script>
<?php 
    if($renderVista == "Sí") {
        $this->endSection(); 
    }
?>