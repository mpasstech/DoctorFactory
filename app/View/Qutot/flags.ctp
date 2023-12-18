<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Token Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css','sweetalert2.min.css','bootstrap-tagsinput.css'),array("media"=>'all')); ?>
    <?php echo $this->Html->script(array('jquery.js','bootstrap4.5.3.min.js','bootstrap-tagsinput.min.js','sweetalert2.min.js','jquery.masked-input.min.js')); ?>
</head>
<style>
    .form_box label{
        width: 100%;
        display: block;
    }
    .form_box .bootstrap-tagsinput{
        width: 100%;

    }

    .swal2-modal .swal2-title{
        font-size: 2.2rem;
    }
    .tag_ul{
        margin: 0;
        padding: 0;
        width: 100%;
        display: block;
        float: left;
    }
    .tag_ul li{
        list-style: none;
        float: left;
        display: block;
        border: 1px solid;
        margin: 2px;
        padding: 4px 12px;
        border-radius: 20px;
        color: #1751f5;
        font-size: 1.2rem;
    }

    .swal2-modal{
        padding: 6px 10px;

    }

    .swal2-modal .swal2-styled{
        padding: 6px 10px;
        font-size: 1rem;
    }
    .tag_ul li.selected{
        color: #fff;
        background: #1751f5;
    }


    .bootstrap-tagsinput {
        line-height: 26px;
        text-align: left;
    }

    .swal2-modal .swal2-content{
        text-align: left;
    }

    .input_label{
        margin-top: 13px;
        display: block;
        float: left;
        width: 100%;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .symt_box{
        background: #f5f5f5;

        padding-bottom: 20px;
    }
    .symt_box .box{
        padding: 0;
    }
    .box{
        margin: 15px 0px;
    }
    .box .col-12{
        padding: 0;
    }
</style>

<body>
<div class="main_box container" >
    <div class="row">

        <div class="col-12">
            <h3 class="top_heading" style="text-align: center;">Patient Health History</h3>
        </div>
        <div class="col-12 form_box">

            <H4 style="text-align: center;"><?php echo $data['name']; ?></H4>

            <?php
                    $tmp = explode("###",$data['notes']);
                    $symptoms_val =@$tmp[0];
                    $duration_val =@$tmp[1];
            ?>

            <div class="row">
                <div class="col-12 symt_box">

                    <div class="col-12 box" data-id="symptoms">
                        <label>Symptoms</label>
                        <input class="form-control" data-role="tagsinput" value="<?php echo $symptoms_val; ?>"  type="text"  id="symptoms" />
                    </div>
                    <div class="col-12 box" data-id="duration">
                        <label>Duration</label>
                        <input class="form-control" data-role="tagsinput" value="<?php echo $duration_val; ?>"  type="text"  id="duration" />
                    </div>
                </div>

            </div>


            <div class="row box" data-id="flags">
                <div class="col-12">
                    <label>Flags</label>
                    <input   class="form-control" data-role="tagsinput" value="<?php echo $data['flag']; ?>"  type="text"  id="flags" />
                </div>
            </div>
            <div class="row box" data-id="allergy">
                <div class="col-12">
                    <label>Allergy</label>
                    <input  class="form-control" data-role="tagsinput" value="<?php echo $data['allergy']; ?>" type="text"  id="allergy" />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label>History</label>
                    <textarea  class="form-control" placeholder="If any disease or operation has happened to this patient and family member, then please mention here" rows="5"  id="history" ><?php echo $data['medical_history']; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label>Height (Feet)</label>
                    <input  class="form-control" value="<?php echo $data['height']; ?>" type="number"  id="height" />
                </div>

                <div class="col-6">
                    <label>Weight (kg)</label>
                    <input  class="form-control" value="<?php echo $data['weight']; ?>" type="number"  id="weight" />
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <label>BP (mm Hg)</label>
                    <input  data-masked-input="99-999" placeholder="00-000" class="form-control" value="<?php echo $data['bp_systolic']; ?>"  type="text"  id="bp_systolic" />
                </div>

                <div class="col-6">
                    <label>Temperature (°F)</label>
                    <input  class="form-control" value="<?php echo $data['temperature']; ?>" type="number"  id="temperature" />
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <label>Oxygen (mm Hg)</label>
                    <input  class="form-control" value="<?php echo $data['o_saturation']; ?>" type="number"  id="o_saturation" />
                </div>
            </div>

            <div class="form-group">
                <button type="button" style="float: right;" class="btn btn-success" id="save_btn">Save </button>
            </div>
        </div>
    </div>
</div>

</body>

<script>
    $( document ).ready(function() {

        $(document).on("click",".tag_ul li",function(e){
            if($(this).hasClass('selected')){
                $(this).removeClass('selected');
            }else{
                $(this).addClass('selected');
            }
        });

        $(document).on("click",".form_box .bootstrap-tagsinput input",function(e){
            var id = $(this).closest(".box").attr('data-id');
            var added = '';
            if($("#"+id).val()){
                added = $("#"+id).val().split(',');
            }


            if(id=='symptoms'){
                var title = "Add Symptoms";
                var string = "<?php echo $symptoms; ?>";
                var flags = string.split(',');
            }else if(id=='duration'){
                var title = "Add Duration";
                var string = "<?php echo $duration; ?>";
                var flags = string.split(',');
            }else if(id=='flags'){
                var title = "Add Flags";
                var flags =['Diabetes','BP','Hypertension','Alcoholic','Smoker'];
            }else{
                title = "Add Allergy";
                var flags =['Anaphylaxis','Antibiotics','Aspirin','Bloating','Breathing','Bronchial Asthma','Cramping abdominal pain','Diarrhea','Diseases','Dizziness','Egg','Fainting','Fainting  ','Fish','Garlic','Hives','Inflammation','Itching','Itching or hives','Mushrooms','Nausea','Pain killers','Peanut','Peanut butter','Penicillin','Runny nose','Sea food','Shortness of breath','Shortness of breath','Skin disease','Skin rashes','Sneezing','Stomach pain','Sulpha','Swelling','Swelling of the lips','Throat and mouth swelling','Tongue or throat','Trouble breathing ','Urticaria','Vomiting','Vomiting ','Vomiting and diarrhoea','Wheezing'];
            }


            var html_string = "<h5>Chose "+id+" here</h5><ul class='tag_ul'>";

            console.log(flags);

            $.each(flags,function (index,value) {
                var cls = (added.includes(value))?'selected':'';
                html_string += "<li class="+cls+" >"+value+"</li>";
            });
            html_string += "</ul>";
            if(id=='flags' || id=='allergy'){
                var input_tag =[];
                $.each(added,function (index,value) {
                    if(!flags.includes(value)){
                        input_tag.push(value);
                    }
                });
                input_tag = input_tag.join(',');
                var str = "Please select "+id+" from above list, if you do not find your "+id+" in the above list of selection, please enter your "+id+" to add in your patient info."
                html_string += "<div class='dialog_tag_box'><div class='input_label'>"+str+"</div><input value='"+input_tag+"'  data-role='tagsinput' type='text' id='input_tag'></div>";
            }


            var swalBox =  swal({
                title: title,
                showCancelButton: true,
                confirmButtonText:'Done',
                allowOutsideClick: false,
                html:html_string,
                preConfirm: function () {
                     $('#'+id).tagsinput('removeAll');
                        var all_tag =[];
                        $(".tag_ul .selected").each(function (index,value) {
                            all_tag.push($(this).text());
                        });

                        if(id=='flags' || id=='allergy'){
                            var extra_tag = $('#input_tag').val().split(',');
                            $.each(extra_tag,function (index,value) {
                                all_tag.push(value);
                            });
                        }

                        console.log(all_tag);

                        $.each(all_tag,function (index,value) {
                            $('#'+id).tagsinput('add',value);
                            $('#'+id).tagsinput('refresh');
                        });
                        $(".swal2-container").remove();
                        $("body").removeClass("swal2-shown swal2-iosfix");
                }

            }).then(function (result) {
                swalBox.close();
            }).catch(swal.noop);

            setTimeout(function () {
                $('#input_tag').tagsinput();
               // $(".bootstrap-tagsinput .tag").css({'backgound':'blue'});
             //   $("#swal2-content .bootstrap-tagsinput").css({'width':'100%'});
            },100);

        });


        $(document).on("click","#save_btn",function(e){
            var $btn =  $(this);
            var data = {
                flags:$('#flags').val(),
                allergy:$('#allergy').val(),
                symptoms:$('#symptoms').val(),
                duration:$('#duration').val(),
                history:$("#history").val(),
                height:$("#height").val(),
                weight:$("#weight").val(),
                bp_systolic:$("#bp_systolic").val(),
                temperature:$("#temperature").val(),
                o_saturation:$("#o_saturation").val(),
                ac:"<?php echo $data['appointment_customer_id']; ?>",
                ai:"<?php echo base64_encode($data['appointment_id']); ?>",
                c:"<?php echo $data['children_id']; ?>"
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/save_flag',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Saving..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text('Save');
                    var response = JSON.parse(response);
                    alert(response.message);
                    if(response.status==1){
                        window.location.reload();
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text('Book Token');
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });
        });

    });
</script>
</html>


