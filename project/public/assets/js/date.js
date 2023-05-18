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

  var KTDatePickerLinked = function () {
    const linkedPicker1Element = document.getElementById("kt_td_picker_linked_1");
    const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element);
    const linked2 = new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_linked_2"), {
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

