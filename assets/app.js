import './styles/app.css';
const $ = require('jquery');
import './Domain/index.js';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/dist/css/bootstrap-datepicker.css';
$(document).ready(function() {
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd',

    });
});
