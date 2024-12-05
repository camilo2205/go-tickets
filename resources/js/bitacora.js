import daterangepicker from 'daterangepicker';
import $ from 'jquery';
import moment from 'moment';
import select2 from 'select2';
import swal from 'sweetalert';
import 'select2/dist/css/select2.css';

$('.datetime').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    timePickerIncrement: 15,
    alwaysShowCalendars: true,
    ranges: {
        'Hoy': [moment().set({ hour: 8, minute: 0 }), moment()],
        'Ayer': [moment().subtract(1, 'days').set({ hour: 8, minute: 0 }), moment().subtract(1, 'days').set({ hour: 17, minute: 0 })],
        'Esta Semana': [moment().set({ weekday: 1, hour: 8, minute: 0 }), moment().set({ hour: 17, minute: 0 })],
        'Ultimos 7 días': [moment().subtract(7, "days").set({ hour: 8, minute: 0 }), moment().set({ hour: 17, minute: 0 })],
        'Este Mes': [moment().startOf('month').set({ hour: 8, minute: 0 }), moment()],
        'Ultimos 30 días': [moment().subtract(30, "days").set({ hour: 8, minute: 0 }), moment()],
        'El mes pasado': [moment().subtract(1, 'month').startOf('month').set({ hour: 8, minute: 0 }), moment().subtract(1, 'month').endOf('month').set({ hour: 17, minute: 0 })]
    },
    locale: {
        "format": "YYYY-MM-DD HH:mm",
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
    },
    startDate: moment().set({ hour: 8, minute: 0 }),
    endDate: moment(),
})

$('#inicio-fin').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    $(this).trigger('change')
});

$('#inicio-fin').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
    $(this).trigger('change')
});

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
});

if ($('#descripcion').length > 0) {
    tinymce.init({
        selector: "#descripcion",
        menu: {
            ver: { title: 'Ver', items: 'code' }
        },
        menubar: 'ver',
        language: 'es-MX',
        license_key: 'gpl',
        plugins: [
            'code', 'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    })
}

$(document).ready(function () {
    $("#proyecto_filter").select2({
        placeholder: '-- seleccione proyecto --',
        allowClear: true,
    })

    $("#cliente_id").select2({
        placeholder: '-- seleccione cliente --',
        allowClear: true
    })

    $("#proyecto").select2({
        tags: true,
        placeholder: '-- seleccione proyecto --',
        allowClear: true
    })

    $('#inicio_fin').on('apply.daterangepicker', function (ev, picker) {
        let inicio_fin = $(ev.currentTarget).val().split(' - ');
        let inicio = moment(inicio_fin[0]);
        let fin = moment(inicio_fin[1]);
        let esfuerzo = 0;
        let semanas = fin.diff(inicio, "weeks", true);
        if (semanas > 1) {
            console.log("semanas: " + semanas)
            esfuerzo = semanas * 5 * 8;
        } else {
            let dias = fin.diff(inicio, "days", true)
            if (dias > 1) {
                console.log("dias: " + dias)
                esfuerzo = dias * 8;
            } else {
                esfuerzo = fin.diff(inicio, "hours", true)
                console.log("horas: " + esfuerzo)
            }
        }

        $('#esfuerzo').val(esfuerzo.toFixed(0));
    })

    $('#inicio_fin').trigger('apply.daterangepicker');
})
