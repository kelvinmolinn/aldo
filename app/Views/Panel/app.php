<?php 
    if($renderVista == "Sí") {
        $this->extend('Panel/plantilla');
        $this->section('contenido');
    }
?>
    <script>
        $(document).ready(function() {
            tituloVentana('<?= $tituloVentana; ?>');
            cambiarInterfaz('<?= $route; ?>', <?= $campos; ?>);
        });
    </script>
<?php 
    if($renderVista == "Sí") {
        $this->endSection(); 
    }
?>