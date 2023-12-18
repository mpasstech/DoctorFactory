<?php
echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js','remote-list.min.js'));
if(isset($this->request->data['MedicalProductQuantity']['expiry_date']) && !empty($this->request->data['MedicalProductQuantity']['expiry_date']))
{
    $this->request->data['MedicalProductQuantity']['expiry_date'] = date('d/m/Y',strtotime($this->request->data['MedicalProductQuantity']['expiry_date']));
}
$login = $this->Session->read('Auth.User.User');
$category = $this->AppAdmin->getHospitalServiceCategoryArray($login['thinapp_id']);

?>



<div class="Home-section-2">

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <?php echo $this->element('inventory_pharma_billing_setting_inner_header'); ?>
    </div>

    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
        <div class="container">

            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title"> Add Detail (<?php echo $medicalProData['MedicalProduct']['name']; ?>)</h3>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                         <?php echo $this->element('hospital_service_inner_tab'); ?>



                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>

                                <?php echo $this->Form->create('MedicalProductQuantity',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
                                <div class="form-group">

                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('purchase_price',array('type'=>'text','placeholder'=>'Purchase Price','label'=>'Purchase Price','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('mrp',array('type'=>'text','placeholder'=>'MRP','label'=>'MRP','class'=>'form-control cnt','required'=>'required')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('quantity',array('type'=>'text','placeholder'=>'Quantity','label'=>'Quantity','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('expiry_date',array('type'=>'text',"data-date-format"=>"dd/mm/yyyy",'placeholder'=>'Expiry Date','label'=>'Expiry Date','id'=>'dateC','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('batch',array('type'=>'text','placeholder'=>'Batch','label'=>'Batch','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 col-sm-offset-5">
                                            <?php echo $this->Form->label('&nbsp;'); ?>
                                            <?php echo $this->Form->submit('Save',array('class'=>'btn btn-info col-md-12')); ?>

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




<script>
    $(document).ready(function(){

        $("#dateC").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});
        $("#dateC").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


        $(".channel_tap a").removeClass('active');
        $("#add_cat_tab").addClass('active');


    });
</script>



