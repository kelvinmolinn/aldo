<h2>Permisos de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btn" class="btn btn-primary" onclick="modal();">
            <i class="fas fa-save"></i>
            Nuevo permiso
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Permiso</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                   <th></th>
                    <th></th>
                    <th></th>

                <td>
                    <button class="btn btn-primary mb-1">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="">Editar</span>
                    </button><br>
                    <button class="btn btn-primary mb-1">0 Roles</button><br>
                    <button class="btn btn-danger mb-1">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div id="modalPermiso" class="modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo permiso</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-outline">
                            <input type="text" class="form-control" placeholder="Permiso">
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" placeholder="Descripción de permiso">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#selectGenero').select2({
            placeholder: 'Fecha',
            dropdownParent: $('.modal-content')   
        });

        $('#selectRol').select2({
            placeholder: 'Roles',
            dropdownParent: $('.modal-content')
        })
    });

    function modal(){
        $('#modalPermiso').modal(

        );
    }
</script>