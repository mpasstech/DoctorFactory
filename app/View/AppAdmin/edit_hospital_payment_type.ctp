<?php
$login = $this->Session->read('Auth.User');
?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <?php echo $this->element('billing_setting_inner_header'); ?>

            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title">Edit Payment Type</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                    <?php echo $this->element('hospital_payment_type_inner_tab'); ?>



                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>

                                <?php echo $this->Form->create('HospitalPaymentType',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                <div class="form-group">
                                    <div class="col-sm-8">
                                        <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'','label'=>'Enter Payment Type','class'=>'form-control cnt','required'=>'required')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('status',array('type'=>'select','label'=>'Status','options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                    </div>


                                    <div class="col-sm-2">
                                        <?php echo $this->Form->label('&nbsp;'); ?>
                                        <?php echo $this->Form->submit('Save',array('class'=>'btn btn-info col-sm-12')); ?>

                                    </div>
                                </div>

                                <?php echo $this->Form->end(); ?>



                            </div>
                    </div>






                </div>



            </div>


        </div>
    </div>
</div>




<script>
    $(document).ready(function(){





        $(".channel_tap a").removeClass('active');
        $("#v_add_subscriber").addClass('active');

        var tab = "<?php echo $tab; ?>";
        $('[href="#' + tab + '"]').tab('show');




        $(document).on('click','.upload_media',function(e){
            $("#myModal").modal('show');
        })
        $(document).on('click','.add_more',function(e){
            $(".group_div").append($(".clone_div").html());
            $(".group_div .number_div:last").find(".delete_number").show();
        })



        $(document).on('submit','.sub_frm',function(e){
            var ret = [];
            $(".mobile_div input").each(function () {

                if(/^[0-9]{4,13}$/.test($(this).val())){
                    $(this).css('border-color','#ccc');
                }else{
                    $(this).css('border-color','red');
                    ret.push(0);
                }
            });

            if($.inArray(0,ret) == -1){
                return true;
            }
            return false;

        })

        $(document).on('submit','.sub_frm_2',function(e){
                var countryData = $("#mobile_2").intlTelInput("getSelectedCountryData");
                $("#mobile_2").val("+"+countryData.dialCode);
        });




        $(document).on('click','.delete_number',function(e){

            var len = $(".number_div").length;
            if(len >1 ){
                $(this).closest(".number_div").remove();
            }

        })


        $.get("https://ipinfo.io", function(response) {
            //console.log(response.country);
            //console.log(response.city);
            $("#mobile, #mobile_2").intlTelInput({
                allowExtensions: true,
                autoFormat: false,
                autoHideDialCode: false,
                autoPlaceholder:  false,
                initialCountry: response.country,
                ipinfoToken: "yolo",
                nationalMode: true,
                numberType: "MOBILE",
                //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                //preferredCountries: ['cn', 'jp'],
                preventInvalidNumbers: true,
                utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
            });
        }, "jsonp");




    });
</script>

<style>
    #mobile, #mobile_2{
        border: medium none !important;
        padding: 0 !important;
        width: 0 !important;
    }
    .selected-flag {
        border: 1px solid #ccc;
    }
    .ins_div{width: 68%;float: left;}
    .csv_img img{
        border-right: 3px solid green;
        border-radius: 0px 0px 50px 0px;
    }
    .delete_number{
        margin-top: 28px;
    }
    .number_div {
        margin-left: 0;
    }
</style>




