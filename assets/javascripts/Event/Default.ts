import * as $ from 'jquery';
import 'bootstrap';
import 'bootstrap-switch-button';
import 'dropify';
import 'bootstrap-datepicker';
import dropifyConfig from '../Config/dropify';
import datepickerConfig from '../Config/datepicker';

export default class Default{
    
    constructor(){
        $(document).ready(function() {
            $('.js-datepicker').datepicker(datepickerConfig);
            $('.dropify-fr').dropify(dropifyConfig);
        });
    }
}
