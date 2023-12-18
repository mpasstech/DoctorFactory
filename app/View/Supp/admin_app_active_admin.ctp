<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Login Admins</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>
<?php

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

?>


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

                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'supp','action' => 'search_app_active_admin'),'admin'=>true)); ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-7">
                                            <?php $type_array = $this->SupportAdmin->getAllThinappDropdwon(); ?>
                                            <?php echo $this->Form->input('thinapp_id',array('type'=>'select','label'=>"Select App",'empty'=>'Select App','options'=>$type_array,'class'=>'form-control thinapp_id')); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <label style="display: block;">&nbsp;</label>
                                            <button class="btn btn-info" type="submit" > Search</button>
                                            <?php $thin_app_id = $this->request->query('t'); if(!empty($thin_app_id)){ ?>
                                                <a href="<?php echo Router::url('/admin/supp/syn_active_admin/',true).$thin_app_id; ?>" class="btn btn-warning refresh_btn" > Refresh List</a>
                                            <?php } ?>

                                        </div>


                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>

                            <?php if(!empty($active_admin)){ ?>
                            <div class="form-group char_group">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <?php if(!empty($active_admin)){ ?>
                                            <div class="table-responsive">
                                                <table class="table table-responsive ">

                                                    <thead>
                                                    <tr >
                                                        <th>#</th>
                                                        <th>Brand Name</th>
                                                        <th>Modal</th>
                                                        <th>Operator</th>
                                                        <th>E-mail</th>
                                                        <th>Mobile</th>
                                                        <th>Last Login</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>

                                                    <?php foreach ($active_admin as $key => $value){ ?>
                                                        <tr>
                                                            <td><?php echo $key+1; ?></td>

                                                            <td><?php echo $value['brand_name']; ?></td>
                                                            <td><?php echo $value['modal_name']; ?></td>
                                                            <td><?php echo $value['operator_name']; ?></td>
                                                            <td><?php echo $value['email']; ?></td>
                                                            <td><?php echo $value['mobile']; ?></td>
                                                            <td><?php echo date('d-m-Y h:i A', strtotime($value['created'])); ?></td>
                                                            <td> <button type="button" class="btn btn-xs btn-success logout_btn"  data-id="<?php echo base64_encode($value['id']); ?>"  >Logout</button></td>
                                                        </tr>
                                                    <?php } ?>

                                                    </tbody>
                                                </table>

                                            </div>
                                        <?php } ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>




                            </div>
                            <div class="clear"></div>
                            <?php  } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .select2-container .select2-selection--single{
        height:34px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style>

<script type="text/javascript">
    $(function(){

        $('.thinapp_id').select2();
        $(document).on('change','.thinapp_id',function(e){
            $(".refresh_btn").hide();
        });


        $(document).on('click','.logout_btn',function(e) {
            var rowID = $(this).attr('data-id');
            var thisButton = $(this);
            if(confirm("Are you sure?")){
                $.ajax({
                    url: baseurl+'/admin/supp/logout_from_device',
                    data:{li:rowID},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var data = JSON.parse(result);
                        if(data.status == 1)
                        {
                            $(thisButton).closest('tr').hide();
                        }
                        else
                        {
                            $.alert(data.message);
                        }
                    }
                });

            }

        });

    });
</script>
