<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Proveedor actualizado con éxito";
    } else {
        $mensajeAlerta = "Proveedor creado con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('conf-general/admin-usuarios/operacion/usuarios'); ?>">
    <div id="modalProveedores" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoProveedor" id="selectTipoProveedor" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Proveedor local</option>
                                    <option value="Internacional">Proveedor Internacional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoPersona" id="selectTipoPersona" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Natural">Persona natural</option>
                                    <option value="Juridica">Persona juridica</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContribuyente" id="selectTipoContribuyente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Grandes">Grandes contribuyentes</option>
                                    <option value="Medianos">Medianos contribuyentes</option>
                                    <option value="Otros">Otros contribuyentes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="nombreProveedor" name="nombreProveedor" class="form-control " placeholder="Nombre del proveedor" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="nombreComercial" name="nombreComercial" class="form-control " placeholder="Nombre comercial" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="nrc" name="nrc" class="form-control " placeholder="NRC" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoDocumento" id="selectTipoDocumento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Dui">DUI</option>
                                    <option value="Nit">NIT</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="numeroDocumento" name="numeroDocumento" class="form-control " placeholder="Numero del documento" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectActividadEconomica" id="selectActividadEconomica" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Ventas">Ventas de juegos</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="direccionProveedor" name="direccionProveedor" class="form-control " placeholder="Dirección del proveedor" required>
                            </div>
                        </div>
                    </div>                
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
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
        $("#selectTipoProveedor").select2({
            placeholder: 'Tipo proveedor'
        });

        $("#selectTipoPersona").select2({
            placeholder: 'Tipo persona'
        });

        $("#selectTipoContribuyente").select2({
            placeholder: 'Tipo contribuyente'
        });
        $("#selectTipoDocumento").select2({
            placeholder: 'Tipo de documento'
        });
        $("#selectActividadEconomica").select2({
            placeholder: 'Actividad economica'
        });

        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalUsuario').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Usuario agregado con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                            // Recargar la DataTable después del insert
                            
                        });
                        console.log("Último ID insertado:", response.empleadoId);
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
        });

    });
</script>
