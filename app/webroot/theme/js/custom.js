function valid(value){
    if(value == "" || value == null || value === false ){
        return false;
    }
    return true;
}

function loading(obj,flag,msg){
    console.log('asdf');
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