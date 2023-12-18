<?php

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>SMS Transition</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>


<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">

                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="progress-bar channel_tap">
                            <a href="<?php echo Router::url('/admin/supp/user_sms_list'); ?>" ><i class="fa fa-list"></i> User SMS List</a>
                            <a href="<?php echo Router::url('/admin/supp/sms_transition'); ?>"  class="active" ><i class="fa fa-list"></i> SMS Transition</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_sms_transition'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Insert user name', 'label' => 'Search by user name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('appName', array('type' => 'text', 'placeholder' => 'Insert app name', 'label' => 'Search by app name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <p>&nbsp;</p>
                                    <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <a class="btn btn-info" href="javascript:void(0);", id="addSMS">Add Sms</a>
                                
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                 <a class="btn btn-info" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'sms_transition')) ?>">Reset</a>
                               
                                </div>

                               


                                
                            </div>

                            <?php echo $this->Form->end(); ?>


                              




                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                            <div class="table-responsive">
                                <?php if(!empty($data)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>App Name</th>
                                        <th>Price</th>
                                        <th width="10">Total SMS/Cloud</th>
                                        <th>Recharge For</th>
                                        <th>Recharge By</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $login = $this->Session->read('Auth.User');
									$num = 1;
									foreach ($data as $key => $value){
                                       
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['User']['username']; ?></td>
                                            <td><?php echo $value['Thinapp']['name']; ?></td>
                                            <td>Rs.&nbsp;<?php echo $value['AppSmsRecharge']['total_price']; ?></td>
                                            <td><?php
                                                if($value['AppSmsRecharge']['recharge_for'] =="SMS"){
                                                    echo $value['AppSmsRecharge']['total_sms'];
                                                }else{
                                                    echo $value['AppSmsRecharge']['total_cloud_storage'];
                                                }

                                                ?></td>
                                            <td><?php echo $value['AppSmsRecharge']['recharge_for']; ?></td>
                                            <td><?php echo ucfirst(strtolower(str_replace("_"," ",$value['AppSmsRecharge']['recharge_by']))); ?></td>
                                            <td><?php echo ucfirst(strtolower(str_replace("_"," ",$value['AppSmsRecharge']['transaction_status']))); ?></td>

                                            <td><?php echo date("d-m-Y",strtotime($value['AppSmsRecharge']['created'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>

                            </div>
                                </div>

                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No SMS..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>
                        </div>



                    </div>






                </div>



            </div>



        </div>
    </div>
</section>





<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('AppSmsRecharge',array( 'class'=>'form','id'=>'form','novalidate'=>'novalidate')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SMS Recharge</h4>
            </div>
            
            
            <div class="modal-body">

                <div class="form-group">
                    <?php echo $this->Form->input('thinapp_id', array('type'=>'select','label'=>"Select App",'options'=>$thinAppList,'required'=>true,'empty'=>'Select Thinapp','class'=>'form-control select_thinapp')); ?>
                </div>

                <div class="form-group" >
                    <?php echo $this->Form->input('recharge_for', array('type'=>'select','label'=>"Recharge For",'options'=>array('SMS'=>'SMS','CLOUD'=>'Cloud Storage'),'required'=>true,'empty'=>'Select Recharge For','class'=>'form-control recharge_for')); ?>
                </div>
                <div class="form-group">
                    <label>Enter total amount</label>
                    <?php echo $this->Form->input('total_price', array('type'=>'number','label'=>false,'required'=>true,'placeholder'=>'Insert total price','class'=>'form-control')); ?>
                </div>

                <div class="form-group tot_sms_div" >
                    <label>Enter total SMS</label>
                    <?php echo $this->Form->input('total_sms', array('type'=>'number','label'=>false,'placeholder'=>'','class'=>'form-control')); ?>
                </div>

                <div class="form-group cloud_div">
                    <label>Select Cloud Storage</label>
                    <?php echo $this->Form->input('total_storage', array('type'=>'select','label'=>false,'options'=>$this->SupportAdmin->getStorageEnum(),'required'=>true,'empty'=>'Select Recharge For','class'=>'form-control')); ?>

                </div>

            </div>


            <div class="modal-footer">
                <?php echo $this->Form->button('Recharge',array('type'=>'submit','class'=>'Btn-typ3','id'=>'RechargeBtn')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>


<style>
    .select2-container .select2-selection--single{
        height:34px !important;
        width: 100% !important;
    }
    .select2{
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style>


<script>
    $(document).ready(function(){


        $('.select_thinapp').select2();

        $(document).on('click','#addSMS',function(e){
            var rowID = $(this).attr('row-id');
            $("#rowID").val(rowID);
            $("#reply").val('');
            $(".show_err").html('');
            $("#myModal").modal('show');
        });

        $(".tot_sms_div, .cloud_div").hide();
        $(document).on('change','.recharge_for',function(e){
            var val = $(this).val();
            $(".tot_sms_div, .cloud_div").hide();
            if(val =="SMS"){
                $(".tot_sms_div").show();
            }else if(val =="CLOUD"){
                $(".cloud_div").show();
            }
        });
            $(document).on('submit','#form',function(e){
                e.preventDefault();
                e.stopPropagation();
                var sendData = $( this ).serialize();
                var thisButton = $("#RechargeBtn");
                $.ajax({
                    url: baseurl+'/admin/supp/add_sms',
                    data:sendData,
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var data = JSON.parse(result);
                        if(data.status == 1)
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(data.message);
                        }
                    }
                });
            });


    });
</script>