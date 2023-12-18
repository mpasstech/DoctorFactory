function flashMessage(type,message){

  $("#flashMessage").remove();
  var color = "green";
  if(type=='error'){
    color = "red";
  }else if(type=='success'){
    color = "green"
  }else if(type=='warning'){
    color = "yellow";
  }
  var div ='<div id="flashMessage" style="min-width:80%;position:fixed;top:9%;right:2%;border-radius:5px;padding: 10px;color:#fff;background: '+color+'"><strong style="font-size:1.1rem;text-transform:capitalize;display:block;" >'+type+'</strong>'+message+'</div>';
  $('body').append(div);
  setTimeout(function(){
    $("#flashMessage").fadeOut(600);
  },4000);

}