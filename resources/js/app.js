require('./bootstrap');

import Alpine from 'alpinejs';
import $ from 'jquery';
import Push from 'push.js';
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css'; // Importar el CSS de Dropzone
// import "@fancyapps/fancybox/dist/jquery.fancybox.min.css";
// import "@fancyapps/fancybox";
window.Alpine = Alpine;

Alpine.start();

Push.config({
    serviceWorker: '/sw.js'
})

$(document).ready(function () {
    $('.eliminar').click(function (e) {
        e.preventDefault();
        notie.confirm({
            text: `¿Está seguro que desea eliminar este ${$(this).data('model')}?`,
            submitText: 'Sí, eliminar',
            cancelText: 'No, conservar',
            submitCallback: function () {
                let formulario = $(e.currentTarget).data('form')
                $(`#${formulario}`).submit();
            }
        })
    });

    $('.show-password').click(function (e) {
        e.preventDefault()
        let input = $(this).data('input');
        let status = $(this).data('status');

        if (status) {
            $(this).data('status', 0);
            $(`#${input}`).attr('type', 'password');
            $(this).html(`
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                        clip-rule="evenodd" />
                    <path
                        d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                </svg>
            `);
        } else {
            $(this).data('status', 1);
            $(`#${input}`).attr('type', 'text');
            $(this).html(`
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>
            `);
        }
    });

    $('#buscar-permiso').keyup(function (e) {
        e.preventDefault();
        let key_word = $(this).val();
        let _permisos = permisos.filter(p => p.name.includes(key_word) || p.description.includes(key_word))

        $('.permiso').hide()

        _permisos.forEach(element => {
            console.log(element.id)
            $(`#permiso-${element.id}`).show();
        });
    });

    $('#superadmin').change(function (e) {
        if ($('#superadmin').prop('checked')) {
            $('.permiso > input').prop('checked', true)
            $('.permiso > input').prop('disabled', true)
            $('.permiso').addClass('text-gray-400')
            $('.permiso > input').addClass('bg-gray-600 checked:bg-gray-600')
        } else {
            $('.permiso > input').prop('checked', false)
            $('.permiso > input').prop('disabled', false)
            $('.permiso').removeClass('text-gray-400')
            $('.permiso > input').removeClass('bg-gray-600 checked:bg-gray-600')
        }
    });

    $('.small-message').fadeOut(5000); 
});