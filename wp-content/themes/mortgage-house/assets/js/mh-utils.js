
/** Disable the previous dates on register.*/

window.onload = function(){
    var today = new Date().toISOString().split('T')[0];
    var dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(element => {
        element.setAttribute('min', today);
    });
}   