<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');
$category = $this->AppAdmin->getHospitalServiceCategoryArray($login['thinapp_id']);

?>

<?php echo $this->Form->create('MedicalProduct',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Service/Product Name','label'=>'Service/Product Name<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
    </div>
</div>



<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('purchase_price',array('type'=>'text','placeholder'=>'Purchase Price','label'=>'Purchase Price','class'=>'form-control cnt')); ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('mrp',array('type'=>'text','placeholder'=>'MRP','label'=>'MRP<span class="red">*</span>','class'=>'form-control cnt','required'=>'required')); ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('hospital_service_category_id', array('type' => 'select','empty'=>'Service/Product Category','options'=>$category,'label' => 'Select Service/Product Category<span class="red">*</span>', 'class' => 'form-control','required'=>'required')); ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('expiry_date',array('type'=>'text','placeholder'=>'Expiry Date','label'=>'Expiry Date',"data-date-format"=>"dd/mm/yyyy",'class'=>'form-control cnt date')); ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('quantity',array('type'=>'number','placeholder'=>'Quantity','label'=>'Quantity','class'=>'form-control cnt')); ?>
    </div>
</div>



<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('batch',array('type'=>'text','label'=>'Batch No.','placeholder'=>'Batch No.','class'=>'form-control')); ?>
    </div>
</div>



<div class="form-group">
    <div class="col-sm-12">
        <?php echo $this->Form->input('medicine_form',array('type'=>'select','label'=>'Medicine Form','empty'=>'Please Select','options'=>$medicineForm,'class'=>'form-control')); ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-12">
        <label style="display: block;">&nbsp;</label>
        <?php echo $this->Form->input('is_price_editable', array('div'=>false,'type' => 'checkbox','label' => 'Is Editable Price')); ?>
        <?php
        if($login1['USER_ROLE'] !="LAB" || $login1['USER_ROLE'] !='PHARMACY'){
            ?>
            <?php echo $this->Form->input('is_package', array('div'=>false,'type' => 'checkbox','label' => 'Is Package')); ?>
        <?php } ?>

    </div>
</div>



<div class="form-group">
    <div class="col-sm-12">
        <label>&nbsp;</label><br>
        <button type="submit" id="updateStatusBtn" class="form-control btn btn-info upload" >Add</button>
    </div>
</div>



<?php echo $this->Form->end(); ?>


<script>
    $(".date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});
    $(".date").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


    $(document).off('submit','#MedicalProductGetAddHospitalServiceServiceModalForm');
    $(document).on('submit','#MedicalProductGetAddHospitalServiceServiceModalForm',function(e){
        e.preventDefault();
        var dataToSend = $(this).serialize();
        var $btn = $("#updateStatusBtn");
        $.ajax({
            url: baseurl+'/app_admin/add_hospital_service_service_ajax',
            data:dataToSend,
            type:'POST',
            beforeSend:function(){
                //$btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
            },
            success: function(result){
                var result = JSON.parse(result);
                if(result.status != 1)
                {
                    alert(result.message);
                }
                else
                {
                    $("#addServiceModal").modal("hide");
                }
                $btn.button('reset');

            }
        });
    });
</script>