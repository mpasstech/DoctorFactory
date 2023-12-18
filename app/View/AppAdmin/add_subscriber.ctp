<?php
$login = $this->Session->read('Auth.User');
?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title"> Add Subscriber</h3>

                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('app_admin_inner_tab_subscriber'); ?>

                           <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#number">Manual Mobile Number</a></li>
                                <li><a data-toggle="tab" href="#file">Upload File</a></li>

                            </ul>

                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>

                                <div id="number" class="tab-pane fade in active">


                                    <?php echo $this->Form->create('Subscriber',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>

                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label>Country</label>
                                            <?php echo $this->Form->input('country',array('id'=>'mobile','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Channel for subscribe</label>
                                            <?php $type_array = $this->AppAdmin->getChannelList($login['User']['id'],$login['User']['thinapp_id']); ?>
                                            <?php echo $this->Form->input('channel_id',array('type'=>'select','label'=>false,'empty'=>'Select Channel','options'=>$type_array,'class'=>'form-control cnt','required'=>'required')); ?>
                                        </div>

                                    </div>
                                    <div class="form-group group_div">
                                        <div class="clone_div">
                                            <div class="col-sm-3 number_div">
                                                <label>Enter 10 digit number</label>
                                                <?php echo $this->Form->input('mobile',array('name'=>"mobile[]",'type'=>'text','placeholder'=>'9999999999','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                                                <a href="javascript:void(0);" class="delete_number" style="display: none;"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3 pull-right">

                                            <?php echo $this->Form->submit('Subscribe',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>
                                        <div class="col-sm-3 pull-right">
                                            <?php echo $this->Form->submit('Add More',array('class'=>'Btn-typ5 add_more','type'=>'button')); ?>

                                        </div>
                                    </div>

                                    <?php echo $this->Form->end(); ?>

                                </div>
                                <div id="file" class="tab-pane fade">

                                    <?php echo $this->element('message'); ?>

                                    <?php echo $this->Form->create('Subscriber',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm_2')); ?>

                                    <div class="form-group">

                                        <div class="col-sm-1">
                                            <label>Country</label>
                                            <?php echo $this->Form->input('country_2',array('id'=>'mobile_2','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                                        </div>

                                        <div class="col-sm-3">
                                            <label>App Name</label>
                                            <?php $type_array = $this->AppAdmin->getChannelList($login['User']['id'],$login['User']['thinapp_id']); ?>
                                            <?php echo $this->Form->input('channel_id',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt','required'=>'required')); ?>
                                        </div>


                                        <div class="col-sm-5">
                                            <label>Select csv file </label>
                                            <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <label>&nbsp;</label>
                                         <?php echo $this->Form->submit('Upload',array('label'=>false,'class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>
                                        <div class="col-sm-12">
                                            <h4>Instruction:</h4>
                                            <div class="ins_div">
                                                <ul>
                                                    <li>Name should not have special characters (!@#$%^&*_+={}:":;'[],./<>?)</li>
                                                    <li>Mobile Number should not have +91</li>
                                                    <li>Number format - 1234567890 (ten digit)</li>
                                                    <li>Add mobile number into first column (Maindotry)</li>
                                                    <li>Add subscriber name into second column (Compulsory)</li>
                                                    <li>Add emil into third column (Compulsory)</li>

                                                </ul>
                                                <label>Please enter 10 digit mobile number into first column of csv. For ex. <a href="<?php echo Router::url('/uploads/csv_template.csv',true); ?>" target="_blank">Download csv template</a> </label>

                                            </div>
                                            <div class="csv_img">
                                                <img src="<?php echo Router::url('/thinapp_images/csv.png');?>">
                                            </div>
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
            $(".number_div input").each(function () {

                if(/^[0-9]{4,13}$/.test($(this).val())){
                    $(this).css('border-color','#ccc');
                }else{
                    $(this).css('border-color','red');
                    ret.push(0);
                }
            });

            if($.inArray(0,ret) == -1){
                var countryData = $("#mobile").intlTelInput("getSelectedCountryData");
                $("#mobile").val("+"+countryData.dialCode);
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
</style>




