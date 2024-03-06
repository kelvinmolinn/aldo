<h2>Gestión de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btn" class="btn btn-primary" onclick="modal();">
            <i class="fas fa-save"></i>
            Nuevo usuario
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    <tr>
        <th></th>
      <td>  Nombre completo:    <br>
            Sucursal:           <br>
            DUI:
      </td>
      <td>
        Correo:         <br>
        Rol:            <br>
        En linea:       <br>
      </td>
      <td>
        Activo
      </td>
      <td>
        <button class="btn btn-primary mb-1">
            <i class="fas fa-pencil-alt"></i>
            <span class="">Editar Usuario</span>
        </button><br>
        <button class="btn btn-success mb-1">Restablecer acceso</button><br>
        <button class="btn btn-primary mb-1">0 Sucursales</button><br>
        <button class="btn btn-primary mb-1">Activar</button>
      </td>
    </tr>
  </tbody>
    </table>
</div>

<div id="modalUsuario" class="modal" tabindex="-1">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo usuario</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="number" class="form-control" placeholder="DUI">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" class="form-control" placeholder="Fecha de nacimiento">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="selectGenero" id="selectGenero" style = "width: 100%;">
                        <option value=""></option>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Primer nombre">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Segundo nombre">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Primer apellido">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Segundo apellido">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="email" class="form-control" placeholder="DUI">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-select-control">
                    <select name="selectRol" id="selectRol" style = "width: 100%;">
                        <option value=""></option>
                        <option value="1">Jefe</option>
                        <option value="2">Jefe 2</option>
                    </select>
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
        $('#modalUsuario').modal(

        );
    }
</script>