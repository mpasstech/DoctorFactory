


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                            <?php echo $this->element('message'); ?>
                        <h3 class="screen_title">Subscriber</h3>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <?php echo $this->element('app_admin_inner_tab_subscriber'); ?>
                        <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_subscriber'))); ?>
                        <div class="form-group">
                            <div class="col-sm-4">

                                <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                            </div>
                            <div class="col-sm-4">

                                <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                <div class="submit">
                                    <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'subscriber')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                </div>
                            </div>

                        </div>

                        <?php echo $this->Form->end(); ?>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <?php if(!empty($subscriber)){ ?>
                                    <div class="table-responsive">

                                    <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>User Name</th>
                                        <th>Mobile</th>
                                        <th>E-mail</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($subscriber as $key => $list){
                                        if(empty($list['User']['image'])){
                                            $list['User']['image'] =Router::url('/images/channel-icon.png',true);
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><img class="channel_icon_list" src="<?php echo $list['User']['image'];?>"></td>
                                            <td><?php
                                                if(!empty($list['User']['username'])){
                                                    echo $list['User']['username'];
                                                }else if(!empty($list['Subscriber']['name'])){
                                                    echo $list['Subscriber']['name'];
                                                }else{
                                                    echo "Anonymous";
                                                }

                                    ?></td>
                                            <td><?php echo $list['Subscriber']['mobile']; ?></td>
                                            <td><?php echo $list['Subscriber']['email']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>
                           <?php }else{ ?>
                            <div class="no_data">
                                <h2>No subscriber..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>
                        </div>


                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>




<script>
    $(document).ready(function(){

        $(".channel_tap a").removeClass('active');
        $("#v_app_subscriber_list").addClass('active');
    });
</script>





