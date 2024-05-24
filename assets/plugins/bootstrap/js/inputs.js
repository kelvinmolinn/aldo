$(document).ready(function() {
    // Función para agregar y remover clase 'active'
    function attachEvents() {
        // Delegar el evento de focus y blur al documento
        $(document).on('focus', 'input, textarea', function() {

            $(this).addClass('active');
        });

        $(document).on('blur', 'input, textarea', function() {
            if ($(this).val().trim() === '') {
                $(this).removeClass('active');
            }
        });
    }

    // Adjuntar eventos iniciales
    attachEvents();

    // Adjuntar eventos cuando cualquier modal se muestra
    $('.frmModal').on('shown.bs.modal', function() {
        attachEvents();
    });



});
