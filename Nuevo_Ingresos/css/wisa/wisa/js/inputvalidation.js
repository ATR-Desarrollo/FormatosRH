

function showInputWarning(fieldsRequired){

    var i;
    for (i=1; i<=fieldsRequired ; i++){
       //alert(document.getElementById('val1').value.length);
        if (document.getElementById('txtInput' + i).value.length == 0){
            //document.getElementById('txtInput' + i).style.color = "#888";
            //document.getElementById('txtInput' + i).value = 'Capturar';
            document.getElementById('txtInput' + i).style.backgroundColor = "#F5A9A9";
        }
    }

}

function hideInputWarning(fieldsRequired){

    var i;
    for (i=1; i<=fieldsRequired ; i++){

        if (document.getElementById('txtInput' + i).value.length == 0){
            //document.getElementById('txtInput' + i).value = '';
            //document.getElementById('txtInput' + i).style.color = "#000000";
            document.getElementById('txtInput' + i).style.backgroundColor = "#FFFFFF";
        }

    }

}

function numericOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    if ( charCode == 8  || charCode == 9 || charCode == 13 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 48 && charCode <= 57) ) {

        } else {
            event.preventDefault();
        }
    }

}

function numericNegativeOnly(event, inputValue){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    var keychar = String.fromCharCode(charCode);

    if ( charCode == 45 && inputValue.length > 0){
        event.preventDefault();
        return;
    }
    
    if ( charCode == 8  || charCode == 9 || charCode == 13 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 48 && charCode <= 57) || charCode == 45 ) {

        } else {
            event.preventDefault();
        }
    }

}

function decimalOnly(event, inputValue){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    var keychar = String.fromCharCode(charCode);

    if ( charCode == 46 && inputValue.indexOf(keychar)> -1 ){
        event.preventDefault();
        return;
    }

    if ( charCode == 8  || charCode == 9 || charCode == 13 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 48 && charCode <= 57) || charCode == 46 ) {

        } else {
            event.preventDefault();
        }
    }

}

function letterOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    if ( charCode == 8  || charCode == 9 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 39 || charCode == 34 ) {

        } else {
            event.preventDefault();
        }
    }
}

function alphaNumericOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    if ( charCode == 8  || charCode == 9 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) ) {

        } else {
            event.preventDefault();
        }
    }
}

function phoneOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    if ( charCode == 8  || charCode == 9 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 48 && charCode <= 57) || charCode == 45 ) {

        } else {
            event.preventDefault();
        }
    }

}

function emailOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    if ( charCode == 8  || charCode == 9 || charCode == 32 ) {
    // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if ( (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) || charCode == 46 || charCode == 64 ) {

        } else {
            event.preventDefault();
        }
    }
}

function readOnly(event){

    var e = event
    if(window.event){ // IE
        var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
        var charCode = e.which
    }

    event.preventDefault();

}
