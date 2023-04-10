
import $ from 'jquery';
import moment from 'moment';
import select2 from 'select2';
import swal from 'sweetalert';
import 'select2/dist/css/select2.css';

$(document).ready(function () {

    $('.items-center #default-checkbox').on('click', function () {
        
        var notificable = $(this).prop('checked');
        let diskId = $(this).data('id');
        $.ajax({
            url: `/clientes/${cliente}/server`,
            method: 'PUT',
            data: {
                notificable: notificable,
                id:diskId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                
            },
            error: function (xhr, status, error) {

            }
        });
    });

});
