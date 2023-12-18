<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
echo $this->Html->script(array('magicsuggest-min.js'));
echo $this->Html->css(array('magicsuggest-min.css'));
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">

                    <h3 class="screen_title">Add Patient</h3>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('app_admin_inner_tab_patient'); ?>

                           <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#number">Menual Mobile Number</a></li>
                                <li><a data-toggle="tab" href="#file">Upload File</a></li>

                            </ul>

                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>

                                <div id="number" class="tab-pane fade in active">

                                    <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                    <div class="form-group group_div">
                                        <div class="clone_div">
                                            <div class="row number_div">
                                                <div class="col-sm-2 mobile_div">
                                                    <label>Enter 10 digit number</label>
                                                    <?php echo $this->Form->input('mobile',array('name'=>"mobile[]",'type'=>'number','placeholder'=>'9999999999','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Name</label>
                                                    <?php echo $this->Form->input('first_name',array('name'=>"first_name[]",'type'=>'text','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>UHID</label>
                                                    <?php echo $this->Form->input('third_party_uhid',array('name'=>"third_party_uhid[]",'type'=>'text','placeholder'=>'UHID','label'=>false,'class'=>'form-control cnt')); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Gender</label>
                                                    <?php echo $this->Form->input('gender',array('name'=>"gender[]",'options'=>array("MALE"=>'Male',"FEMALE"=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Country</label>
                                                    <?php echo $this->Form->input('country_id',array('name'=>"country_id[]",'type'=>'text','label'=>false,'class'=>'form-control country')); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php $state_list =array();?>
                                                    <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                                    <?php echo $this->Form->input('state_id',array('name'=>"state_id[]" ,'type'=>'text','label'=>false,'class'=>'form-control state')); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php $city_list =array();?>
                                                    <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                                    <?php echo $this->Form->input('city_id',array('name'=>"city_id[]",'type'=>'text','label'=>false,'class'=>'form-control city')); ?>
                                                </div>


                                                <div class="col-sm-1">
                                                    <div class="submit"><input class="btn btn-info delete_number" style="display: none;" value="Remove" type="button"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3 pull-right">

                                            <?php echo $this->Form->submit('Add Patient',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>
                                        <div class="col-sm-3 pull-right">
                                            <?php echo $this->Form->submit('Add More',array('class'=>'Btn-typ5 add_more','type'=>'button')); ?>

                                        </div>
                                    </div>

                                    <?php echo $this->Form->end(); ?>

                                </div>


                                <div id="file" class="tab-pane fade">

                                    <?php echo $this->element('message'); ?>

                                    <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm_2')); ?>

                                    <div class="form-group">
                                        <div class="col-sm-2">
                                            <label></label>
                                            <?php echo $this->Form->input('add_receipt',array('type'=>'checkbox','label'=>"Add Receipt")); ?>
                                        </div>
                                        <div class="col-sm-3 doc_container">
                                            <label>Consultant</label>
                                            <?php echo $this->Form->input('appointment_staff_id',array('empty'=>'Select Consultant','type'=>'select','label'=>false,'options'=>$doctor_list,'id'=>'consultDoc','class'=>'form-control message_type cnt')); ?>
                                        </div>
                                        <div class="col-sm-3 add_container">
                                            <label>Address</label>
                                            <?php echo $this->Form->input('appointment_address_id',array('empty'=>'Select Address','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control message_type cnt','id'=>'address_holder')); ?>
                                        </div>
                                        <div class="col-sm-3 amt_container">
                                            <label>Amount</label>
                                            <?php echo $this->Form->input('amount',array('type'=>'number','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">


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
                                                    <li>Patient Name,Mobile Number,Email Address,Gender,Date of Birth,Medical History,Country,State,City fields are maindotry to have is csv file.</li>
                                                    <li style="color:red;">Patient Name,Mobile Number are maindotry to fill</li>
                                                    <li>Gender ( MALE,FEMALE )</li>
                                                    <li>Do not use "," in any field </li>
                                                    <li>Date Of Birth and Created Date should be in formate of 'YYYY-MM-DD' (1991-09-23)</li>
                                                </ul>
                                                <label>Download example file. <a href="<?php echo Router::url('/uploads/Patients.csv',true); ?>" target="_blank">Download csv template</a> </label>

                                            </div>
                                            <div class="csv_img">
                                                <img src="<?php echo Router::url('/thinapp_images/patient_csv_template.png');?>">
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
            var htmlToAdd = '<div class="clone_div">'+
                '<div class="row number_div">'+
                '<div class="col-sm-2 mobile_div">'+
                '<label>Enter 10 digit number</label>'+
            '<?php echo $this->Form->input("mobile",array("name"=>"mobile[]","type"=>"number","placeholder"=>"9999999999","label"=>false,"class"=>"form-control cnt","required"=>"required")); ?>'+
            '</div>'+
            '<div class="col-sm-1">'+
                '<label>Name</label>'+
                '<?php echo $this->Form->input("first_name",array("name"=>"first_name[]","type"=>"text","placeholder"=>"Name","label"=>false,"class"=>"form-control cnt","required"=>"required")); ?>'+
                '</div>'+
                '<div class="col-sm-1">'+
                '<label>UHID</label>'+
               '<?php echo $this->Form->input("third_party_uhid",array("name"=>"third_party_uhid[]","type"=>"text","placeholder"=>"UHID","label"=>false,"class"=>"form-control cnt")); ?>'+
                '</div>'+
                '<div class="col-sm-1">'+
                '<label>Gender</label>'+
                '<div class="input select"><select name="gender[]" class="form-control cnt" id="AppointmentCustomerGender">'+
                '<option value="MALE">Male</option>'+
                '<option value="FEMALE">Female</option>'+
                '</select></div>'+
                '</div>'+
                '<div class="col-sm-2">'+
                '<label>Country</label>'+
                '<?php echo $this->Form->input("country_id",array("name"=>"country_id[]","type"=>"text","label"=>false,"class"=>"form-control country")); ?>'+
                '</div>'+
                '<div class="col-sm-2">'+
                '<?php $state_list =array();?>'+
                '<label>State<i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i></label>'+
                '<?php echo $this->Form->input("state_id",array("name"=>"state_id[]","type"=>"text","label"=>false,"class"=>"form-control state")); ?>'+
                '</div>'+
                '<div class="col-sm-2">'+
                '<?php $city_list =array();?>'+
                '<label>City<i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>'+
                '<?php echo $this->Form->input("city_id",array("name"=>"city_id[]","type"=>"text","label"=>false,"class"=>"form-control city")); ?>'+
                '</div>'+
                '<div class="col-sm-1">'+
                '<div class="submit"><input class="btn btn-info delete_number" style="display: none;" value="Remove" type="button"></div>'+
                '</div>'+
                '</div>'+
                '</div>';

            $(".group_div").append(htmlToAdd);
            $(".group_div .number_div:last").find(".delete_number").show();
            addMagicsuggest($(document));
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


    $(document).ready(function(){


        var valAddRec = $("#AppointmentCustomerAddReceipt:checked").val();

        if(valAddRec == 1)
        {

            $(".doc_container").show();
            $(".add_container").show();
            $(".amt_container").show();

        }
        else
        {
            $(".doc_container").hide();
            $(".add_container").hide();
            $(".amt_container").hide();
        }

        $(document).on("click","#AppointmentCustomerAddReceipt",function(){

            if($("#AppointmentCustomerAddReceipt:checkbox").is(":checked"))
            {

                $(".doc_container").show();
                $(".add_container").show();
                $(".amt_container").show();

            }
            else
            {
                $(".doc_container").hide();
                $(".add_container").hide();
                $(".amt_container").hide();
            }



        });

    });

    $(document).on('change','#consultDoc',function(e){
        $("#address_holder").html('');
        var docID =$(this).val();
        if(docID){
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_doc_address_list",
                data:{docID:docID},
                beforeSend:function(){

                },
                success:function(data){
                    $("#address_holder").html(data);
                },
                error: function(data){
                }
            });
        }

    });

    $(document).on("submit","#AppointmentCustomerAddPatientForm",function(e){
        e.preventDefault();
        if($("#AppointmentCustomerAddReceipt:checkbox").is(":checked"))
        {
            var consultDoc = $("#consultDoc").val();
            var address_holder = $("#address_holder").val();
            var AppointmentCustomerAmount = $("#AppointmentCustomerAmount").val();
            console.log(consultDoc+address_holder+AppointmentCustomerAmount);
            if(consultDoc == '' || address_holder == '' || AppointmentCustomerAmount == '')
            {
                alert("Please provide Consultant, Address, Amount");
            }
            else
            {
                $(document).off("submit","#AppointmentCustomerAddPatientForm");
                $("#AppointmentCustomerAddPatientForm").unbind("submit");
                $("#AppointmentCustomerAddPatientForm").submit();
                $("#AppointmentCustomerAddPatientForm").trigger('submit');
            }
        }
        else
        {
            $(document).off("submit","#AppointmentCustomerAddPatientForm");
            $("#AppointmentCustomerAddPatientForm").unbind("submit");
            $("#AppointmentCustomerAddPatientForm").submit();
            $("#AppointmentCustomerAddPatientForm").trigger('submit');
        }

    });


    function addMagicsuggest(html){
        var msc = $(html).find(".country").magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            data:<?php echo json_encode($country_list,true); ?>,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        var ms = $(html).find(".state").magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        var mscity = $(html).find(".city").magicSuggest({
            allowFreeEntries:true,
            allowDuplicates:false,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        $(msc).on('selectionchange', function(e,m){
            var $this = this;
            var IdArr = this.getSelection();
            if(IdArr[0])
            {
                var country_id =IdArr[0].id;
                if(country_id){
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/get_state_list_json",
                        data:{country_id:country_id},
                        beforeSend:function(){

                            //$('.state_spin').show();
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").show();
                            $('.city, .state').attr('disabled',true).html('');
                        },
                        success:function(data){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").hide();
                            ms.setData(JSON.parse(data));
                            $('.city, .state').attr('disabled',false);
                        },
                        error: function(data){
                        }
                    });
                }
            }

        });

        $(ms).on('selectionchange', function(e,m) {
            var $this = this;
            var IdArr = this.getSelection();
            if (IdArr[0]) {
                var state_id =IdArr[0].id;
                if(state_id){
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/get_city_list_json",
                        data:{state_id:state_id},
                        beforeSend:function(){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").show();
                            $('.city').attr('disabled',true).html('');
                        },
                        success:function(data){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").hide();
                            mscity.setData(JSON.parse(data));
                        },
                        error: function(data){
                        }
                    });
                }
            }
        });
    }
    addMagicsuggest($(document));






</script>

<style>
    .ms-ctn {
       height: unset;
    }
    .form-control{height:unset;}
    #mobile, #mobile_2{
        /*border: medium none !important;
        padding: 0 !important;
        width: 0 !important;*/
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




