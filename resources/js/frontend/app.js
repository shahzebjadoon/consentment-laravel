import $ from 'jquery';
window.$ = window.jQuery = $;

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Optional: Global options
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "5000"
};

window.toastr = toastr;
