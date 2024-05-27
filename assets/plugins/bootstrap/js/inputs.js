$(document).ready(function() {
    // Función para agregar o remover la clase 'active' a los campos con datos
    function toggleActiveClass(container) {
        $(container).find('input, textarea').each(function() {
            if ($(this).val().trim() !== '') {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
    }

    // Función para agregar clase 'active' al enfocar el campo y removerla al perder el foco si está vacío
    function attachFocusBlurEvents() {
        $(document).on('focus', 'input, textarea', function() {
            $(this).addClass('active');
        });

        $(document).on('blur', 'input, textarea', function() {
            if ($(this).val().trim() === '') {
                $(this).removeClass('active');
            }
        });
    }

    // Inicializar eventos y verificar campos con datos
    function initialize() {
        attachFocusBlurEvents();
        toggleActiveClass(document); // Verificar campos en el documento inicial
    }

    // Inicialización al cargar la página
    initialize();

    // Adjuntar eventos y verificar campos con datos cuando se muestra un modal
    $(document).on('shown.bs.modal', function(e) {
        toggleActiveClass(e.target); // Verificar campos dentro del modal mostrado
    });
});
