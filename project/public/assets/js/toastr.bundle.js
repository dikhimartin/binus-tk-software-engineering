
"use strict";

const TOASTR_OPTIONS =  {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toastr-bottom-full-width",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "4000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

// ToastrSuccess
var ToastrSuccess = function (messages) {
    toastr.options = TOASTR_OPTIONS;
    toastr.success(messages);
}

// ToastrError
var ToastrError = function (messages) {
    toastr.options = TOASTR_OPTIONS;
    toastr.error(messages);
}


// ToastrErrorValidation
var ToastrErrorValidation = function (jqXHR) {
    var errorMessage = '';
    if (jqXHR.responseJSON.status.code == 400 && jqXHR.responseJSON.status.message != undefined) {
      // If there are validation errors, iterate over them and append them to the errorMessage
      var errors = jqXHR.responseJSON.status.message;
      $.each(errors, function (key, value) {
          errorMessage += value.join('\n') + '\n';
      });      
    }
    toastr.options = TOASTR_OPTIONS;
    toastr.error(errorMessage);    
  }

