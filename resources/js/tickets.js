import $ from 'jquery';
import moment from 'moment';
import swal from 'sweetalert';

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
        swal({
            title: 'ELIMINAR',
            text: '¿Está seguro que desea cerrar este ticket?',
            icon: 'warning',
            buttons: ['No, conservar ticket', 'Sí, cerrar ticket']
        }).then(cerrar => {
            if (cerrar) {
                $('#cerrar').val(1)
                $('#respuesta-form').submit();
            }
        })
    });

    $('#enviar').click(function (e) {
        e.preventDefault();
        $('#cerrar').val(0)
        $('#respuesta-form').submit();
    });

    setInterval(() => {
        $.ajax({
            type: "get",
            url: `/tickets/${ticket}/respuestas`,
            success: function (response) {
                $('#td-respuestas').html('');
                let respuestas = response.respuestas
                respuestas.forEach(respuesta => {
                    $('#td-respuestas').append(`<div class="flex ${ respuesta.user.cliente ? 'flex-row' : 'flex-row-reverse' } space-x-2">
                        <div
                            class="rounded-xl m-1 p-3 basis-7/12 ${ respuesta.user.cliente ? 'bg-cyan-300' : 'bg-green-200' }">
                            <strong>${ respuesta.user.name }
                                (${ respuesta.user.cliente ? 'Cliente' : (respuesta.user.funcionario ? 'Encargado' : 'Admin') })
                                - ${ moment(respuesta.created_at).format('DD/MM/YYYY H:m A') } ${ respuesta.cerrar ? '(Cerrado)' : ''}
                            </strong><br>
                            ${ respuesta.cuerpo }
                        </div>
                    </div>`)
                });
            }
        });
    }, 5000);
});
