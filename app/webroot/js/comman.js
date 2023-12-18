function valid(value){
    if(value == "" || value == null || value === false ){
        return false;
    }
    return true;
}

function loading(obj,flag,msg){


    if(msg === undefined){
        msg = '';
    }
    if(flag===true){
        $(".app_loader_msg").html(msg);
        $(".slot_loading").show();
        obj.attr('readonly',true);
    }else{
        $(".slot_loading").hide();
        $(".app_loader_msg").html("");
        obj.attr('readonly',false);
    }
}
var obj = new Object();
function setObject(dataObject){
    obj = dataObject;
}
function getObject() {
    return obj;
}



function priceValidation( event,obj,decimal_digit){
    if ( (( event.which != 37 || event.which != 39 || event.which != 46 ) && (event.which < 48 || event.which > 57)) || hasDecimalPlace($(obj).val(), decimal_digit)) {
        event.preventDefault();
    }
}

function hasDecimalPlace(value, x) {
    var pointIndex = value.indexOf('.');
    return  pointIndex >= 0 && pointIndex < value.length - x;
}


function calculateValue(discount,quantity,productPrice,discountType,taxRate){

    discount  = (discount)?parseFloat(discount):0;
    quantity  = (quantity)?parseInt(quantity):0;
    var amount = parseFloat((productPrice) * quantity);
    if(discountType == 'PERCENTAGE')
    {
        discount = ((amount * discount )/100);
    }
    if(discount > amount)
    {
        discount = amount;
    }
    var totalAmount = amount-discount;
    var tax = (totalAmount*taxRate)/100;
    totalAmount = totalAmount+tax;
    var tmp = totalAmount.toString().split(".");
    if(tmp.length ==2){
        totalAmount = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
    }
    return totalAmount;
}


function openPayDialog(){

    var appointment_id = readCookie('appointment_id');
    if(appointment_id != '0'){
        $("[data-i="+appointment_id+"]").find('.appointment_pay_btn').trigger('click');
        setACookie('appointment_id','0');
    }
}

function setACookie(cookie_name,cookie_value){
    var exdays=365;
    cookie_value = JSON.stringify(cookie_value);

    var d = new Date();
    d.setTime(d.getTime() + (exdays*1000*60*60*24));
    var expires = "expires=" + d.toGMTString();
    window.document.cookie = cookie_name+"="+cookie_value+"; "+expires;

}

function readCookie(cookie_name) {
    var data=getCookie(cookie_name);
    if (data != "") {
        return data;
    } else {
        return 0;
    }
}
function getCookie(cname) {
    var name = cname + "=";
    var cArr = window.document.cookie.split(';');
    for(var i=0; i<cArr.length; i++) {
        var c = cArr[i].trim();
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}

