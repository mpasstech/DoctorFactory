<?php  $login = $this->Session->read('Auth.User'); ?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>User List</h2> </div>
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


                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_user_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Insert name', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => 'Insert mobile', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'user_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>

                            <div class="form-group row">
                                <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-responsive ">

                                <thead>
                                <tr >
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Is Support User</th>
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
                                            <td class="td_links">
                                                <div style="display:flex;">
                                                    <button type="button" id="changeStatus" class="action_icon btn <?php echo ($val['User']['is_support_user']=='YES')?'btn-success':'btn-default'; ?>  btn-xs setting_div_<?php echo $val['User']['id']; ?>" row-id="<?php echo $val['User']['id']; ?>" ><?php echo $val['User']['is_support_user']; ?></button>
                                           </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{ ?>
                                   <h5>No User Found</h5>
                                <?php } ?>

                                </tbody>
                            </table>
                            <?php echo $this->element('paginator'); ?>
                        </div>


                        <div class="clear"></div>
                    </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</section>




<script>
    $(document).ready(function(){

        $(document).on('click','#changeStatus',function(e){
            var rowID = $(this).attr('row-id');

            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/change_support_user',
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
                        if(result.text =="YES"){
                            $(thisButton).removeClass('btn-default').addClass('btn-success');
                        }else{
                            $(thisButton).removeClass('btn-success').addClass('btn-default');
                        }
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });

    });
</script>


<style>


    .Social-login-box{
        padding: 17px 4px !important;
    }
    .action_icon{
        padding:  1px 20px; !important;
    }
</style>
