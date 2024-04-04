import daterangepicker from 'daterangepicker';
import $ from 'jquery';
import moment from 'moment';
import select2 from 'select2';
import swal from 'sweetalert';
import 'select2/dist/css/select2.css';

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

    function getRepuestas(params) {
        if (typeof ticket !== 'undefined') {
            $.ajax({
                type: "get",
                url: `/tickets/${ticket}/respuestas`,
                success: function (response) {
                    $('#td-respuestas').html('');
                    let respuestas = response.respuestas
                    respuestas.forEach(respuesta => {
                        /* muestra mensaje no visto  */
                        if (respuesta.visto == 0) {
                            $('#td-respuestas').append(`<div class="flex ${respuesta.user.cliente || respuesta.user.func_gotele ? 'flex-row' : 'flex-row-reverse'} space-x-2">
                            <div
                                class="rounded-xl m-1 p-3 basis-7/12 ${respuesta.user.cliente || respuesta.user.func_gotele ? 'bg-cyan-300' : 'bg-green-200'}">
                                <strong>${respuesta.user.name}
                                    (${respuesta.user.cliente ? 'Cliente' : (respuesta.user.funcionario ? 'Encargado' : 'Admin')})
                                    - ${moment(respuesta.created_at).format('DD/MM/YYYY hh:mm A')} ${respuesta.cerrar ? '(Cerrado)' : ''}
                                </strong> <i class="fa-solid fa-check-double"></i><br>
                                ${$(respuesta.cuerpo).html()}
                            </div>
                        </div>`)
                        }
                        /* muestra mensaje en visto  */
                        else {
                            $('#td-respuestas').append(`<div class="flex ${respuesta.user.cliente || respuesta.user.func_gotele ? 'flex-row' : 'flex-row-reverse'} space-x-2">
                            <div
                                class="rounded-xl m-1 p-3 basis-7/12 ${respuesta.user.cliente || respuesta.user.func_gotele ? 'bg-cyan-300' : 'bg-green-200'}">
                                <strong>${respuesta.user.name}
                                    (${respuesta.user.cliente ? 'Cliente' : (respuesta.user.funcionario ? 'Encargado' : 'Admin')})
                                    - ${moment(respuesta.created_at).format('DD/MM/YYYY hh:mm A')} ${respuesta.cerrar ? '(Cerrado)' : ''}
                                </strong><i class="fa-solid fa-check-double text-blue-600"></i><br>
                                ${$(respuesta.cuerpo).html()}
                            </div>
                        </div>`)
                        }
                    });
                }
            });
        }
    }
    
    getRepuestas();

    setInterval(() => {
        getRepuestas();
    }, 5000);

    $('#tagsTickets #chip').click(function (e) {
        e.preventDefault();
        let tagsId = $(this).data('id');
        window.location.href = `/tickets?tags_id%5B%5D=${tagsId}`;
    });

    $('.filtro').change(function (e) {
        e.preventDefault();
        $('#filtrar').submit();
    });

    $(".tags").select2({
        tags: true,
        placeholder: '-- seleccione tags--',
        theme: "classic",
        allowClear: true,
    })
});
