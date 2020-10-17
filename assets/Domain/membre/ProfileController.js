const axios = require('axios');
import 'bootstrap-switch-button';
import 'bootstrap-switch-button/css/bootstrap-switch-button.css';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import * as toastr from 'toastr';
import 'toastr/build/toastr.css';
$( document ).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    /**
     *  Function to Enabled and Disabled OTP
     */
    $('#google_authentication_form_state').on('change', function (event) {
        event.preventDefault();
        const url = $(this).closest('form')[0].action;
        axios({
            method: 'post',
            url: url,
            data: {
                state: event.target.checked,
                _token: $('#google_authentication_form__token').val(),
            }
         }).then((response) => {
         //   toastr.info(response.data)
        }, (error) => {
         });
    });



});