import $ from 'jquery';

$(document).ready(function () {
    $('#funcionario_id').change(function (e) { 
        if ($('#funcionario_id').val() == '') {
            $('#estado').val('creado');
        } else {
            $('#estado').val('asignado');
        }
    });
    $('#funcionario_id').trigger('change');

    $('#cerrar_ticket').click(function (e) { 
        e.preventDefault();
        $('#cerrar').val(1)
        $('#enviar').click();
    });
});