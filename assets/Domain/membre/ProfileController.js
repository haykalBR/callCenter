const axios = require('axios');
$( document ).ready(function() {
   $('#qrocde-otp').click(function (event){
       event.preventDefault();
       const url = $(this).closest('form')[0].action;
           axios({
               method: 'post',
               url: url,
               data: {
                   qrcode: $('#qrocde').val()
               }
           })
           .then((response) => {
                   console.log(response);
            }, (error) => {
                   console.log(error);
            });

   })
});