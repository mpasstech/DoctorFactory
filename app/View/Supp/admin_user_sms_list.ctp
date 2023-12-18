<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>SMS User List</h2> </div>
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
                            <a href="<?php echo Router::url('/admin/supp/user_sms_list'); ?>"  class="active" ><i class="fa fa-list"></i> User SMS List</a>
                            <a href="<?php echo Router::url('/admin/supp/sms_transition'); ?>" ><i class="fa fa-list"></i> SMS Transition</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_user_sms_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Insert user name', 'label' => 'Search by user name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('appName', array('type' => 'text', 'placeholder' => 'Insert app name', 'label' => 'Search by app name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('smsCount', array('type' => 'select', 'empty' => 'Select SMS', 'options' => array('< 10'=>'Less then 10','< 50'=>'Less then 50','< 100'=>'Less then 100','< 500'=>'Less then 500','< 1000'=>'Less then 1000','< 2000'=>'Less then 2000','< 5000'=>'Less then 5000','> 5000'=>'More then 5000',), 'label' => 'Select SMS', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'user_sms_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
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
                                        <th>Total Promotional SMS</th>
                                        <th>Total Transactional SMS</th>
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
                                            <td><?php echo $value['AppSmsStatic']['total_promotional_sms']; ?></td>
                                            <td><?php echo $value['AppSmsStatic']['total_transactional_sms']; ?></i></td>
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

