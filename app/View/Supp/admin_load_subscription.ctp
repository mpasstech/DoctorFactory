<style>
    .top_box label span{
        display: block;
    }
    .top_box label{
        margin: 0;
        padding: 0;
        text-align: center;
        font-size: 16px;
        font-weight: 500 !important;
        width: 100%;
    }
    .top_box{
        display: block;
        float: left;
        width: 100%;
        background: #e3ffdc;
        padding: 14px 0px;
        border: 1px solid #8ee977;
    }
    .top_box label span {
        display: block;
        font-weight: 600;
        font-size: 18px;
    }
    .current_status{
        margin-top: 5px;
        text-decoration: underline;
        text-transform: uppercase;
        width: 100%;
        text-align: center;
        margin-bottom: 22px;
    }
    .status_lbl {
        color: green;
        WIDTH: 50%;
        text-align: center;
        border: 1px solid;
        border-radius: 34px;
        margin: 12px 20% 10px 27%;
        padding: 0px;
        font-size: 14px;
        font-weight: 600;

    }
    .status_lbl .fa{
        float: left;
        font-size: 22px;
        padding: 3px 10px;
        position: relative;
        left: 0;
        border-right: 1px solid;
        border-radius: 16px;

        color: #fff;
    }
    .second_box{
        background:  #ebebeb !important;

    }

    .second_box, .third_box {
        border: 1px solid #dedddd;
        float: left;
        background: #fdfdfd;
        width: 100%;
    }



    .form-control{
        height: 30px;
    }

    .modal-footer th, .modal-footer td{
        text-align: left;
    }
    .modal-footer h2{
        text-align: left;
    }
    .danger{
        color: red !important;;
    }
    .app_top_logo {
        float: left;
        width: 4%;
        /* display: inline-block; */
    }

    .modal-header {

    }

    .modal-title {
        margin: 0;
        line-height: 1.428571429;
        font-size: 23px;
        TEXT-ALIGN: center;
        padding: 0;

    }
    .send_sms{
        float: left;
        width: 4%;
        height: 15px;

        margin-right: 13px;
    }

</style>
<div class="modal-dialog" style="width: 70%; height: auto;">
    <div class="modal-content">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title">
                <img style="float: left;" class="app_top_logo" src="<?php echo $thin_app_data['Thinapp']['logo']; ?>">
                <?php echo $thin_app_data['Thinapp']['name']; ?> Subscription Setting</div>
        </div>
        <div class="modal-body" style="overflow-y: inherit; height: auto;" >
            <div class="container">
                <div class="form-group top_box">

                    <div class="col-sm-2">
                        <label>Publish Date <span><?php echo date('d-m-Y',strtotime($thin_app_data['Thinapp']['start_date']))?></span></label>
                    </div>
                    <div class="col-sm-2">
                        <label>Sub. Start <span><?php echo date('d-m-Y',strtotime($thin_app_data['Thinapp']['subscription_start_date'])); ?></span></label>
                    </div>
                    <div class="col-sm-2">
                        <label class="<?php echo (strtotime(date('Y-m-d')) > strtotime($thin_app_data['Thinapp']['end_date']))?'danger':''; ?>"  >Sub. End <span><?php echo date('d-m-Y',strtotime($thin_app_data['Thinapp']['end_date'])); ?></span></label>

                    </div>
                    <div class="col-sm-3">
                        <label>Total Users  <span><?php echo $this->SupportAdmin->appUserCount($thin_app_data['Thinapp']['id']); ?></span></label>

                    </div>

                    <div class="col-sm-3">
                        <label><span>

                    <?php if($thin_app_data['Thinapp']['is_published'] =="NO"){ ?>
                        <label class="status_lbl" style="color: red;"><i style="color: red;" class="fa fa-android"></i>Not Published</label>
                    <?php }else{ ?>
                        <label class="status_lbl" style="color: green;"><i style="color: green;" class="fa fa-android"></i>Published</label>
                    <?php } ?>

                            </span></label>

                    </div>

                </div>

                <div class="form-group second_box">
                    <H3 class="current_status">Cron Alert Setting</H3>
                    <div class="col-sm-3">
                        <label>Send Alert Type</label>
                        <?php
                        $option['WEEKLY']= "Weekly";
                        $option['DAILY']= "Daily";
                        ?>
                        <?php echo $this->Form->input('alert_status',array('type'=>'select','default'=>'WEEKLY','value'=> $thin_app_data['Thinapp']['subscription_alert_type'] ,'label'=>false,'options'=>$option,'class'=>'form-control alert_status')); ?>
                    </div>
                    <div class="col-sm-3">
                        <label>Subscription Price</label>
                        <input type="text" class="form-control subscription_price" value="<?php echo $thin_app_data['Thinapp']['subscription_price'] ?>">
                    </div>
                    <div class="col-sm-3">
                        <label>Status</label>
                        <?php
                        $option_alert['ACTIVE']= "Active";
                        $option_alert['INACTIVE']= "Inactive";
                        ?>
                        <?php echo $this->Form->input('cron_alert',array('type'=>'select','value'=> $thin_app_data['Thinapp']['subscription_alert_via_cron'] ,'label'=>false,'options'=>$option_alert,'class'=>'form-control cron_alert')); ?>
                    </div>


                    <div class="col-sm-3">
                        <label style="display: block;width: 100%;">&nbsp;</label>
                        <button class="update_alert_btn btn btn-success">Update Setting</button>
                    </div>
                </div>

                <div class="form-group third_box">
                    <H3 class="current_status">Update Subscription Status</H3>

                    <div class="col-sm-2" style="display:none;">
                        <label>Download Count</label>
                        <input type="text" class="form-control download_cnt" value="<?php echo $thin_app_data['Thinapp']['free_subscription_count'] ?>">
                    </div>

                    <div class="col-sm-3">
                        <label>Subscription Start Date</label>
                        <?php echo $this->Form->input('start_date',array('placeholder'=>'DD-MM-YYYY','type'=>'text','label'=>false,'class'=>'form-control datepicker start_date')); ?>
                    </div>

                    <div class="col-sm-3">
                        <label>Payment Date</label>
                        <?php echo $this->Form->input('payment_date',array('placeholder'=>'DD-MM-YYYY','type'=>'text','label'=>false,'class'=>'form-control datepicker payment_date')); ?>
                    </div>


                    <div class="col-sm-3">
                        <label>Subscription End Date</label>
                        <?php echo $this->Form->input('end_date',array('placeholder'=>'DD-MM-YYYY','type'=>'text','label'=>false,'class'=>'form-control datepicker end_date')); ?>
                    </div>

                    <div class="col-sm-3">
                        <label>Subscription Amount</label>
                        <?php echo $this->Form->input('amount',array('type'=>'text','label'=>false,'class'=>'form-control amount')); ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Remark</label>
                        <?php echo $this->Form->input('remark',array('type'=>'text','label'=>false,'class'=>'form-control remark')); ?>
                        <br>
                                       </div>
                    <div class="col-sm-6">
                        <label style="display: block;" >&nbsp;</label>

                        <?php echo $this->Form->input('send_sms',array('type'=>'checkbox','label'=>"Send Sms To App Admin","checked"=>"checked", 'class'=>'form-control send_sms')); ?>
                        <br>
                        <button style="float: right;margin-top: -25px;margin-bottom: 12px;" class="update_sub btn btn-info"> <?php echo ($thin_app_data['Thinapp']['is_published'] =="NO")?"Save & Publish":"Update"; ?></button>
                        <button style="float: right;margin-top: -25px;margin-bottom: 12px;margin-right: 5px;" data-toggle="collapse" data-target="#history_box" class="history btn btn-warning">Show History</button>
                        <button style="float: right;margin-top: -25px;margin-bottom: 12px;margin-right: 5px;" data-toggle="collapse" data-target="#mcq_doctor_list" class=" btn btn-success">MoQ Staff List</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div id="history_box" class="collapse">
                <h2>History</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Remark</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $key=>$list){ ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($list['start_date'])); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($list['end_date'])); ?></td>
                            <td>
                                <?php
                                        if(!empty($list['payment_date']) && $list['payment_date'] !='0000-00-00'){
                                            echo date('d-m-Y',strtotime($list['payment_date']));
                                        }else{
                                            echo "N/A";
                                        }

                                ?>
                            </td>
                            <td><?php echo $list['amount'] ?></td>
                            <td><?php echo $list['remark'] ?></td>
                            <td><?php echo date('d-m-Y H:i',strtotime($list['created'])); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="mcq_doctor_list" class="collapse">
                <h2>McQ Doctor</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Type</th>
                        <th>Url</th>
                          <th>Web App Url</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($staff_list as $key=>$list){ ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $list['name'] ?></td>
                            <td><?php echo $list['mobile'] ?></td>
                            <td><?php echo $list['staff_type'] ?></td>
                            <td>
                                     <a target="_blank" href="<?php echo 'https://www.mengage.in/doctor/homes/mq_form/'.base64_encode($list['thinapp_id']).'/'.base64_encode($list['id']); ?>">MoQ Link</a>
                           
                            </td>
                            <td>

                                <?php
                                        $long_url =  'https://www.mengage.in/doctor/doctor/index/'.$list['id'].'/?t='.base64_encode($list['id']).'&wa=y&back=no';
                                      //  $url = $this->AppAdmin->short_url($long_url,$list['thinapp_id']);
                                ?>
                                <a target="_blank" href="<?php echo $long_url; ?>">WebAPP Link</a>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    $(function () {
        $(document).off('click',".update_sub");

        $(document).on('click',".update_sub",function () {
           var start_date = $('.start_date').val();
           var end_date = $('.end_date').val();
           var payment_date = $('.payment_date').val();
           var download = $('.download_cnt').val();
           var remark = $('.remark').val();
           var amount = $('.amount').val();
           var send_sms = $('.send_sms:checked').val();
           var tttid = '<?php echo ($thin_app_data['Thinapp']['id']); ?>';
           var published = '<?php echo ($thin_app_data['Thinapp']['is_published']); ?>';
           var tid = '<?php echo base64_encode($thin_app_data['Thinapp']['id']); ?>';



            if(start_date ==""){
                $.alert("Please enter subscription start date");
              //  $('.start_date').focus();
            }else if(end_date ==""){
                $.alert("Please enter subscription end date");
               // $('.end_date').focus();
            }else if(download == "" || !(/^[0-9]{1,3}$/.test(+download))){
                $.alert("Please enter valid download count");
            }else{
                var jc = $.confirm({
                    title: 'Update Subscription Status',
                    content: 'Are you sure you want to update this app. if YES then subscription data will be effect by this action?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $.ajax({
                                    url:baseurl+"admin/supp/update_publish_status",
                                    type:'POST',
                                    data:{
                                        start_date:start_date,
                                        end_date:end_date,
                                        payment_date:payment_date,
                                        remark:remark,
                                        tid:tid,
                                        download:download,
                                        published:published,
                                        amount:amount,
                                        send_sms:send_sms
                                    },
                                    beforeSend:function(){
                                        jc.buttons.ok.setText("Wait..");
                                    },
                                    success:function(res){
                                        var response = JSON.parse(res);
                                        jc.buttons.ok.setText("Yes");
                                        if(response.status==1){
                                            jc.close();
                                            $("#setting_modal").html("").modal('hide');
                                            $(".setting_btn_"+tttid).removeClass('btn-danger').addClass('btn-success');
                                            $(".setting_div_"+tttid).show();
                                            $(".setting_end_date_"+tttid).html(response.data.end_date);
                                        }else{
                                            $.alert(response.message);
                                        }
                                    },
                                    error:function () {
                                        jc.buttons.ok.setText("Yes");
                                        $.alert(response.message);
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function(){
                            console.log('the user clicked cancel');
                        }
                    }
                });




            }
        });

        $(document).off('click',".update_alert_btn");
        $(document).on('click',".update_alert_btn",function () {
            var subscription_price = $('.subscription_price').val();
            var cron_alert = $('.cron_alert').val();
            var alert_status = $('.alert_status').val();
            var tid = '<?php echo base64_encode($thin_app_data['Thinapp']['id']); ?>';
            var jc = $.confirm({
                title: 'SMS Alert Setting',
                content: 'Are you sure you want to update this setting.',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            $.ajax({
                                url:baseurl+"admin/supp/update_sub_alert_status",
                                type:'POST',
                                data:{
                                    alert_status:alert_status,
                                    subscription_price:subscription_price,
                                    tid:tid,
                                    cron_alert:cron_alert
                                },
                                beforeSend:function(){
                                    jc.buttons.ok.setText("Wait..");
                                },
                                success:function(res){
                                    var response = JSON.parse(res);
                                    jc.buttons.ok.setText("Yes");
                                    if(response.status==1){
                                        jc.close();

                                    }else{
                                        $.alert(response.message);
                                    }
                                },
                                error:function () {
                                    jc.buttons.ok.setText("Yes");
                                    $.alert(response.message);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });


        });


        $('.datepicker').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
    })
</script>
