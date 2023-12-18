<style>
.message {
    width: 100% !important;
}
</style>
<?php
$reqData = $this->request->query;

?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Support User</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('support_admin_leftsidebar'); ?>



                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <div class="Social-login-box dashboard_box" style="padding: 15px; 20px;">


                            <div class="row">
                                <div class="form-group" >

                                    <div class="col-md-12">
                                        <a style="float: right;" class="btn btn-warning add_new_user" href="javascript:void(0);">Add New Support User</a>
                                    </div>

                                </div>


                                <div class="form-group add_user_div" style="display: none;">

                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Enter username', 'class' => 'form-control name')); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Enter mobile', 'class' => 'form-control mobile')); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('password', array('type'=>'text','placeholder' => '', 'label' => 'Enter Password', 'class' => 'form-control password')); ?>
                                    </div>
                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->button('Save',array('div'=>false,'class'=>'btn btn-info add_user')); ?>

                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-12">

                                    <div class="table-responsive">
                                        <table class="table table-responsive ">

                                            <thead>
                                            <tr >
                                                <th>#</th>
                                                <th>Username</th>
                                                <th>Mobile</th>
                                                <th>Password</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php
                                            if(isset($data) && !empty($data))
                                            {
                                                foreach($data as $key => $val)
                                                {

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>

                                                        <td><?php echo $val['User']['username']; ?></td>
                                                        <td><?php echo $val['User']['mobile']; ?></td>
                                                        <td><?php echo $val['User']['password']; ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-xs admit_btn"  data-status = "<?php echo $val['User']['status']; ?>" data-id="<?php echo base64_encode($val['User']['id']); ?>"><?php echo ($val['User']['status']=='Y')?'Active':'Inactive'; ?></button>

                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-xs edit_btn"  data-name = "<?php echo $val['User']['username']; ?>" data-mobile="<?php echo ($val['User']['mobile']); ?>" data-pass = "<?php echo $val['User']['password']; ?>"><i class="fa fa-edit"></i> Edit</button>

                                                        </td>

                                                    </tr>
                                                <?php }
                                            }else{ ?>
                                                <tr>
                                                    <td colspan="5"> No user found.</td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>


                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="clear"></div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        $('.from_date, .to_date').datepicker({autoclose:true,format:'dd-mm-yyyy'});

        var visible = edit = false;
        $(document).on('click','.add_new_user',function(e) {
            if(visible === false){
                visible =true;
                $(".add_user_div").hide().slideDown(10);
            }else{
                visible = false;
                $(".add_user_div").slideUp(10).show();
            }
            edit = false;
            $('.add_user').text('Save');
            $('.name, .mobile, .password').val('');

        });


            $(document).on('click','.admit_btn',function(e){
                var pat_id = $(this).attr('data-id');
                var status = $(this).attr('data-status');

                var display ='';
                if(status == "Y"){
                    status = 'N';
                    display ='Inactive';
                }else{
                    status = 'Y';
                    display = 'Active';
                }

                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_support_status',
                    data:{pat_id:pat_id,status:status},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('Wait...');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            $(thisButton).text(display);
                            thisButton.attr('data-status',status);


                        }
                        else
                        {
                            thisButton.removeClass('btn-success').addClass('btn-danger',status);
                            alert('Sorry, Could not change status!');
                        }
                    }
                });
            });

        $(document).on('click','.add_user',function(e){
            var name = $('.name').val();
            var mobile = $('.mobile').val();
            var password = $('.password').val();

            if(name!='' && mobile !='' && password !=''){
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/add_support_admin',
                    data:{edit:edit,name:name,mobile:mobile,password:password},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('Wait...');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                           window.location.reload();

                        }
                        else
                        {

                            $.alert(result.message);
                        }
                    },
                    error:function () {
                        $(thisButton).button('reset');
                        $.alert('Something went wrong on server.')
                    }
                });
            }else{
                $.alert('Please enter all field');
            }


        });

        $(document).on('click','.edit_btn',function(e){
            $('.name').val($(this).attr('data-name'));
            $('.mobile').val($(this).attr('data-mobile'));
            $('.password').val($(this).attr('data-pass'));

            visible = edit = true;
            $('.add_user').text('Edit');
            $(".add_user_div").hide().slideDown(10);


        });


    });

</script>
