<section class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
        <div id = "contenidoGeneral" class="col-sm-12">
            <h1>Escritorio</h1>
        </div>
    </div>

</div><!-- /.container-fluid -->

</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Usuarios</h5>
                                <h3 class="card-text">1,234</h3>
                            </div>
                            <div>
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Ventas Hoy</h5>
                                <h3 class="card-text">$5,678</h3>
                            </div>
                            <div>
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Reservas pendientes</h5>
                                <h3 class="card-text">89</h3>
                            </div>
                            <div>
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Facturas pendientes</h5>
                                <h3 class="card-text">12</h3>
                            </div>
                            <div>
                                <i class="fas fa-life-ring fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5>Últimos Productos Añadidos</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto A
                        <span class="badge badge-primary badge-pill">Nuevo</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto B
                        <span class="badge badge-primary badge-pill">Nuevo</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto C
                        <span class="badge badge-primary badge-pill">Nuevo</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Actividades Recientes</h5>
                <ul class="list-group">
                    <li class="list-group-item">Producto A ha sido reservado.</li>
                    <li class="list-group-item">Factura generada para la venta de Producto B.</li>
                    <li class="list-group-item">Se ha aplicado retaceo a la compra.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        tituloVentana('Inicio');
    });
</script>