<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">New/Old Patient List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php echo $this->element('app_admin_inner_tab_patient'); ?>

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'new_old_search_patient'))); ?>
                            <div class="form-group">
                                <div class="col-sm-2">

                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">

                                    <?php
                                    $tagVal = array();
                                    if(!empty($this->request->data['Search']['tags'])){
                                        $searchTag = $this->request->data['Search']['tags'];
                                        foreach ($searchTag AS $tagID)
                                        {
                                            foreach ($tag AS $tagValue)
                                            {
                                                if($tagValue['id'] == $tagID)
                                                {
                                                    $tagVal[] = $tagValue['id'];
                                                }
                                            }
                                        }
                                    }  ?>

                                    <?php echo $this->Form->input('tags', array('type' => 'text','value'=>'', 'placeholder' => '', 'label' => 'Search by tags', 'class' => 'magicsuggest form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('patient_type', array('type' => 'select', 'options' => array("NEW"=>"New","OLD"=>"Old",), 'empty' => "Please Select", 'label' => 'Search by New/Old', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <?php echo $this->Form->input('from_date', array('type' => 'text', 'placeholder' => 'From Date', 'label' => 'From Date', 'class' => 'date form-control')); ?>
                                </div>
                                <div class="col-sm-1">
                                    <?php echo $this->Form->input('end_date', array('type' => 'text', 'placeholder' => 'End Date', 'label' => 'To Date', 'class' => 'date form-control')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-1">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'new_old_patient')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php if(!empty($AppointmentCustomer)){ ?>
                                <div class="table-responsive">

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Patient Name</th>
                                            <th>MEngae UHID</th>
                                            <th>UHID</th>
                                            <th>Mobile</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Address</th>
                                            <th>Medical History</th>
                                            <th>Type</th>
                                            <th>Total Appointment</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($AppointmentCustomer as $key => $list){

                                            ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['first_name']; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['uhid']; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['third_party_uhid']; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['mobile']; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['gender']; ?></td>
                                                <td><?php echo ($list['AppointmentCustomer']['dob'] != '0000-00-00' && !empty($list['AppointmentCustomer']['dob']))?date('d/m/Y',strtotime($list['AppointmentCustomer']['dob'])):""; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['address']; ?></td>
                                                <td><?php echo $list['AppointmentCustomer']['medical_history']; ?></td>
                                                <td><?php echo ($list[0]['total_appointment'] > 1)?"Old":"New"; ?></td>
                                                <td><?php echo $list[0]['total_appointment']; ?></td>
                                                <td style="display: flex;">
                                                    <button type="button" class="btn btn-primary btn-xs edit_patient_btn"  data-pt = 'CUSTOMER' data-pi="<?php echo base64_encode($list['AppointmentCustomer']['id']); ?>" >Edit</button>
                                                    <button type="button" data-pt="CUSTOMER" data-pi="<?php echo base64_encode($list['AppointmentCustomer']['id']); ?>" class="btn btn-xs btn-warning delete_patient"><i class="fa fa-trash"></i> Delete</button>
                                                    <a href="<?php echo Router::url('/app_admin/get_patient_history/'.base64_encode($list['AppointmentCustomer']['thinapp_id']).'/'.base64_encode($list['AppointmentCustomer']['uhid']),true); ?>" target="_blank" class="btn btn-primary btn-xs receipt_btn history_btn" ><i class="fa fa-list"></i> History </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php echo $this->element('paginator'); ?>
                                </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No patient..!</h2>
                                </div>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>


                    </div>






                </div>
                <!-- box 1 -->


            </div>

    </div>
</div>

<div class="modal fade" id="patient_history_modal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">History</h4>
            </div>
            <div class="modal-body" id="modal_body">
                <iframe id="ifram" src="https://www.w3schools.com"></iframe>
            </div>
        </div>
    </div>

</div>
<style>
    #ifram {

        width: 100%;
        border: none;
        height: 90%;

    }
    .form-control {

        height: 35px;

    }


    #patient_history_modal > .modal-dialog.modal-sm {

        height: 90%;

    }
    .modal-content {

        height: 100%;

    }
    #modal_body {

        height: 100%;

    }
    .modal-header {
        background: #03a9f5;
        color: #FFFFFF;
    }
</style>

<script>
    $(document).ready(function(){

        $(document).on("click",".delete_patient",function(){
            if(confirm("Do you want to delete this patient?"))
            {
                var patientType = $(this).attr("data-pt");
                var patientID = $(this).attr("data-pi");




                var $btn = $(this);
                var obj = $(this);
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/inactive_patient_status",
                    data: {patientType: patientType,patientID:patientID},
                    beforeSend: function () {
                        $btn.button('loading').html('Wait..')
                    },
                    success: function (data) {
                        $btn.button('reset');

                        data = JSON.parse(data)

                        if(data.status == '0')
                        {
                            alert("Sorry something went wrong on server.");
                        }
                        else
                        {
                            $(obj).closest('tr').remove();
                        }

                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        });

        $(".date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});
        $(".dob").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

        $(".channel_tap a").removeClass('active');
        $("#n_app_subscriber_list").addClass('active');


        $(document).on('click','.history_btn',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $("#ifram").attr("src",url);
            $("#patient_history_modal").modal('show');
            /*var thisButton = $(this);
             $.ajax({
             url: url,
             type:'GET',
             beforeSend:function(){
             $(thisButton).button('loading').html('Loading...');
             },
             success: function(result){
             $(thisButton).button('reset');
             $("#modal_body").html(result);

             }
             });*/


        });
        $(function() {
            var ms = $('.magicsuggest').magicSuggest({
                allowFreeEntries:false,
                allowDuplicates:false,
                data:<?php echo json_encode($tag,true); ?>,
                maxDropHeight: 345
            });
            ms.setValue(<?php echo json_encode($tagVal,true); ?>);
        });

    });
</script>





