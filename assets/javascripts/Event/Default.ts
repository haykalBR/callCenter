import * as $ from 'jquery';
import 'bootstrap';
import 'bootstrap-switch-button';
import 'dropify';
import 'bootstrap-datepicker';

export default class Default{
    constructor(){
        $(document).ready(function() {
            $('.js-datepicker').datepicker({
                format: 'dd/mm/yyyy',
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
    }
}
