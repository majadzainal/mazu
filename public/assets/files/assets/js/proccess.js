function saveData(form, callback){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if (!$(form)[0].checkValidity()) {
        $(form)[0].reportValidity();
    } else {
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),
            success: function(res) {
                swal(res.status, res.message, res.status.toLowerCase());
                if(res.status.toLowerCase() === 'Success'.toLowerCase()){
                    $("#closeModal").click();
                    $(form).trigger("reset");
                }
                callback();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

}

function saveDataModal(form, closeModalId, callback){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if (!$(form)[0].checkValidity()) {
        $(form)[0].reportValidity();
    } else {
        $.ajax({
            type: "POST",
            url: $(form).attr("action"),
            data: $(form).serialize(),
            success: function(res) {
                swal(res.status, res.message, res.status.toLowerCase());
                if(res.status.toLowerCase() === 'Success'.toLowerCase()){
                    $(closeModalId).click();
                    $(form).trigger("reset");
                }
                callback();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}

function deleteConfirm(e, callback){
    var text = $(e).attr("data-confirm").split('|');
    var location = $(e).attr("data-url");
    swal({
        title: text[0],
        text: text[1],
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(){
        deleteData(location);
        callback();
    });
}

function printConfirm(e, callback){
    var text = $(e).attr("data-confirm").split('|');
    var data_id = $(e).attr("data-id");
    var form = $(e).attr("form-id");
    swal({
        title: text[0],
        text: text[1],
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(){
        printData(data_id, form);
        callback();
    });
}

function printData(data_id, form){
    $(data_id).printThis({
        debug: false,                   // show the iframe for debugging
        importCSS: true,                // import parent page css
        importStyle: true,             // import style tags
        printContainer: true,           // grab outer container as well as the contents of the selector
        formValues: true,               // preserve input/form values
        canvas: true,                  // copy canvas elements
        header: null,                   // prefix to html
        footer: null,                   // postfix to html
        afterPrint: afterPrint(form),
    });
}

function afterPrint(form){
    updatePrint(form);
    partSupplierChangeFilter();
}

function updatePrint(form){
    $.ajax({
        type: "POST",
        url: $(form).attr("action"),
        data: $(form).serialize(),
        success: function(res) {
            swal(res.status, res.message, res.status.toLowerCase());
            if(res.status.toLowerCase() === 'Success'.toLowerCase()){
                $(form).trigger("reset");
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function deleteData(uri){
    $.ajax({
        type: "GET",
        url: uri,
        success: function(res) {
            swal(res.status, res.message, res.status.toLowerCase());
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //swal('Info', xhr.message, 'error');
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function readImportExcels(inputFileId){
    clearTable('tableBodyImport');

    let fileImported = $(inputFileId)[0].files[0];
    if(fileImported){
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(fileImported);
        fileReader.onload = (event)=>{
            let data = event.target.result;
            let workbook = XLSX.read(data,{type:"binary", cellDates:true});
            workbook.SheetNames.forEach(sheet => {
                rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                displayImportData(rowObject);
            })
        }
    }

    $(inputFileId).val('');
}

function clearTable(idTable){
    var node = document.getElementById(idTable);
        while (node.hasChildNodes()) {
        node.removeChild(node.lastChild);
    }
}


function justDeleteImport(e){
    var row = e.parentNode.parentNode;
    row.parentNode.removeChild(row); // remove the row
}

function doDeleteImport(e){
    var text = $(e).attr("data-confirm").split('|');
    swal({
        title: text[0],
        text: text[1],
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: true
    },
    function(){
        justDeleteImport(e);
    });
}

function getDateNow(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd ;

    return today;
}

async function getData(uri){
    return await $.ajax({
            type: "GET",
            url: uri,
    }).then(function(res) {
        return res.data;
    });

}


function validateEmail(valId, errorId){
    var is_error = 0;
    var email = $(valId).val();
    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if(email === ""){
        $(valId).focus();
        $(errorId).text('This input is required!');
        is_error = 1;

    }else if(email.match(mailformat))
    {
        $(errorId).text('');
    }else{
        $(valId).focus();
        $(errorId).text('Please input valid email');
        is_error = 1;
    }

    return is_error;
}

function validateRequired(inputId, errorId){
    var is_error = 0;
    var trimStr = $.trim($(inputId).val());
    if(trimStr === ""){
        $(inputId).focus();
        $(errorId).text('This input is required!');
        is_error = 1;
    }else{
        $(errorId).text('');
    }

    return is_error;
}

function validateMaxLength(e){
    if (e.value.length > e.maxLength) e.value = e.value.slice(0, e.maxLength);
}
function validateBankAcoount(e){
    var v = e.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
    var matches = v.match(/\d{4,20}/g);
    var match = matches && matches[0] || ''
    var parts = []
    for (i = 0, len = match.length; i < len; i += 4) {
      parts.push(match.substring(i, i + 4))
    }
    e.value = parts.length ? parts.join(' ') : e.value;
}

function validateNPWP(e){
    var val = e.value.replace(/[A-Za-z\W\s_]+/g, '');
    let split = 6;
    const dots = [];

    for (let i = 0, len = val.length; i < len; i += split) {
        split = i >= 2 && i <= 6 ? 3 : i >= 8 && i <= 12 ? 4 : 2;
        dots.push(val.substr(i, split));
    }

    const temp = dots.join('.');
    e.value = temp.length > 12 ? `${temp.substr(0, 12)}-${temp.substr(12, 7)}` : temp;
}

function sortDescDateEffective(partPrice){

    var sortDesc = partPrice.sort(function (a, b) {
        if (b.effective_date < a.effective_date) {
            return -1;
        }
        if (b.effective_date > a.effective_date) {
            return 1;
        }
        return 0;
    });

    return sortDesc;
}

function numbering(num) {
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function formatRupiah(amount, prefix){
	var number_string = amount.toString().replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	remain     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, remain),
	thousand        = split[0].substr(remain).match(/\d{3}/gi);

	if(thousand){
		separator = remain ? '.' : '';
		rupiah += separator + thousand.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
}
