<?php
$login = $this->Session->read('Auth.User.User');
$category = $this->AppAdmin->getHospitalServiceCategoryArray($login['thinapp_id']);

?>
<?php  echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js')); ?>


<div class="Home-section-2">

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <?php echo $this->element('inventory_pharma_billing_setting_inner_header'); ?>
    </div>

    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
        <div class="container">

            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title"> Edit Service/Product</h3>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>

                                <?php echo $this->Form->create('MedicalProduct',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                <div class="form-group">

                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'','label'=>'Enter Service/Product Name<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
                                    </div>

                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('hospital_service_category_id', array('type' => 'select','empty'=>'Select Service Category','options'=>$category,'label' => 'Select Service Category<span class="red">*</span>', 'class' => 'form-control','required'=>'required')); ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('medicine_form',array('type'=>'select','label'=>'Medicine Form','empty'=>'Please Select','options'=>$medicineForm,'class'=>'form-control')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $isPackage = $this->request->data['MedicalProduct']['is_package']; ?>
                                    <?php if(($hasQuantity == false) || ($isPackage == 1) ){ ?>
                                        <div class="col-sm-1">
                                            <?php echo $this->Form->input('price',array('type'=>'text','placeholder'=>'Price','label'=>'Enter Price<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
                                        </div>
                                    <?php } ?>


                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('status',array('type'=>'select','label'=>'Status','options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                        </div>
                                    <div class="col-sm-2">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->input('is_price_editable', array('type' => 'checkbox','label' => 'Is Editable Price')); ?>
                                    </div>

                                        <div class="col-sm-3">
                                            <?php echo $this->Form->label('&nbsp;'); ?>
                                            <?php echo $this->Form->submit('Save',array('class'=>'btn btn-info col-md-12')); ?>

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


<style>
    .red {

        color: red;
        font-size: 15px;

    }
</style>

<script>
    $(document).ready(function(){





        $(".channel_tap a").removeClass('active');
        $("#add_cat_tab").addClass('active');




        $(document).on('click','.upload_media',function(e){
            $("#myModal").modal('show');
        })
        $(document).on('change','#category',function(e){
            var cat_id = $(this).val();
            if(cat_id !=''){
                var btn = $(this).find('[type=submit]');

                $.ajax({
                    url:baseurl+"/service/get_tax_rate_list",
                    type:'POST',
                    data:{
                        category_id:cat_id,
                    },
                    beforeSend:function(){
                        btn.button('loading').val('Loading tax rate...');
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        var option = "";
                        if(response.status==1){

                        }
                        btn.button('reset');
                    },
                    error:function () {
                        btn.button('reset');
                        $(".show_msg_text").html("Sorry something went wrong on server.").css('color','red');
                    }
                })
            }
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

        });

        $(document).on('change','#MedicalProductIsPackage',function(){
            console.log('here');
            if($(this).prop('checked') == true){
                $(".price_container").show();
            }
            else
            {
                $(".price_container").hide();
            }
        });






    });
</script>



