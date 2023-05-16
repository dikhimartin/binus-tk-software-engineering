
"use strict";

// SwalSuccess
// usage
// SwalSuccess(messages).then(function (result) {
//     if (result.isConfirmed) {
//         // Do something
//     }
// }); 
var SwalSuccess = function (messages) {
    return Swal.fire({
        text: messages,
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    })
}



// SwalError
var SwalError = function (errorThrown) {
    return  Swal.fire({
        text: errorThrown,
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
}

var SwalValidation = function (jqXHR) {
    var errorMessage = '';
    if (jqXHR.responseJSON.status.code == 400 && jqXHR.responseJSON.status.message != undefined) {
      // If there are validation errors, iterate over them and append them to the errorMessage
      var errors = jqXHR.responseJSON.status.message;
      $.each(errors, function (key, value) {
          errorMessage += value.join('\n') + '\n';
      });      
    //   if (errors.indexOf('\n') > -1) {
    //     // If there are multiple error messages, split the string and iterate over the array
    //     var errorMessages = errors.split('\n');
    //     $.each(errorMessages, function (key, value) {
    //       errorMessage += value + '\n';
    //     });
    //   } else {
    //     // If there is only one error message, append it to the errorMessage
    //     errorMessage = errors;
    //   }
    }
    return Swal.fire({
        text: errorMessage,
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
  }
