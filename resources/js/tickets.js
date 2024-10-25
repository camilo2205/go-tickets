import daterangepicker from 'daterangepicker';
import $ from 'jquery';
import moment from 'moment';
import select2 from 'select2';
import swal from 'sweetalert';
import 'select2/dist/css/select2.css';
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css'; // Importar el CSS de Dropzone
Dropzone.autoDiscover = false;

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
            title: 'CERRAR',
            text: '¿Está seguro que desea cerrar este ticket?',
            icon: 'warning',
            buttons: ['No, conservar ticket', 'Sí, cerrar ticket']
        }).then(cerrar => {
            if (cerrar) {
                $('#cerrar').val(1)
                let cuerpo = $('#cuerpo').val();
                $('#cuerpo_text').val(cuerpo)
                $('#respuesta-form').submit();
            }
        })
    });


    let token = $('meta[name="csrf-token"]').attr('content');

    if (document.getElementById('dropzoneDragArea')) {
        var myDropzone = new Dropzone("#dropzoneDragArea", {
            paramName: "file_message[]", // Cambia a un arreglo para manejar múltiples archivos si es necesario
            url: "/respuestas",
            // previewsContainer: 'div.dropzone-previews',
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: false,
            parallelUploads: 1,
            maxFiles: 1,
            params: {
                _token: token
            },
            init: function () {
                var myDropzone = this;

                // Maneja el envío del formulario
                $("form#dropzone-form").submit(function (event) {
                    event.preventDefault(); // Prevenir el envío predeterminado del formulario

                    // Crea un objeto FormData que incluye los datos del formulario y los archivos de Dropzone
                    var formData = new FormData(this);
                    myDropzone.getQueuedFiles().forEach(function (file) {
                        formData.append('file_message[]', file);
                    });

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            // Aquí puedes manejar la respuesta del servidor
                            if (result.status === "success") {
                                // Procesar la cola si es necesario
                                // myDropzone.processQueue();
                                getRespuestas();
                                $('#cuerpo').val('');
                                // Limpiar Dropzone y eliminar archivos
                                myDropzone.removeAllFiles();

                            } else {
                                console.log("Error");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error("Error al enviar:", textStatus, errorThrown);
                        }
                    });
                });

                // Manejar el envío de archivos
                this.on('sending', function (file, xhr, formData) {
                    // Puedes agregar datos adicionales aquí si es necesario
                });

                // Manejar la respuesta exitosa
                this.on("success", function (file, response) {
                    // Restablecer el formulario y Dropzone
                    $('#dropzone-form')[0].reset();
                    $('.dropzone-previews').empty();
                });

                this.on("queuecomplete", function () {
                    // Aquí puedes manejar el evento después de que la cola esté completa
                });

                // Manejar múltiples archivos (si es necesario)
                this.on("sendingmultiple", function () {
                    // Puedes manejar el envío de múltiples archivos aquí
                });

                this.on("successmultiple", function (files, response) {
                    // Manejar la respuesta cuando múltiples archivos se han enviado exitosamente
                });

                this.on("errormultiple", function (files, response) {
                    // Manejar errores al enviar múltiples archivos
                });
            }
        });
    }

    // $('#enviar').click(function (e) {
    //     e.preventDefault();
    //     $('#cerrar').val(0)
    //     $('#respuesta-form').submit();
    // });

    function getRespuestas(params) {
        if (typeof ticket !== 'undefined') {
            $.ajax({
                type: "get",
                url: `/tickets/${ticket}/respuestas`,
                success: function (response) {
                    $('#td-respuestas').html('');
                    let respuestas = response.respuestas;
                    respuestas.forEach(respuesta => {
                        let archivosHTML = '';

                        if (respuesta.files) {
                            let archivos = JSON.parse(respuesta.files);
                            archivos.forEach(archivo => {
                                if (/\.(jpg|jpeg|png|gif)$/i.test(archivo.file_name)) {
                                    archivosHTML += `
                                        <br>
                                        <a href="/${archivo.file_path}" data-fancybox="gallery-${respuesta.id}" data-caption="${archivo.file_name}">
                                            <img src="/${archivo.file_path}" alt="${archivo.file_name}" class="w-16 h-16 object-cover">
                                        </a>`;
                                } else {
                                    archivosHTML += `
                                        <br>
                                        <a href="/${archivo.file_path}" target="_blank" class="text-blue-500 underline">
                                            ${archivo.file_name}
                                        </a>`;
                                }
                            });
                        }

                        let html = `
                            <div class="flex ${respuesta.user.cliente || respuesta.user.func_gotele ? 'flex-row' : 'flex-row-reverse'} space-x-2">
                                <div class="rounded-xl m-1 p-3 basis-7/12 ${respuesta.user.cliente || respuesta.user.func_gotele ? 'bg-cyan-300' : 'bg-green-200'}">
                                    <strong>${respuesta.user.name}
                                        (${respuesta.user.cliente ? 'Cliente' : (respuesta.user.funcionario ? 'Funcionario' : 'Admin')})
                                        - ${moment(respuesta.created_at).format('DD/MM/YYYY hh:mm A')} ${respuesta.cerrar ? '(Cerrado)' : ''}
                                    </strong> <i class="fa-solid fa-check-double ${respuesta.visto ? 'text-blue-600' : ''}"></i><br>
                                    ${respuesta.cuerpo}
                                    ${archivosHTML}
                                </div>
                            </div>`;

                        $('#td-respuestas').append(html);
                    });

                    // Inicializar Fancybox
                    Fancybox.bind('[data-fancybox]', {
                        // Opciones de Fancybox (puedes personalizarlas según tus necesidades)
                    });
                }
            });
        }
    }


    getRespuestas();

    setInterval(() => {
        getRespuestas();
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
