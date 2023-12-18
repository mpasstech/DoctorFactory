<?php
$login = $this->Session->read('Auth.User');
$latest_apk = $this->AppAdmin->getLetestApk($login['Leads']['customer_id']);
$quires = $this->AppAdmin->getAppQueries($login['Leads']['customer_id']);

?>


<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Account</h2> </div>
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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <?php echo $this->element('app_admin_inner_tab_header'); ?>


                        <div class="Social-login-box payment_bx">



                            <?php echo $this->element('message'); ?>

                            <?php if($latest_apk){ ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="download_link">Download your app<a href="<?php echo Router::url('/uploads/apk/',true).$latest_apk['CmsPage']['name']; ?>" > <img src="<?php echo Router::url('/images/donload-android.png'); ?>"></a></div>
                                </div>
                            </div>
                            <?php }else{ ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="no_apk">You don't have any apk for download !</div>
                                    </div>
                                </div>
                            <?php } ?>


                            <?php echo $this->Form->create('AppQueries',array('enctype'=>'multipart/form-data','admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Your query</label>
                                    <?php echo $this->Form->input('message', array('label'=>false,'rows'=>3,'class'=>"form-control",'type' => 'textarea','placeholder'=>"",'required'=>'required')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Upload file</label>
                                    <?php echo $this->Form->input('file', array('label'=>false,'class'=>"form-control",'type' => 'file')); ?>
                                </div>
                            </div>


                            <div class="col-sm-3">
                                <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5','id'=>'signup')); ?>


                            </div>


                            <div class="clear"></div>

                            <?php echo $this->Form->end();?>






                            <?php if(count($quires) > 0){ ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <h3>Queries and Supports</h3>
                                        <ul class="app_comment_ul">
                                            <?php foreach ($quires as $key => $qur){  ?>
                                                <li>
                                                    <div class="msg_content_<?php echo ($qur['AppQueries']['sender_id']==$login['User']['id'])?'2':'1'; ?>">
                                                        <label><?php echo $qur['Sender']['username']; ?><span class="msg_time"><?php echo date('Y-m-d H:i',strtotime($qur['AppQueries']['created'])); ?></span></label>

                                                        <p><?php echo $qur['AppQueries']['message']; ?></p>





                                        <ul class="msg_inner_ul">
                                            <?php if(!empty($qur['AppQueries']['attachment'])){ ?>
                                            <li class="att_msg">
                                                    <a title="Attachment for this message" href="<?php echo Router::url('/uploads/message/').$qur['AppQueries']['attachment']; ?>" download=""><i class="fa fa-paperclip" aria-hidden="true"></i></a>
                                            </li>
                                            <?php } ?>

                                            <?php if(!empty($qur['UploadApk']['name'])){ ?>
                                            <li>
                                                <strong>Thinapp version <?php echo $qur['UploadApk']['version']; ?></strong>
                                                    <a title="Download your apk file from here" href="<?php echo Router::url('/uploads/apk/').$qur['UploadApk']['name']; ?>" download=""><i class="fa fa-android" aria-hidden="true"></i></a>
                                            </li>
                                            <?php } ?>




                                        </ul>





                                                        <div class="clear"></div>
                                                    </div>
                                                </li>
                                            <?php } ?>

                                        </ul>

                                    </div>
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
    $(document).ready(function (){
        $(".app_inner_tab a").removeClass('active');
        $("#v_download").addClass('active');
    });
</script>






