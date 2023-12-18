<?php
$login = $this->Session->read('Auth.User');
?>


<?php echo $this->Html->css(array('bootstrap-multiselect.css'),array("media"=>'all','fullBase' => true)); ?>
<script src="<?php echo Router::url('/js/bootstrap-multiselect.js')?>"></script>


<style>
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        padding: 0px;
    }
    .ver_load{
        float: left;
        position: relative;
        margin: 10px 1px;
    }
    .tracker_load{
        float: right;
        margin: 2px 6px;
        position: absolute;
        right: 5px;

    }
    .custom-control-label{
        width: 100%;
    }
    .tracker_box{
        border: 5px solid #f8f8f8;
        padding: 0px;
        margin: 7px 3px;
        width: 32%;
        min-height: 550px;
        background: #e5e5e5;
        float: left;

    }
    .tracker_box .custom-radio{
        min-height: 284px;
        display: block;
        width: 100%;
        float: left;
    }
    .active_temp{
        border-top-color: green !important;
    }
    .custom-radio{
        padding: 5px 9px;
        background: #f8f8f8;
        border-top: 3px solid #c8c8fb94;
    }
    .display_box{
        border: 1px solid #eeeeee;
        padding: 5px 5px;
        margin: 3px;
        width: 24%;
    }
    .display_box img{
        margin: 4px 0px;
        border-radius: 10px;
        float: left;
        height: 40px;
        width: 40px;
    }
    .display_box .room_name{
        width: 90%;
        float: left;
        margin: 3px 0px;
        height: 20px;
        padding: 0px 2px;
        border: none;
        border-bottom: 1px solid #d0d0d0;
        border-radius: 0px;
        outline: none;
        border-top: none !important;
        box-shadow: none;
    }
    .mobile_lbl{
        display: block;
        font-size: 10px;
        line-height: 1.5;
    }
    .tracker_column_div a{
        float: left;
    }
    .fifty_div{
        width: 49%;
        float: left;
        padding: 0px;
        border: 1px solid;
        margin: 2px;
    }
    .fifty_lbl_radio{
        float: left;
        width: 50%;
        padding-left: 11%;
    }
</style>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <h3 class="screen_title">Tracker Template Setting</h3>
                <div class="middle-block">
                    <!-- Heading -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>


                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3> Template Setting</h3>
                                    <?php if(!empty($template_list)){
                                        foreach ($template_list as $key => $template){
                                            ?>
                                            <div class="col-sm-4 tracker_box">
                                                <img style="height: 250px; width: 100%" src="<?php echo $template['url']; ?>" >

                                                <div class="custom-control custom-radio <?php echo ($app_data['tracker_template_id'] == $template['id'])?"active_temp":''; ?>">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" class="custom-control-input" value="<?php echo $template['id']; ?>" id="template" name="template" <?php echo ($app_data['tracker_template_id'] == $template['id'])?"checked":''; ?> />
                                                            <?php echo $template['name']; ?>
                                                        </label>
                                                    </div>

                                                    <h3 style="display: block;width: 100%;">Tracker Refresh setting in seconds</h3>
                                                    <label>Refresh Tracker</label>
                                                    <select class="refresh_list">
                                                        <option value="0">0</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="15">15</option>
                                                        <option value="20">20</option>
                                                        <option value="25">25</option>
                                                        <option value="30">30</option>
                                                        <option value="35">35</option>
                                                        <option value="40">40</option>
                                                    </select>
                                                    &nbsp;&nbsp;
                                                    <label>Change Doctor</label>
                                                    <select class="change_doctor">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                        <option value="13">13</option>
                                                        <option value="14">14</option>
                                                        <option value="15">15</option>
                                                        <option value="20">20</option>
                                                    </select>

                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input show_time"  id="<?php echo "show_time_".$template['id']; ?>" <?php echo ($app_data['show_tracker_time']=="YES")?"checked":""; ?>>
                                                        <label class="form-check-label" for="<?php echo "show_time_".$template['id']; ?>"  >Show time on tracker</label>
                                                    </div>


                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input show_patient_name"  id="<?php echo "show_patient_name_".$template['id']; ?>" <?php echo ($app_data['show_patient_name_on_tracker']=="YES")?"checked":""; ?>>
                                                        <label class="form-check-label" for="<?php echo "show_patient_name_".$template['id']; ?>"  >Show patient name on tracker</label>
                                                    </div>


                                                </div>
                                            </div>
                                        <?php }
                                    } ?>








                                    <div class="col-sm-4 tracker_box">
                                        <a href="<?php echo Router::url('/tracker/display_tracker_opd_new/'.base64_encode($login['User']['thinapp_id'])); ?>" target="_blank">
                                            <img style="height: 250px; width: 100%" src="<?php echo Router::url('/thinapp_images/smart_temp1.png'); ?>" >
                                        </a>

                                        <div class="custom-control custom-radio">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" class="custom-control-input" value="20" id="template" name="template" <?php echo ($app_data['tracker_template_id'] == '20')?"checked":''; ?> />
                                                    Smart Tracker 1
                                                </label>
                                            </div>

                                            <h3 style="display: block;width: 100%;">
                                                <a target="_blank" href="<?php echo Router::url('/app_admin/speech_message'); ?>" class="btn btn-success btn-sm">Speech Message</a>

                                                <a href="Javascript:void(0)" id="announcement" class="btn btn-success btn-sm">Announcement Message</a>
                                            </h3>



                                            <label>Select Doctor</label>
                                            <select class="select_doctor_multi multiselect-ui  form-control" multiple>
                                                <?php
                                                $newDocID = json_decode($trackerDocData['tracker_new_doctor_id']);
                                                $newDocID = is_array($newDocID)?$newDocID:array();
                                                ?>
                                                <?php foreach ($doctor_list as $key => $doctor){ ?>
                                                    <option value="<?php echo $doctor['id']; ?>" <?php echo (in_array($doctor['id'],$newDocID))?'selected=""SELECTED':''; ?> ><?php echo $doctor['name']; ?></option>
                                                <?php } ?>
                                            </select>

                                            <label>Refresh Tracker</label>
                                            <select class="refresh_list_tracker_new">
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 5)?'selected':''; ?> value="5">5</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 6)?'selected':''; ?> value="6">6</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 7)?'selected':''; ?> value="7">7</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 8)?'selected':''; ?> value="8">8</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 9)?'selected':''; ?> value="9">9</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 10)?'selected':''; ?> value="10">10</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 15)?'selected':''; ?> value="15">15</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 20)?'selected':''; ?> value="20">20</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 25)?'selected':''; ?> value="25">25</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 30)?'selected':''; ?> value="30">30</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 35)?'selected':''; ?> value="35">35</option>
                                                <option <?php echo ($trackerDocData['tracker_new_refresh_sec'] == 40)?'selected':''; ?> value="40">40</option>
                                            </select>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input show_patient_name_tracker_new"  id="show_patient_name_tracker_new" <?php echo ($trackerDocData['tracker_new_show_patient_name'] == 'Y')?'checked':''; ?> >
                                                <label class="form-check-label" for="show_patient_name_tracker_new"  >Show patient name on tracker</label>
                                            </div>

                                            <label>Select Tune</label>
                                            <select class="tune_tracker_new" id="tune_tracker_new">
                                                <option value="">Default Voice</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "beyond-doubt.mp3")?'selected':''; ?> value="beyond-doubt.mp3">Tune 1</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "beyond-doubt-2.mp3")?'selected':''; ?> value="beyond-doubt-2.mp3">Tune 2</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "chime.mp3")?'selected':''; ?> value="chime.mp3">Tune 3</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "definite.mp3")?'selected':''; ?> value="definite.mp3">Tune 4</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "end-point-reached.mp3")?'selected':''; ?> value="end-point-reached.mp3">Tune 5</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "for-sure.mp3")?'selected':''; ?> value="for-sure.mp3">Tune 6</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "graceful.mp3")?'selected':''; ?> value="graceful.mp3">Tune 7</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "just-like-that.mp3")?'selected':''; ?> value="just-like-that.mp3">Tune 8</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "just-maybe.mp3")?'selected':''; ?> value="just-maybe.mp3">Tune 9</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "knob.mp3")?'selected':''; ?> value="knob.mp3">Tune 10</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "oh-finally.mp3")?'selected':''; ?> value="oh-finally.mp3">Tune 11</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "pluck.mp3")?'selected':''; ?> value="pluck.mp3">Tune 12</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "relentless.mp3")?'selected':''; ?> value="relentless.mp3">Tune 13</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_new'] == "youve-been-informed.mp3")?'selected':''; ?> value="youve-been-informed.mp3">Tune 14</option>
                                            </select>
                                            <button type="button" data-id="tune_tracker_new" class="play btn btn-primary btn-xs"><i class="fa fa-play-circle-o" aria-hidden="true"></i></button>
                                        </div>
                                    </div>



                                    <div class="col-sm-4 tracker_box">
                                        <a href="<?php echo Router::url('/tracker/display_tracker_opd_multiple/'.base64_encode($login['User']['thinapp_id'])); ?>" target="_blank">
                                            <img style="height: 250px; width: 100%"  src="<?php echo Router::url('/images/Screenshot_2019-09-04 Appointment Tracker.png'); ?>"  >
                                        </a>
                                        <div class="custom-control custom-radio" >
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" class="custom-control-input" value="22" id="template" name="template" <?php echo ($app_data['tracker_template_id'] == '22')?"checked":''; ?> />
                                                    Smart Tracker 3
                                                </label>
                                            </div>

                                            <label>Refresh Tracker</label>
                                            <select class="refresh_list_tracker_multiple">
                                                <option value="5" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 5)?'selected':''; ?>>5</option>
                                                <option value="6" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 6)?'selected':''; ?>>6</option>
                                                <option value="7" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 7)?'selected':''; ?>>7</option>
                                                <option value="8" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 8)?'selected':''; ?>>8</option>
                                                <option value="9" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 9)?'selected':''; ?>>9</option>
                                                <option value="10" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 10)?'selected':''; ?>>10</option>
                                                <option value="15" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 15)?'selected':''; ?>>15</option>
                                                <option value="20" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 20)?'selected':''; ?>>20</option>
                                                <option value="25" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 25)?'selected':''; ?>>25</option>
                                                <option value="30" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 30)?'selected':''; ?>>30</option>
                                                <option value="35" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 35)?'selected':''; ?>>35</option>
                                                <option value="40" <?php echo ($trackerDocData['tracker_multiple_refresh_sec'] == 40)?'selected':''; ?>>40</option>
                                            </select>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input show_patient_name_tracker_multiple"  <?php echo ($trackerDocData['tracker_multiple_show_patient_name'] == 'Y')?'checked':''; ?> id="show_patient_name_tracker_multiple">
                                                <label class="form-check-label" for="show_patient_name_tracker_multiple"  >Show patient name on tracker</label>
                                            </div>


                                            <label>Select Tune</label>
                                            <select class="tune_tracker_multiple" id="tune_tracker_multiple">
                                                <option value="">Default Voice</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "beyond-doubt.mp3")?'selected':''; ?> value="beyond-doubt.mp3">Tune 1</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "beyond-doubt-2.mp3")?'selected':''; ?> value="beyond-doubt-2.mp3">Tune 2</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "chime.mp3")?'selected':''; ?> value="chime.mp3">Tune 3</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "definite.mp3")?'selected':''; ?> value="definite.mp3">Tune 4</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "end-point-reached.mp3")?'selected':''; ?> value="end-point-reached.mp3">Tune 5</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "for-sure.mp3")?'selected':''; ?> value="for-sure.mp3">Tune 6</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "graceful.mp3")?'selected':''; ?> value="graceful.mp3">Tune 7</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "just-like-that.mp3")?'selected':''; ?> value="just-like-that.mp3">Tune 8</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "just-maybe.mp3")?'selected':''; ?> value="just-maybe.mp3">Tune 9</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "knob.mp3")?'selected':''; ?> value="knob.mp3">Tune 10</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "oh-finally.mp3")?'selected':''; ?> value="oh-finally.mp3">Tune 11</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "pluck.mp3")?'selected':''; ?> value="pluck.mp3">Tune 12</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "relentless.mp3")?'selected':''; ?> value="relentless.mp3">Tune 13</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_multiple'] == "youve-been-informed.mp3")?'selected':''; ?> value="youve-been-informed.mp3">Tune 14</option>
                                            </select>
                                            <button type="button" data-id="tune_tracker_multiple" class="play btn btn-primary btn-xs"><i class="fa fa-play-circle-o" aria-hidden="true"></i></button>

                                        </div>

                                    </div>

                                    <div class="col-sm-6 tracker_box tracker_column_div" style="width:64%;">
                                        <a class="fifty_div" href="<?php echo Router::url('/tracker/display_tracker_opd_media/'.base64_encode($login['User']['thinapp_id'])); ?>" target="_blank">
                                            <img style="height: 250px; width: 100%"  src="<?php echo Router::url('/thinapp_images/smart_temp2.png'); ?>"  >
                                        </a>

                                        <a class="fifty_div" href="<?php echo Router::url('/tracker/display_tracker_opd_media_two_column/'.base64_encode($login['User']['thinapp_id'])); ?>" target="_blank">
                                            <img style="height: 250px; width: 100%"  src="<?php echo Router::url('/thinapp_images/smart_temp2_column.png'); ?>"  >
                                        </a>

                                        <div class="custom-control custom-radio" >
                                            <div class="radio">
                                                <label class="fifty_lbl_radio" >
                                                    <input type="radio" class="custom-control-input" value="21" id="template" name="template" <?php echo ($app_data['tracker_template_id'] == '21')?"checked":''; ?> />
                                                    Smart Tracker Horizontal
                                                </label>
                                                <label class="fifty_lbl_radio" >
                                                    <input type="radio" class="custom-control-input" value="23" id="template" name="template" <?php echo ($app_data['tracker_template_id'] == '23')?"checked":''; ?> />
                                                    Smart Tracker Vertical
                                                </label>
                                            </div>



                                            <h3 style="display: block;width: 100%; float: left;">
                                                <a target="_blank" href="<?php echo Router::url('/app_admin/media_message'); ?>" class="btn btn-success btn-sm">Media Message</a>
                                            </h3>


                                            <label>Select Doctor</label>

                                            <select class="select_doctor multiselect-ui-1  form-control" multiple>
                                                <?php
                                                $newMediaID = json_decode($trackerDocData['tracker_media_doctor_id']);
                                                $newMediaID = is_array($newDocID)?$newDocID:array();
                                                ?>
                                                <?php foreach ($doctor_list as $key => $doctor){ ?>
                                                    <option value="<?php echo $doctor['id']; ?>" <?php echo (in_array($doctor['id'],$newMediaID))?'selected=""SELECTED':''; ?> ><?php echo $doctor['name']; ?></option>
                                                <?php } ?>
                                            </select>




                                            <label>Refresh Tracker</label>
                                            <select class="refresh_list_tracker_media">

                                                <option value="5" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 5)?'selected':''; ?> >5</option>
                                                <option value="6" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 6)?'selected':''; ?>>6</option>
                                                <option value="7" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 7)?'selected':''; ?>>7</option>
                                                <option value="8" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 8)?'selected':''; ?>>8</option>
                                                <option value="9" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 9)?'selected':''; ?>>9</option>
                                                <option value="10" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 10)?'selected':''; ?>>10</option>
                                                <option value="15" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 15)?'selected':''; ?>>15</option>
                                                <option value="20" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 20)?'selected':''; ?>>20</option>
                                                <option value="25" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 25)?'selected':''; ?>>25</option>
                                                <option value="30" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 30)?'selected':''; ?>>30</option>
                                                <option value="35" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 35)?'selected':''; ?>>35</option>
                                                <option value="40" <?php echo ($trackerDocData['tracker_media_refresh_sec'] == 40)?'selected':''; ?>>40</option>
                                            </select>
                                            <div class="form-check">
                                                <input type="checkbox"  <?php echo ($trackerDocData['tracker_media_show_patient_name'] == 'Y')?'checked':''; ?> class="form-check-input show_patient_name_tracker_media"  id="show_patient_name_tracker_media">
                                                <label class="form-check-label" for="show_patient_name_tracker_media"  >Show patient name on tracker</label>
                                            </div>

                                            <label>Select Tune</label>
                                            <select class="tune_tracker_media" id="tune_tracker_media">
                                                <option value="">Default Voice</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "beyond-doubt.mp3")?'selected':''; ?> value="beyond-doubt.mp3">Tune 1</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "beyond-doubt-2.mp3")?'selected':''; ?> value="beyond-doubt-2.mp3">Tune 2</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "chime.mp3")?'selected':''; ?> value="chime.mp3">Tune 3</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "definite.mp3")?'selected':''; ?> value="definite.mp3">Tune 4</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "end-point-reached.mp3")?'selected':''; ?> value="end-point-reached.mp3">Tune 5</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "for-sure.mp3")?'selected':''; ?> value="for-sure.mp3">Tune 6</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "graceful.mp3")?'selected':''; ?> value="graceful.mp3">Tune 7</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "just-like-that.mp3")?'selected':''; ?> value="just-like-that.mp3">Tune 8</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "just-maybe.mp3")?'selected':''; ?> value="just-maybe.mp3">Tune 9</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "knob.mp3")?'selected':''; ?> value="knob.mp3">Tune 10</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "oh-finally.mp3")?'selected':''; ?> value="oh-finally.mp3">Tune 11</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "pluck.mp3")?'selected':''; ?> value="pluck.mp3">Tune 12</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "relentless.mp3")?'selected':''; ?> value="relentless.mp3">Tune 13</option>
                                                <option <?php echo ($trackerDocData['tune_tracker_media'] == "youve-been-informed.mp3")?'selected':''; ?> value="youve-been-informed.mp3">Tune 14</option>
                                            </select>
                                            <button type="button" data-id="tune_tracker_media" class="play btn btn-primary btn-xs"><i class="fa fa-play-circle-o" aria-hidden="true"></i></button>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <h3>Payment Queue Tracker</h3>
                                    <a class="btn btn-success" target="_blank" href="<?php echo Router::url('/tracker/pq/'.base64_encode($login['User']['thinapp_id']),true); ?>">Click to View</a>
                                </div>

                                <?php if(CK_BIRLA_APP_ID==$login['User']['thinapp_id']){ ?>
                                    <div class="col-sm-6">
                                        <h3>Counter Token Tracker</h3>
                                        <a class="btn btn-success" target="_blank" href="<?php echo Router::url('/tracker/counter_tracker/'.base64_encode($login['User']['thinapp_id']),true); ?>">Click to View</a>
                                    </div>
                                <?php } ?>


                            </div>


                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h3> Doctor Room Setting</h3>
                                    <?php if(!empty($doctor_list)){ ?>





                                        <?php   foreach ($doctor_list as $key => $doctor){ ?>


                                            <div class="col-sm-3 display_box">
                                                <img  src="<?php echo !empty($doctor['profile_photo'])?$doctor['profile_photo']:$app_data['logo']; ?>" >
                                                <label style="padding:0px 5px; display: block; float: left; width: 82%;">
                                                    <?php echo $doctor['name']; ?>
                                                    <span class="mobile_lbl"> <?php $department = !empty($doctor['department_name'])?'<br>'.$doctor['department_name']:'<br>N/A'; echo $doctor['mobile'].$department ; ?></span>

                                                </label>
                                                <input row-id="<?php echo base64_encode($doctor['id']); ?>" type ="text"  last-value = "<?php echo $doctor['room_number']; ?>" value = "<?php echo $doctor['room_number']; ?>" placeholder="Enter room number"  class="form-control room_name" />

                                            </div>


                                        <?php }} ?>
                                </div>
                            </div>

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


<div class="modal fade" id="myModalForm" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <form method="post" id="annForm">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Announcement Message</h4>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="text" maxlength="40" value="<?php echo $trackerDocData['tracker_voice']; ?>" class="form-control" placeholder="Announcement Message" name="ann_message" id="ann_message">
                            <p class="ann_note"> Note: Enter [TOKEN] for token number, [NAME] for patient name, [ROOM] for room number</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer payment">
                    <div class="submit">
                        <input class="btn btn-info btn-xl" id="ann_submit" value="Submit" type="submit">
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>


<style>
    .ann_note {
        color:
                red;
        font-weight: bold;
        font-size: 11px;
    }
    .dropdown-toggle{
        height: 45px;
        margin-bottom: 12px;
        text-align: left;
    }
    .dropdown-toggle {
        background-image: none !important;
        border-color: #FFFFFF !important;
    }
    .play {
        height: 22px;
        margin-top: -4px;
    }
    <?php if($trackerDocData['tune_tracker_new'] == ''){ ?>
    [data-id="tune_tracker_new"]{
        display: none;
    }
    <?php } ?>
    <?php if($trackerDocData['tune_tracker_media'] == ''){ ?>
    [data-id="tune_tracker_media"]{
        display: none;
    }
    <?php } ?>
    <?php if($trackerDocData['tune_tracker_multiple'] == ''){ ?>
    [data-id="tune_tracker_multiple"]{
        display: none;
    }
    <?php } ?>
</style>

<script>
    $(document).ready(function() {

        $(document).on("click","#announcement",function(){
            $("#myModalForm").modal("show");
        });

        var arr = new Array();


        $('.multiselect-ui').multiselect({
            buttonWidth: '100%',
            onChange: function(option, checked) {
                // Get selected options.
                var selectedOptions = $('.multiselect-ui option:selected');

                if (selectedOptions.length >= 2) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('.multiselect-ui option').filter(function() {
                        return !$(this).is(':selected');
                    });

                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                }
                else {
                    // Enable all checkboxes.
                    $('.multiselect-ui option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
            }
        });

        $('.multiselect-ui-1').multiselect({
            buttonWidth: '100%',
            onChange: function(option, checked) {

                $('.multiselect-ui option').each(function() {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });

            }
        });



        /**************SMART TRACKER START**************/

        $(".select_doctor_multi").change(function() {
            $(this).find("option:selected")
            if ($(this).find("option:selected").length > 2) {
                $(this).find("option").removeAttr("selected");
                $(this).val(arr);
                alert('You can select upto 2 options only');
            }
            else {
                arr = new Array();
                $(this).find("option:selected").each(function(index, item) {
                    arr.push($(item).val());
                });

                var dataToSend = $(".select_doctor_multi").val();
                $.ajax({
                    url: baseurl + '/tracker/update_doctor_tracker_new',
                    data: {ID: dataToSend},
                    type: 'POST',
                    success: function (result) {
                        var result = JSON.parse(result);
                        if (result.status != 1) {
                            alert(result.message);
                        }
                    }
                });


            }
        });


        $(".select_doctor").change(function() {

            var dataToSend = $(".select_doctor").val();
            $.ajax({
                url: baseurl + '/tracker/update_doctor_tracker_media',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });

            /* var dataToSend = $(".select_doctor").val();
            $.ajax({
                url: baseurl + '/tracker/update_doctor_tracker_media',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            }); */
        });






        $(".refresh_list_tracker_new").change(function(){
            var dataToSend = $(this).val();
            $.ajax({
                url: baseurl + '/tracker/tracker_new_update_refresh_sec',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });
        $(".show_patient_name_tracker_new").click(function(){
            var dataToSend = $('.show_patient_name_tracker_new:checked').length > 0?'Y':'N';
            $.ajax({
                url: baseurl + '/tracker/show_patient_name_tracker_new',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });

        $(".refresh_list_tracker_media").change(function(){
            var dataToSend = $(this).val();
            $.ajax({
                url: baseurl + '/tracker/tracker_media_update_refresh_sec',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });
        $(".show_patient_name_tracker_media").click(function(){
            var dataToSend = $('.show_patient_name_tracker_media:checked').length > 0?'Y':'N';
            $.ajax({
                url: baseurl + '/tracker/show_patient_name_tracker_media',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });

        $(".refresh_list_tracker_multiple").change(function(){
            var dataToSend = $(this).val();
            $.ajax({
                url: baseurl + '/tracker/tracker_multiple_update_refresh_sec',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });
        $(".show_patient_name_tracker_multiple").click(function(){
            var dataToSend = $('.show_patient_name_tracker_multiple:checked').length > 0?'Y':'N';
            $.ajax({
                url: baseurl + '/tracker/show_patient_name_tracker_multiple',
                data: {ID: dataToSend},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });




        $(".tune_tracker_multiple, .tune_tracker_media, .tune_tracker_new").change(function(){
            var field = $(this).attr('id');
            var dataToSend = $(this).val();
            if(dataToSend == '')
            {
                console.log($(this).next('.play'));
                $(this).next('.play').hide();
            }
            else
            {
                $(this).next('.play').show();
            }
            $.ajax({
                url: baseurl + '/tracker/tracker_update_tune',
                data: {val: dataToSend, field:field},
                type: 'POST',
                success: function (result) {
                    var result = JSON.parse(result);
                    if (result.status != 1) {
                        alert(result.message);
                    }
                }
            });
        });







        /**************SMART TRACKER END**************/

    });

    $(function () {


        $('input[type=radio][name=template], .refresh_list, .change_doctor, .show_time, .show_patient_name').change(function(e) {
            if ($(this).is(':checked') || e.target.type !='radio' ) {

                if(e.target.type !='radio'){
                    $(this).closest('.custom-control').find("input[type=radio][name=template]").attr('checked',true);
                }
                update_setting($(this).closest('.custom-control'));
            }


        });

        function update_setting(obj){
            var thisButton = $(obj).find("input[type=radio][name=template]");
            var rowID = $(obj).find("input[type=radio][name=template]:checked").val();
            var rl = $(obj).find(".refresh_list").val();
            var dc = $(obj).find(".change_doctor").val();
            var st = $(obj).find('.show_time').is(":checked");
            var spn = $(obj).find('.show_patient_name').is(":checked");
            $.ajax({
                url: baseurl + '/tracker/update_tracker_template',
                data: {rowID: rowID,rl:rl,dc:dc,st:st,spn:spn},
                type: 'POST',
                beforeSend: function () {
                    $(thisButton).closest('.radio').append('<i class="fa fa-spinner fa-pulse tracker_load">');
                },
                success: function (result) {
                    $(thisButton).closest('.radio').find(".tracker_load").remove();
                    var result = JSON.parse(result);
                    if (result.status == 1) {
                        $('.custom-radio').removeClass("active_temp");
                        $(thisButton).closest('.custom-radio').addClass("active_temp");
                    }
                    else {
                        $(thisButton).closest('.custom-radio').css('border-top-color', "red");
                    }
                }
            });
        }

        $(".active_temp .refresh_list, .active_temp .change_doctor").val("0");
        $(".active_temp .refresh_list").val("<?php echo $app_data['refresh_tracker_list_second']; ?>");
        $(".active_temp .change_doctor").val("<?php echo $app_data['change_tracker_doctor_second']; ?>");
        $(document).on('blur','.room_name',function(e){
            var room = $(this).val();
            var rowID = $(this).attr('row-id');
            var last_value = $(this).attr('last-value');
            var thisButton = $(this);
            if(room != last_value)
            {
                $.ajax({
                    url: baseurl + '/tracker/update_doctor_room',
                    data: {room: room, rowID: rowID},
                    type: 'POST',
                    beforeSend: function () {
                        $(thisButton).closest('.display_box').append('<i class="fa fa-spinner fa-pulse ver_load">');
                    },
                    success: function (result) {
                        $(thisButton).closest('.display_box').find(".ver_load").remove();
                        var result = JSON.parse(result);
                        if (result.status == 1) {
                            $(thisButton).attr('last-value',room);
                            $(thisButton).css('border-color', "green");
                        }
                        else {
                            $(thisButton).css('border-color', "red");
                        }
                    }
                });
            }else{
                $(this).val(last_value);
            }
        });


    });

    $(document).ready(function(){
        $(document).on('blur','.select_doctor_multi',function(e){


        });
    });
    $(document).ready(function(){
        $(".play").click(function(){
            var ID = $(this).attr("data-id");
            var tuneName = $("#"+ID).val();

            var audioElement1 = document.createElement('audio');
            var audioUrl = "<?php echo Router::url('/tracker_tunes/'); ?>"+tuneName;

            audioElement1.setAttribute('src', audioUrl);

            audioElement1.play({
                onplay: function() {
                    //console.log('Yay, playing');

                },
                onerror: function(errorCode, description) {
                    //console.log(errorCode,description);

                },
                onended: function() {

                }
            });
        });
    });

    $(document).on("submit","#annForm",function(e){
        e.preventDefault();
        var data = $( this ).serialize();


        $.ajax({
            url: baseurl + '/tracker/update_announcement_message',
            data: data,
            type: 'POST',
            beforeSend: function () {
                $("#ann_submit").attr('disabled','disabled');
            },
            success: function (result) {
                $("#ann_submit").removeAttr('disabled');
                $("#myModalForm").modal("hide");
            }
        });



    });

</script>











