<?php  $login = $this->Session->read('Auth.User'); ?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Leads List</h2> </div>
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
                         <div class="Social-login-box payment_bx">

                             <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_lead_list'),'admin'=>true)); ?>


                             <div class="form-group">
                                 <div class="col-sm-4">

                                     <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Insert org name', 'label' => 'Search by org name', 'class' => 'form-control')); ?>
                                 </div>
                                 <div class="col-sm-4">

                                     <?php echo $this->Form->input('email', array('type' => 'text', 'placeholder' => 'Insert email', 'label' => 'Search by email', 'class' => 'form-control')); ?>
                                 </div>
                                 <div class="col-sm-2">
                                     <?php echo $this->Form->label('&nbsp;'); ?>
                                     <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                 </div>
                                 <div class="col-sm-2">
                                     <?php echo $this->Form->label('&nbsp;'); ?>
                                     <div class="submit">
                                         <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'support_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                     </div>
                                 </div>

                             </div>

                             <?php echo $this->Form->end(); ?>




                             <div class="form-group row">
                                 <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-responsive">

                                <thead>
                                <tr >
                                    <th>S.no</th>
                                    <th>Organization</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Payment Status</th>
                                    <th>Organization Type</th>
                                    <th>Date</th>
                                    <th>Manage By</th>
                                    <th>Action</th>

                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                if(isset($Leads) && !empty($Leads))
                                {
                                    foreach($Leads as $key => $user)
                                    {
                                       /* if($user['Leads']['user_id']==2)
                                            continue; */
                                      //  pr($Leads); die;
                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><?php  echo $user['Leads']['org_name']; ?></td>
                                            <td><?php echo $user['Leads']['mobile']; ?></td>
                                            <td><?php echo $user['Leads']['cust_email']; ?></td>
                                            <td><?php echo ($user['Leads']['app_payment'] == 1)?'Paid':'Unpaid'; ?></td>
                                            <td><?php echo $this->SupportAdmin->getOrgName($user['Leads']['org_type']); ?></td>
                                            <td><?php echo date('Y-m-d H:i',strtotime($user['Leads']['created'])); ?></td>
                                            <td>
                                                <?php
                                                if($user['Leads']['status'] == 'INPROCESS'){ ?>

                                                    <?php if($user['Leads']['support_admin_id'] == $login['id']){ ?>
                                                        <button title="Close this lead" type="button" id="closeThis" lead-id="<?php echo $user['Leads']['customer_id']; ?>" class="btn btn-primary btn-xs"><?php echo $user['Support']['username'];?> &nbsp;<i class="fa fa-power-off"></i></button>
                                                    <?php }else{ ?>

                                                        <lable title="This lead managed by <?php echo $user['Support']['username']; ?>"class="label label-info"> <?php echo $user['Support']['username']; ?> &nbsp;<i class="fa fa-check"></i></lable>


                                                     <?php } ?>

                                                <?php }
                                                else if($user['Leads']['status'] == 'DONE')
                                                { ?>

                                                    <lable title="This lead closed by <?php echo $user['Support']['username']; ?>"class="label label-success"> <?php echo $user['Support']['username']; ?> &nbsp;<i class="fa fa-check"></i></lable>

                                                <?php }
                                                else
                                                { ?>
                                                    <button type="button" id="acceptThis" lead-id="<?php echo $user['Leads']['customer_id']; ?>" class="btn btn-warning btn-xs">Accept &nbsp;<i class="fa fa-check"></i></button>
                                                <?php } ?>
                                            </td>
                                            <td class="td_links">
                                                <div style="display:flex;">
                                                <?php
                                                if($user['Leads']['support_admin_id'] == $login['id']) { ?>

                                                    <a title="Reply to customer" row-id ="<?php echo $user['Leads']['customer_id'];?>" class="action_icon btn btn-primary" href="<?php echo Router::url('/admin/supp/message/',true).base64_encode($user['Leads']['customer_id']); ?>"><i class="fa fa-reply"></i></a>
                                                    &nbsp;
                                                    <button type="button" title="View lead" row-id ="<?php echo $user['Leads']['customer_id'];?>" class="action_icon btn btn-primary view_lead" href="javascript:void(0);"><i class="fa fa-eye"></i></button>


                                                    <?php } ?>

                                                    <?php
                                                    if($user['Leads']['support_admin_id'] == $login['id'] && $user['Leads']['status'] == 'INPROCESS' && $user['Leads']['app_payment'] == 1 && $user['Leads']['app_id'] == 0)
                                                    { ?>
                                                        &nbsp;
                                                        <a  title="Add thin app" id="addThinApp" lead-id="<?php echo $user['Leads']['user_id']; ?>" row-id="<?php echo $user['Leads']['customer_id']; ?>" class="action_icon btn btn-primary add_thin_app" href="javascript:void(0);"><i class="fa fa-android"></i></a>

                                                    <?php } ?>


                                                    <?php
                                                    if($user['Leads']['app_payment'] != 1 && $user['Leads']['status'] == 'INPROCESS')
                                                    { ?>

                                                        &nbsp;
                                                        <a  title="Pay with customer cheque" id="addPayment" row-id="<?php echo $user['Leads']['customer_id']; ?>" class="action_icon btn btn-primary add_thin_app" href="javascript:void(0);"><i class="fa fa-money"></i></a>

                                                    <?php } ?>


                                                </div>
                                            </td>




                                        </tr>
                                    <?php }
                                }else{ ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>No Leads Found...</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                            <?php echo $this->element('paginator'); ?>
                        </div>
                    </div>
                             </div>

                        <div class="clear"></div>
                    </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</section>






<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Lead</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewLeadTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="addThinAppMobal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Thin App</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="addThinAppForm" method="POST">

                        <input type="hidden" name="user_id" id="userIDholder">
                        <input type="hidden" name="customer_id" id="customerIDholder">

                      <!--<div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="app_id" placeholder="Please enter app ID" class="form-control cnt" required>

                            </div>
                        </div>-->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="name" placeholder="Please enter app Name" class="form-control cnt">
                            </div>
                        </div>
                        <!--div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="firebase_server_key" placeholder="Please enter firebase server key" class="form-control cnt" required>

                            </div>
                        </div-->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" placeholder="Please enter email" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="phone" id="thin_phone" placeholder="Please enter phone number" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="address" placeholder="Please enter address" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="start_date" value="<?php echo date("Y-m-d");?>" id="datePick" placeholder="Please select start date" readonly class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                            <button type="submit" name="submitForm" class="form-control" id="thisBtn">Add Thin App</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>




<div class="modal fade" id="addPaymentMobal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="addPaymentForm" method="POST">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="cheque_number" placeholder="Please enter cheque number" class="form-control cnt" required>
                                <input type="hidden" name="customer_id" id="customerIDPayment">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="amount" placeholder="Please enter amount" class="form-control cnt">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select name="membership_id" class="form-control cnt" required>
                                <option value="">Please select membership</option>
                                    <?php
                                    foreach ($membership as $key => $member)
                                    { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $member; ?></option>
                                   <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" name="submitForm" class="form-control" id="thisBtnPayment">Add Payment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>








<script>
    $(document).ready(function(){

        $("#thin_phone").intlTelInput({
            allowExtensions: true,
            autoFormat: false,
            autoHideDialCode: false,
            autoPlaceholder:  false,
            initialCountry: 'IN',
            ipinfoToken: "yolo",
            nationalMode: true,
            numberType: "MOBILE",
            //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            //preferredCountries: ['cn', 'jp'],
            preventInvalidNumbers: true,
            utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
        });


        $("#thin_phone").on("countrychange", function(e, countryData) {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            //console.log($("#phone").intlTelInput("isValidNumber"));
            // do something with countryData
            $("#thin_phone").trigger("keyup");

        });

        $(document).on("keyup blur","#thin_phone", function() {
                if($(this).intlTelInput("isValidNumber")){
                    $(this).css('border-color',"#ccc");
                }else{
                    $(this).css('border-color',"red");
                }
        });





        $(document).on('click','#acceptThis',function(e) {
            var conf = confirm("Do you want to accept this task?");
            if(conf == false){ return false; }
            var rowID = $(this).attr('lead-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'admin/supp/accept_lead',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).parent().html((data.message)).next().html((data.icon));
                        $(thisButton).remove();
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            });
        });


        $(document).on('click','#closeThis',function(e) {
            var conf = confirm("Do you want to close this task?");
            if(conf == false){ return false; }
            var rowID = $(this).attr('lead-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'admin/supp/close_lead',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).parent().html(data.message).next().next().html('');
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            });
        });


        $(document).on('click','.view_lead',function(e){

            var rowID = $(this).attr('row-id');
            var thisButton = $(this);

            $.ajax({
                url: baseurl+'/admin/supp/view_lead',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);

                    if(result.status == 1)
                    {
                        //console.log(result.html);
                        $("#viewLeadTable").html(result.html);
                        $("#myModalView").modal('show');
                    }
                },
                error:function () {
                    $(thisButton).button('reset');
                }
            });


        });


        $(document).on('click','#addThinApp',function(e){
            var leadID = $(this).attr('lead-id');
            $("#userIDholder").val(leadID);
            var rowID = $(this).attr('row-id');
            $("#customerIDholder").val(rowID);
            $("#addThinAppMobal").modal('show');
        });

        $(document).on('submit','#addThinAppForm',function(e){
            e.stopPropagation();
            e.preventDefault();


            if($("#thin_phone").intlTelInput("isValidNumber")){
                var mob = $("#thin_phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $("#thin_phone").val(mob);
                var dataToPost = $(this).serialize();
                var userID = $("#userIDholder").val();
                var thisButton = $("#thisBtn");
                $.ajax({
                    url: baseurl+'/admin/supp/add_thinapp',
                    data:dataToPost,
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);

                        if(result.status == 1)
                        {
                            $("#addThinAppMobal").modal('hide');
                            //   userID
                            $("#addThinApp[lead-id='"+userID+"']").remove();
                            $("#addThinAppForm").trigger('reset');
                        }
                        else
                        {
                            alert(result.message);
                        }
                    }
                });

            }else{
                return false;
            }



        });


        $(document).on('click','#addPayment',function(e){
            var rowID = $(this).attr('row-id');
            $("#customerIDPayment").val(rowID);
            $("#addPaymentMobal").modal('show');
        });



        $(document).on('submit','#addPaymentForm',function(e){
            e.stopPropagation();
            e.preventDefault();
            var dataToPost = $(this).serialize();
            var thisButton = $("#thisBtnPayment");
            $.ajax({
                url: baseurl+'/admin/supp/add_payment',
                data:dataToPost,
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);

                    if(result.status == 1)
                    {
                        $("#addPaymentMobal").modal('hide');
                        $("#addThinAppForm").trigger('reset');
                        location.reload();
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });
        });



        $('#datePick').datepicker({startDate:new Date(),format: 'yyyy-mm-dd'});


    });
</script>



