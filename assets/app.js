import './styles/app.css';
const $ = require('jquery');
import './Domain/index.js';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.css';
require('dropify');
require('dropify/dist/css/dropify.css');

$(document).ready(function() {
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd',

    });
    $('.dropify-fr').dropify({
        messages: {
            'default': '',
            'replace': 'Faites glisser et déposez ou cliquez pour remplacer',
            'remove':  'Retirer',
            'error':   'Oups, quelque chose de mal s\'est produit.'
        }
    });
});
