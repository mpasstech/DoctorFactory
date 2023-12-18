<?php
$login = $this->Session->read('Auth.User.User');
$tax_rate = $this->AppAdmin->getHospitalTaxRateArray($login['thinapp_id']);
$cat_type= $this->AppAdmin->getHospitalCategoryType();
?>
<?php echo $this->Form->create('HospitalServiceCategory',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Enter Category Name','label'=>'Enter Category Name','class'=>'form-control cnt','required'=>'required')); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?php echo $this->Form->input('hospital_tax_rate_id', array('type' => 'select','options'=>$tax_rate,"empty"=>"Select Tax Rate",'label' => 'Select Tax Rate', 'class' => 'form-control','required'=>'required')); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?php echo $this->Form->input('hospital_service_category_type_id', array('type' => 'select','options'=>$cat_type,"empty"=>"Category For",'label' => 'Category For', 'class' => 'form-control','required'=>'required')); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?php echo $this->Form->label('&nbsp;'); ?>
            <button type="submit"  name="submitForm" id="updateStatusBtn" class="form-control btn btn-primary" >Add</button>

        </div>
    </div>


<?php echo $this->Form->end(); ?>



<script>
    $(document).off('submit','#HospitalServiceCategoryGetAddCategoryModalForm');
    $(document).on('submit','#HospitalServiceCategoryGetAddCategoryModalForm',function(e){
        e.preventDefault();
        var dataToSend = $(this).serialize();
        var $btn = $("#updateStatusBtn");
        $.ajax({
            url: baseurl+'/app_admin/add_hospital_service_category_ajax',
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
                    $("#addCategoryModal").modal("hide");
                }
                $btn.button('reset');

            }
        });
    });
</script>