import daterangepicker from 'daterangepicker';
import $ from 'jquery';
import moment from 'moment';
import swal from 'sweetalert';


$(function () {
    $('input[name="fecha"]').daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                'month').endOf('month')]
        },
        alwaysShowCalendars: true,
        locale: {
            "format": "YYYY-MM-DD",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Rango Personalizado",
            "daysOfWeek": [
                "D",
                "L",
                "M",
                "M",
                "J",
                "V",
                "S"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    });

    $('input[name="fecha"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $(this).trigger('change')
    });

    $('input[name="fecha"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $(this).trigger('change')
    });
});

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
        if (typeof ticket !== 'undefined') {
            $.ajax({
                type: "get",
                url: `/tickets/${ticket}/respuestas`,
                success: function (response) {
                    $('#td-respuestas').html('');
                    let respuestas = response.respuestas
                    respuestas.forEach(respuesta => {
                        $('#td-respuestas').append(`<div class="flex ${respuesta.user.cliente ? 'flex-row' : 'flex-row-reverse'} space-x-2">
                            <div
                                class="rounded-xl m-1 p-3 basis-7/12 ${respuesta.user.cliente ? 'bg-cyan-300' : 'bg-green-200'}">
                                <strong>${respuesta.user.name}
                                    (${respuesta.user.cliente ? 'Cliente' : (respuesta.user.funcionario ? 'Encargado' : 'Admin')})
                                    - ${moment(respuesta.created_at).format('DD/MM/YYYY H:m A')} ${respuesta.cerrar ? '(Cerrado)' : ''}
                                </strong><br>
                                ${respuesta.cuerpo}
                            </div>
                        </div>`)
                    });
                }
            });
        }
    }, 5000);

    $('.filtro').change(function (e) {
        e.preventDefault();
        $('#filtrar').submit();
    });
});
