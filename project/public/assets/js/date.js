function convertDateTime(dateTimeString) {
    const dateTime = new Date(dateTimeString);
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
    return dateTime.toLocaleDateString('id-ID', options);
}

function convertDate(dateString) {
      const dateOnly = new Date(dateString + 'T00:00:00');
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      return dateOnly.toLocaleDateString('id-ID', options);
}

function formatDate(dateString) {
    var options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    var dateParts = dateString.split(' ');
    var day = dateParts[0];
    var month = dateParts[1];
    var year = dateParts[2];
  
    // Convert month name to month number
    var monthIndex = new Date(Date.parse(month + ' 1, 2000')).getMonth() + 1;
  
    // Ensure day and month have leading zeros if necessary
    day = day.padStart(2, '0');
    monthIndex = monthIndex.toString().padStart(2, '0');
  
    var formattedDate = `${year}-${monthIndex}-${day}`;
  
    return formattedDate;
}

function formatDateFilterRange(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}


function calculateNumberOfDays(startDate, endDate) {
    // Get the timestamps of the start date and end date
    let startTimestamp = new Date(startDate).getTime();
    let endTimestamp = new Date(endDate).getTime();
  
    // Calculate the difference in milliseconds
    let difference = endTimestamp - startTimestamp;
  
    // Convert milliseconds to days
    let numberOfDays = Math.floor(difference / (1000 * 60 * 60 * 24));
  
    return numberOfDays;
  }

var KTDatePickerLinked = function () {
    const linkedPicker1Element = document.getElementById("kt_td_picker_linked_1");

    const linked1 = new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_linked_1"), {
        display: {
            viewMode: "calendar",
            components: {
                hours: false,
                minutes: false,
                seconds: false
            }
        }
    });


    const linked2 = new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_linked_2"), {
        display: {
            viewMode: "calendar",
            components: {
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        useCurrent: false,
    });
    
    //using event listeners
    linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
        linked2.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });
    }); 
    
    //using subscribe method
    const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
        linked1.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
    });
}

