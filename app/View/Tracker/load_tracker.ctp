<?php if(!empty($tracker_data)){
    if($template_id == 1) {
        foreach ($tracker_data as $key => $data) {
            ?>


            <!-- Start hero featured area -->
            <div style="display: none;" id="<?php echo $data['doctor_id']; ?>" data-aea="<?php echo $data['allow_emergency_appointment']; ?>" class="main_div mu-hero-featured-area">
                <!-- Start center Logo -->
                <div class="mu-logo-area">
                    <!-- text based logo -->
                    <a class="mu-logo" href="#"><?php echo $data['doctor_name']; ?></a>
                </div>
                <!-- End center Logo -->
                <div class="mu-event-counter-area">
                    <?php $label = $this->AppAdmin->tracker_labels($data['patient_queue_type'],$data['sub_token']); ?>
                    <div id="mu-event-counter"><span class="mu-event-counter-block"><span style="<?php echo ($data['sub_token'] == "YES" || $data['has_token'] =='NO' || !empty($label)) ? 'font-size:90px' : ''; ?>" class="<?php echo ($data['sub_token'] == 'YES') ? 'blink_me' : ''; ?>"><?php

                                if (empty($label)) {
                                    echo @ $data['token_number'];
                                } else {
                                    echo $label;
                                }

                                ?></span></span>

                        <?php
                        if ($data['sub_token'] == 'YES') { ?>
                            <p class="note_lbl">Note:- Expect some delay if doctor is attending to emergency cases</p>
                        <?php } ?>


                    </div>
                </div>
                <div class="mu-hero-featured-content">



                    <h1><?php echo ($show_patient_name=="YES")?$data['patient_name']:''; ?></h1>
                    <?php
                            $label = ($show_time=="YES" && empty($label))?"Approx : ".$data['time_slot']:date('d F, Y');
                    ?>
                    <p class="mu-event-date-line"><?php echo $label; ?></p>

                </div>


            </div>


        <?php }
    }else if($template_id==2){

        $color[] = "#6A4F94";
        $color[] = "#F1671C";
        $color[] = "#0090C4";
        $color[] = "#339851";
        $color[] = "#e04956";
        $color[] = "#546776";
        $color[] = "#cc2099";

        $border_color[] = "#1E4879";
        $border_color[] = "#F71739";
        $border_color[] = "#2A6892";
        $border_color[] = "#1d8b39";
        $border_color[] = "#ce3a47";
        $border_color[] = "#485a68";
        $border_color[] = "#b11483";

        ?>

        <?php

        $chunk_data = array_chunk($tracker_data,3);
        $color_count = 0;
        foreach($chunk_data as $chunk_key => $chunk_data){
            echo "<div style='display: none;' class='main_div'>";
            foreach ($chunk_data as $key => $data) {
                $doctor_image = !empty($data['doctor_image'])?$data['doctor_image']:$logo;
                ?>

                <div class="row doctor_section"  >
                    <div class="col-sm-2 col-md-2 col-lg-2 doctor_img_div" style="border-color: <?php echo $color[$color_count];?>;">
                        <img style="border-color: <?php echo $color[$color_count];?>; " class="img-circle" src="<?php echo $doctor_image; ?>"  />
                    </div>

                    <div class="col-sm-10 col-md-10 col-lg-10 second_row_box">
                        <div class="row">


                            <div class="col-sm-7 col-md-7 col-lg-7 doctor_name name_div" style="background: <?php echo $color[$color_count];?>;"><?php echo $data['doctor_name']; ?>

                                <div class="shape_div"><img src="<?php echo Router::url('/images/tracker/header_shape.png',true); ?>" ></div>
                                <div class="overlay">&nbsp;</div>
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5 doctor_name department_div" style="background: <?php echo $color[$color_count];?>;">&nbsp;</div>
                        </div>
                        <div class="row second_row">
                            <div class="col-sm-7 col-md-7 col-lg-7 second_parent_div">
                                <div class="col-sm-7 col-md-7 col-lg-7 inner_text" style="color: <?php echo $color[$color_count];?>;">

                                   <?php if(!empty($data['room_number'])){  ?>
                                                Room No <br> <span class="room_span">
                                               <?php echo $data['room_number']; ?>
                                            </span>
                                          <?php  } ?>
                                    
                                    
                                </div>
                                <div class="col-sm-5 col-md-5 col-lg-5 patient_lbl_div" style="border-color: <?php echo $color[$color_count];?>;">
                                    <!-- <div class="room_inner" style="display: none; border-color: <?php /*echo $border_color[$color_count];*/?>; background: <?php /*echo $color[$color_count];*/?>;">
                                    <img src="<?php /*echo Router::url('/images/tracker/room.png',true); */?>" >
                                </div>
                                  <?php $label = $this->AppAdmin->tracker_labels($data['patient_queue_type'],$data['sub_token']); ?>

-->                                 <?php if($show_patient_name=="YES"){ ?>
                                    <h2 class="patient_name_heading" style="color: <?php echo $color[$color_count];?>;"><label style="background: <?php echo $color[$color_count];?>;">Patient Name</label><span style="color: <?php echo $color[$color_count];?>;"> <?php echo $data['patient_name']; ?> </span></h2>
                                    <?php } ?>
                                    <?php if($show_time=="YES" && empty($label)){ ?>
                                    <h2 class="appointment_time_heading" style="color: <?php echo $color[$color_count];?>;"><label style="background: <?php echo $color[$color_count];?>;">Approx Time </label>  <span style="color: <?php echo $color[$color_count];?>;"> <?php echo ($data['sub_token']=="NO")?$data['time_slot']:''; ?> </span> </h2>
                                    <?php } ?>

                                </div>

                                <div class="corner_shape" style="background: <?php echo $color[$color_count];?>;">
                                    <img  style="width: 100%; height: 100%;  background: <?php echo $color[$color_count];?>;" src="<?php echo Router::url('/images/tracker/round_line.png',true); ?>" >
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5 third_parent_div">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 inner_text" style="color: <?php echo $color[$color_count];?>;">Current Token No.</div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 inner_text" style="color: <?php echo $color[$color_count];?>;">


                                         <?php
                                            $token_number = !empty($label)?$label:$data['token_number'];
                                            if($thin_app_id==892 && $token_number=='NOT'){
                                                $token_number = $data['queue_number'];
                                            }
                                            ?>
                                        <span class="token_span <?php echo ($data['sub_token'] =="YES")?'blink':''; ?>" ><span><?php echo ($data['sub_token'] =="YES")?'Emergency':$token_number; ?></span></span>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                <?php $color_count = (++$color_count< 7 )?$color_count:0; } echo "</div>"; ?>
        <?php } }else if($template_id==3){ ?>

        <?php foreach ($tracker_data as $key => $data){ ?>
            <div style="display: none;" id="<?php echo $data['doctor_id']; ?>" data-aea="<?php echo $data['allow_emergency_appointment']; ?>" class="main_div">
                <h2 style="display: none;">
                    <img  class ="logo_img" src="<?php echo Router::url( '/images/logo.png'); ?>">
                    <?php echo @$data['app_name']; ?>
                    <label class="app_title" >
                        <img   src="<?php echo Router::url('/images/tracker/date_icon.png'); ?>">
                        <span><?php echo date('d F, Y'); ?></span>
                    </label>
                </h2>

                <div  class="header_section">
                    <div class="doctor_part">
                        <div class="doctor_part_inner doc_img_div">
                            <?php if(!empty($data)){

                                if(!empty($data['doctor_image'])){
                                    $logo = $data['doctor_image'];
                                }

                            } ?>

                            <img  class ="doc_img" src="<?php echo $logo; ?>">
                        </div>
                        <div class="doctor_part_inner doc_name_div">
                            <h1><?php echo $data['doctor_name']; ?></h1>
                            <h3 style="font-size: 2.1rem;"><?php echo $data['doctor_category']; ?></h3>
                        </div>
                        <div class="room_number">
                            <?php echo ($thin_app_id=="821")?'Counter : ':'Room : '; ?> <?php echo @$data['room_number']; ?>
                        </div>
                    </div>
                    <div class="tracker_part">
                        <div class="tracker_title">

                            <div class="token_lbl current_title_wraper">
                                Current Token
                            </div>
                            <div class="token_lbl patient_name_lbl">
                                Next Token
                            </div>

                        </div>
                    </div>

                    <div class="tracker_detail_part">
                        <div class="detail_div  patient_detail_div">


                            <div class="detail_inner_div">

                                <label class="lbl date_div">

                                    <div  class="icon_div">
                                        <img   src="<?php echo Router::url('/images/tracker/date_icon.png'); ?>">
                                    </div>
                                    <div  class="name_value_div">
                                        <label>Appointment Date</label>
                                        <span><?php echo date('d F, Y'); ?></span>
                                    </div>


                                </label>

                                <label class="lbl time_div">


                                    <div  class="icon_div">
                                        <img   src="<?php echo Router::url('/images/tracker/time_icon.png'); ?>">
                                    </div>
                                    <div  class="name_value_div">
                                        <label>Approx Time</label>
                                        <span> <?php echo $data['time_slot']; ?></span>
                                    </div>


                                </label>
                            </div>
                            <div class="detail_inner_div"></div>

                        </div>
                        <div class="detail_div  token_detail_div">



                            <div  class="token current_div">
                                <img class="bg_image" src = "<?php echo Router::url('/images/tracker/token_border.png'); ?>">
                                <?php $label = $this->AppAdmin->tracker_labels($data['patient_queue_type'],$data['sub_token']);
                                    $token_number = empty($label)?$data['token_number']:$label;
                                 $blink_class = ($data['sub_token'] =="YES")?'blink':'';
                                ?>

                                <label class="token_number"><?php echo ($data['sub_token'] =="YES" || !empty($label))?"<span class='$blink_class' style='color:red;font-size:56px;font-weight: 600;margin-top:40px;width: 100%;display: block;'> $token_number </span>":$token_number; ?></label>
                                <p style="opacity:<?php echo ($data['next_sub_token']=="YES")?'0':($show_time=="YES" && empty($label))?'1':'0'; ?>"><?php echo @$data['time_slot']; ?></p>
                                <div class="doctor_part_inner doc_time_div">

                                    <label class="patient_name"><?php echo ($show_patient_name=="YES")?@$data['patient_name']:''; ?></label>
                                </div>
                            </div>


                            <div  class="token current_div">
                                <img class="bg_image" src = "<?php echo Router::url('/images/tracker/token_border.png'); ?>">
                                <?php $label = $this->AppAdmin->tracker_labels($data['next_patient_queue_type'],$data['next_sub_token']);
                                $next_token_number = empty($label)?$data['next_patient_token']:$label;
                                $blink_class = ($data['next_sub_token'] =="YES")?'blink':'';
                                ?>
                                <label class="token_number"><?php echo ($data['next_sub_token'] =="YES" || !empty($label))?"<span class='$blink_class' style='color:red;font-size:56px;font-weight: 600;margin-top:40px;width: 100%;display: block;'> $next_token_number </span>":$next_token_number; ?></label>

                                <p style="opacity:<?php echo ($data['next_sub_token']=="YES")?'0':($show_time=="YES" && empty($label))?'1':'0'; ?>" ><?php echo @$data['next_patient_slot']; ?></p>
                                <div class="doctor_part_inner doc_time_div">
                                    <label class="patient_name"><?php echo ($show_patient_name=="YES")?@$data['next_patient_name']:''; ?></label>
                                </div>


                            </div>





                        </div>


                    </div>

                </div>

            </div>
        <?php } ?>

        <?php }else if($template_id==4){ ?>

			 <style>
            .video_lbl{

                margin-top: 14px;
                padding: 5px 10px;
                background: red;
                color: #fff;
                font-size: 27px;
                float: right;
                right: 40%;
                text-align: center;
                width: auto !important;
                line-height: 1.2;
                border-radius: 40px;
                border: none;
                position: absolute;

            }
        </style>
         <?php  foreach ($tracker_data as $key => $data){ ?>
            <div style="display: none;" id="<?php echo $data['doctor_id']; ?>" data-aea="<?php echo $data['allow_emergency_appointment']; ?>" class="main_div">

                <div  class="header_section">
                    <div class="doctor_box">
                        <div class="powered_by">
                            <span>Powered by </span>
                            <img class="logo-mengage" src="https://mengage.in/doctor/images/logo.png">

                        </div>

                        <div class="doctor_part">
                            <div class="doctor_part_inner doc_img_div">
                                <?php if(!empty($data)){

                                    if(!empty($data['doctor_image'])){
                                        $logo = $data['doctor_image'];
                                    }

                                } ?>
                                <img  class ="doc_img" src="<?php echo $logo; ?>">
                            </div>
                            <div class="doctor_part_inner doc_name_div">
                                <label class="doctor_name"><?php echo $data['doctor_name']; ?></label>
                                <span class="department_span" style="display: none;"><?php echo $data['doctor_category']; ?></span>
                            </div>
                            <div class="room_number">
                                Room : <?php echo @$data['room_number']; ?>
                            </div>
                        </div>
                        <div class="current_box">
                            <div class="box current_patient_span" style="width: 70%;">

                                <span style="text-align: left;padding-left: 10px;"><?php echo ($show_patient_name=="YES")?@mb_strimwidth(ucwords(strtolower($data['current_patient'])), 0, 19, '.'):'-' ; ?></span>

                            </div>
                            <div class="box current_patient_time_span" style="width: 30%;">
                                <span style="width: 94%;"  class="<?php echo (@$data['sub_token'] == 'YES')?'blink':''; ?>">
                                    <?php $label = $this->AppAdmin->tracker_labels($data['patient_queue_type'],$data['sub_token']); ?>
                                    <?php

                                    if(!empty($label)){
                                        echo $label;
                                    }else{
                                         echo $data['current_token'];
                                    }
                                    ?></span>
                            </div>
                        </div>

                        <?php if(isset($data['upcoming'])){ ?>
                        <ul class="doctor_patient_list">
                            <li class="header">

								
                                <div class="patient_box"><?php echo ($show_patient_name=="YES")?'NEXT':'&nbsp;'; ?>   </div>
                                <div class="time_box"><?php echo ($show_time=="YES")?'TIME(APPROX)':'&nbsp;'; ?>    </div>
                                <div class="token_box">TOKEN</div>


                            </li>
                            <?php  foreach ($data['upcoming'] as $list_key => $upcoming){ ?>
                            <li>

									<?php if($upcoming['consulting_type']=='VIDEO'){ ?>
                                            <div class="video_lbl">Video Consulting</div>
                                        <?php } ?>
                                    <div class="patient_box"><?php echo ($show_patient_name=="YES")?mb_strimwidth(ucwords(strtolower($upcoming['patient_name'])), 0, 26, '...'):'&nbsp;'; ?></span></div>



                                <?php if( $upcoming['sub_token'] =='NO' && $upcoming['emergency_appointment'] =='NO' && ($upcoming['patient_queue_type']=='NONE' || !in_array($upcoming['patient_queue_type'],array('REPORT_CHECKIN','LATE_CHECKIN','EARLY_CHECKIN')))  ){ ?>

                                    <div class="time_box"><?php echo ($show_time=="YES" && $upcoming['sub_token'] =='NO' && $upcoming['patient_queue_type']!='LATE_CHECKIN' && $upcoming['sub_token'] =='NO')?$upcoming['slot_time']:"&nbsp;";; ?></span></div>

                                    <div class="token_box" style="padding-right: 20px;"><?php

                                        if($upcoming['has_token'] == "NO" && $upcoming['sub_token'] =='NO'){
                                            echo $upcoming['queue_number'];
                                        }else{
                                            echo $upcoming['queue_number'];
                                        }

                                        ?></div>

                                <?php }else{    $label =  $this->AppAdmin->tracker_labels($upcoming['patient_queue_type'],$upcoming['sub_token']); ?>
                                    <?php $style = (strtoupper($label) == "EMERGENCY")?"color: #fff !important;background: red;":''; ?>
                                    <?php $blink = (strtoupper($label) == "EMERGENCY")?"blink":''; ?>
                                    <div style="<?php echo $style; ?>" class="check_in_type_lbl <?php echo $blink; ?>"><?php echo $label; ?></div>
                                <?php } ?>

                            </li>
                            <?php } ?>

                        </ul>
                        <?php }else{ ?>
                            <h2 class="no_patient">No upcoming patient.</h2>
                        <?php } ?>
                    </div>


                </div>


            </div>
           <?php } ?>
        <?php } ?>

 
    
<?php }else{  ?>
        <h1 style="text-align: center;"> There is no appointment available </h1>
<?php } ?>

<script>
    $("body").data('key','<?php echo json_encode($break_array); ?>');
</script>
