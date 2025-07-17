import $ from 'jquery';
import select2 from 'select2';
import swal from 'sweetalert';
import 'select2/dist/css/select2.css';

$(document).ready(function () {
    $("#cliente_id,#encargado_id").select2({
        tags: true,
        placeholder: '-- seleccione tags--',
        // theme: "classic",
        allowClear: true,
    })

    // Confirmación al enviar el formulario de creación de tarea
    $('form[action$="tareas/store"]').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: '¿Guardar tarea?',
            text: '¿Estás seguro de crear esta tarea?',
            icon: 'info',
            buttons: true,
            dangerMode: false,
        }).then((willSave) => {
            if (willSave) {
                this.submit();
            }
        });
    });

    $('.select2').select2({
        width: '100%',
        placeholder: 'Selecciona una opción',
        allowClear: true,
        dropdownAutoWidth: true
    });

    // Enfocar el input de búsqueda al abrir select2
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
});
