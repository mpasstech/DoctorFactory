<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');
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
                    <h3 class="screen_title"> Add Service/Product</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">




                            <div class="tab-content">


                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#add">Add Product/Service</a></li>
                                    <li><a data-toggle="tab" href="#file">Upload CSV</a></li>
                                </ul>


                                <div class="tab-content">
                                    <?php echo $this->element('message'); ?>
                                    <div id="add" class="tab-pane fade in active">
                                        <h3>Add Product/Service</h3>

                                        <?php echo $this->Form->create('MedicalProduct',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                        <div class="form-group">

                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Service/Product Name','label'=>'Service/Product Name<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
                                            </div>

                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('purchase_price',array('type'=>'text','placeholder'=>'Purchase Price','label'=>'Purchase Price','class'=>'form-control cnt')); ?>
                                            </div>

                                            <div class="col-sm-1">
                                                <?php echo $this->Form->input('mrp',array('type'=>'text','placeholder'=>'MRP','label'=>'MRP<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
                                            </div>

                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('hospital_service_category_id', array('type' => 'select','empty'=>'Service/Product Category','options'=>$category,'label' => 'Select Service/Product Category<span class="red">*</span>', 'class' => 'form-control','required'=>'required')); ?>
                                            </div>

                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('expiry_date',array('type'=>'text','placeholder'=>'Expiry Date','label'=>'Expiry Date',"data-date-format"=>"dd/mm/yyyy",'class'=>'form-control cnt date')); ?>
                                            </div>

                                            <div class="col-sm-1">
                                                <?php echo $this->Form->input('quantity',array('type'=>'number','placeholder'=>'Quantity','label'=>'Quantity','class'=>'form-control cnt')); ?>
                                            </div>

                                        </div>
                                        <div class="form-group">

                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('batch',array('type'=>'text','label'=>'Batch No.','placeholder'=>'Batch No.','class'=>'form-control')); ?>
                                            </div>

                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('medicine_form',array('type'=>'select','label'=>'Medicine Form','empty'=>'Please Select','options'=>$medicineForm,'class'=>'form-control')); ?>
                                            </div>

                                            <div class="col-sm-3">
                                                <label style="display: block;">&nbsp;</label>
                                                <?php echo $this->Form->input('is_price_editable', array('div'=>false,'type' => 'checkbox','label' => 'Is Editable Price')); ?>

                                            </div>



                                            <div class="col-sm-2">
                                                <label>&nbsp;</label><br>
                                                <button type="submit" class="btn btn-info upload" style="width: 50%;">Add</button>
                                            </div>

                                        </div>

                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                    <div id="file" class="tab-pane fade">
                                        <h3>Upload CSV  </h3>

                                        <?php echo $this->Form->create('MedicalProduct',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('hospital_service_category_id', array('type' => 'select','empty'=>'Select Product/Service Category','options'=>$category,'label' => 'Select Product/Service Category', 'class' => 'form-control','required'=>'required')); ?>
                                            </div>

                                            <div class="col-sm-3">
                                                <label>Select csv file </label>
                                                <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control cnt','required'=>'required')); ?>
                                            </div>

                                            <div class="col-sm-3">
                                                <label style="display: block;">&nbsp;</label>
                                                <?php echo $this->Form->input('is_price_editable', array('div'=>false,'type' => 'checkbox','label' => 'Is Editable Price')); ?>

                                            </div>

                                            <div class="col-sm-3">
                                                <label>&nbsp;</label><br>
                                                <button type="submit" class="btn btn-info save">Save</button>
                                                <a class="btn btn-success" href="<?php echo Router::url('/uploads/service_template.csv',true); ?>" download="template.csv">Download Template CSV </a>

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
</div>

<style>
    .red {

        color: red;
        font-size: 15px;

    }
</style>


<script>
    $(document).ready(function(){

        $(".date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});
        $(".date").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


        var tab = "<?php echo $tab; ?>";
        $('[href="#' + tab + '"]').tab('show');




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

        })

        $(document).on('click','.upload',function(e){
            $('#csv_file').trigger('click');
        });


    });
</script>



