<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">FollowUp Patient List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php echo $this->element('app_admin_inner_tab_patient'); ?>

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_follow_patient'))); ?>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('uhid', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by UHID', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('follow_date', array('autocomplete'=>'off','type' => 'text', 'placeholder' => '', 'label' => 'FollowUp Date', 'class' => 'form-control','id'=>'follow_date')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <?php echo $this->Form->input('message_status', array('type' => 'select','empty'=>'All','options'=>array('PENDING'=>'Pending','SENT'=>'Sent'),'label' => 'Message Status', 'class' => 'form-control')); ?>

                                </div>


                                <div class="col-sm-2">
                                    <div class="input text">
                                        <label style="display: block;">&nbsp;</label>
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'follow_patient')) ?>"><button type="button" class="btn btn-warning" >Reset</button></a>

                                    </div>

                                </div>


                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php if(!empty($data)){ ?>
                                <div class="table-responsive">

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Patient Name</th>
                                            <th>UHID</th>
                                            <th>Mobile</th>
                                            <th>Gender</th>
                                            <th>Follow Up Date</th>
                                            <th>Message Staus</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data as $key => $list){
                                               $follow = $list['FollowUpReminder'];
                                                $name = !empty($list['AppointmentCustomer'])?$list['AppointmentCustomer']['first_name']:$list['Children']['child_name'];
                                                $list = !empty($list['AppointmentCustomer'])?$list['AppointmentCustomer']:$list['Children'];
                                            ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $name; ?></td>
                                                <td><?php echo $list['uhid']; ?></td>
                                                <td><?php echo $list['mobile']; ?></td>
                                                <td><?php echo $list['gender']; ?></td>
                                                <td><?php echo !empty($follow['reminder_date'])?date('d/m/Y',strtotime($follow['reminder_date'])):""; ?></td>
                                                <td><?php echo $follow['reminder_status']; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs send_message"  data-ptreminder_date = 'CUSTOMER' data-pi="<?php echo base64_encode($follow['id']); ?>" >Send Message</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php echo $this->element('paginator'); ?>
                                </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No patient for follow up</h2>
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

<script>
    $(document).ready(function(){

        $("#follow_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $(".channel_tap a").removeClass('active');
        $("#v_followup").addClass('active');

        $(document).on("click",".send_message",function(){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-pi');



            var jc = $.confirm({
                title: "Send Followup Reminder",
                content: 'Are you sure you want to send followup reminder?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){

                            $.ajax({
                                type: 'POST',
                                url: baseurl + "app_admin/send_followup_reminder",
                                data: {id: id},
                                beforeSend: function () {
                                    $btn.button('loading').html('Wait..')
                                },
                                success: function (data) {
                                    $btn.button('reset');
                                },
                                error: function (data) {
                                    $btn.button('reset');
                                    alert("Sorry something went wrong on server.");
                                }
                            });

                            //return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });

        });



    });
</script>





