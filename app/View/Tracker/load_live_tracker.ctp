<?php
$color[] = "#6A4F94";
$color[] = "#F1671C";
$color[] = "#339851";
$color[] = "#0090C4";
$color[] = "#e04956";
$color[] = "#546776";
$color[] = "#cc2099";

$border_color[] = "#4169E1";
$border_color[] = "#008000";
$border_color[] = "#1E90FF";
$border_color[] = "#32CD32";
$border_color[] = "#FF6347";
$border_color[] = "#FF8C00";
$border_color[] = "#DC143C";

?>

<?php if(!empty($againPlayVoice)){ ?>
    <span id="playAgainVoice" data-voice="<?php echo $againPlayVoice; ?>" style="display: none;" ></span>
<?php } ?>
<style>
    .service_name_heading{
        text-align: center;
        margin: 0;
        background: yellow;
        padding: 0.8rem 0rem;
        display: block;
        float: left;
        width: 10%;
    }
    .service_data_container{
        display: block;
        width: 100%;
        float: left;
        border-bottom: 2px solid silver;
    }
    .service_name{
        color: #fff;
        background: green;
    }
    .closedToken{
        color: red !important;
    }

</style>
<?php $counter = 0;
foreach ($result_array as $service_name =>$service_data){   if(count($color) ==$counter){ $counter=0; } $show_header=true; ?>

    <div  class="service_data_container">

        <div style="padding: 0;"   class="<?php echo $widthclass; ?> load_tracker_div ul_class">
            <div  class="token_li" style="padding: 4px 0px; text-align: center; width: 215px; background: <?php echo $color[$counter]; ?>;color: #fff;font-weight: 600;font-size: 2rem;height: 70px;">

                <?php echo $service_name; ?>
                <?php if(!empty($service_data['room_number'])){ ?>
                <span style="display: block;font-size: 2rem;">
                     <?php echo "(".$service_data['room_number'].")"; ?>
                </span>

                <?php } ?>
            </div>
        </div>
        <?php foreach ($service_data as $doctor_id =>$doctor_data){    $showTokenDiv =  ( $doctor_data['data']['current']['token_number'] > 0)?'block':'none';         $lg = (count($result_array) > 4 )?'6':'12';  ?>

            <div style="padding: 0;"  data-bg="<?php echo $color[$counter]; ?>" class="load_tracker_div ul_class">
                <div  class="token_li doctor_box_<?php echo $doctor_data['service_id']; ?>" data-voice="<?php echo (array_key_exists($doctor_id,$voice_array))?$voice_array[$doctor_id]:''; ?>" id="<?php echo $doctor_id; ?>" style="border:1px solid <?php echo $color[$counter]; ?>; display: <?php echo $showTokenDiv ?>; ">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <h6 class="service_name" style="background: <?php echo $color[$counter]; ?>"><?php echo $doctor_data['doctor_name']; ?></h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tokenColor = $color[$counter];
                                $closedClass = "";
                                if($doctor_data['data']['current']['token_number']=='Closed'){
                                    $closedClass = "closedToken";
                                }
                                if($doctor_data['data']['current']['sub_token']=='NO'){ ?>
                                    <label class="token_num_lbl_<?php echo $doctor_id; ?> token_number <?php echo $closedClass; ?>" style="<?php echo $style; ?> color: <?php echo $color[$counter]; ?> !important;"><?php
                                        if(!empty($doctor_data['data']['current']['token_number'])){
                                            echo $doctor_data['data']['current']['token_number'];
                                        }else{
                                            echo 'OPEN';
                                        }
                                        ?></label>
                                <?php }else{
                                    if($doctor_data['data']['current']['sub_token']=="YES"){
                                        $remark = !empty($doctor_data['data']['current']['remark'])?$doctor_data['data']['current']['remark']:"EMERGENCY";
                                        ?>
                                        <span class="blink" ><?php echo ucfirst(strtolower($remark)); ?></span>
                                    <?php }else{ ?>
                                        <label class="token_number closedToken" style="<?php echo $style; ?> color: <?php echo $tokenColor; ?>"><?php echo "Closed" ?></label>
                                    <?php } ?>

                                <?php }  ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php  } ?>
    </div>

    <?php $counter++; } ?>




