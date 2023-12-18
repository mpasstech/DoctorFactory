 <?php
 $color[] = "#6A4F94";
        $color[] = "#F1671C";

        $color[] = "#339851";
 $color[] = "#0090C4";
        $color[] = "#e04956";
        $color[] = "#546776";
        $color[] = "#cc2099";

        /*$border_color[] = "#1E4879";
        $border_color[] = "#F71739";
        $border_color[] = "#2A6892";
        $border_color[] = "#1d8b39";
        $border_color[] = "#ce3a47";
        $border_color[] = "#485a68";
        $border_color[] = "#b11483";*/


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

 <?php $counter = 0;
 foreach ($result_array as $doctor_id =>$doctor_data){  $lg = (count($result_array) > 4 )?'6':'12';  ?>
     <div style="padding: 0;"  class="load_tracker_div col-xs-12 col-sm-12 col-md-12 col-lg-12 ul_class">

             <div  class="token_li" data-voice="<?php echo (array_key_exists($doctor_id,$voice_array))?$voice_array[$doctor_id]:''; ?>" id="<?php echo $doctor_id; ?>" style="border-color: <?php echo $border_color[$counter]; ?>; background: <?php echo $border_color[$counter]; ?>; color: #fff;">
                <table style="width: 100%;">
                    <tr>

                        <td style="width: 10%;">

                            <img class="doctor_profile" src="<?php echo $doctor_data['profile_photo']; ?>">
                        </td>
                        <td style="width: 80%; text-align: left;">
                            <h6 class="service_name"><?php echo $doctor_data['doctor_name']; ?></h6>
                        </td>

                        <td style="text-align: center;">
                            <?php if($doctor_data['data']['current']['sub_token']=='NO'){  ?>

                                <label class="token_number" style="color: <?php echo $border_color[$counter]; ?>;"><?php
                                    if(!empty($doctor_data['data']['current']['token_number'])){
                                        if(false){
                                            $batchCount = 5;
                                            $endNumber = 0;
                                            $currentToken = $doctor_data['data']['current']['token_number'];
                                            for($counter=$batchCount; $counter<=200;){
                                                if($currentToken <= $counter){
                                                    $endNumber = $counter;
                                                    break;
                                                }else{
                                                    $counter = $counter+$batchCount;
                                                }
                                            }
                                            $tokenArray =array();
                                            for($counter=$currentToken; $counter<=$endNumber;$counter++){
                                                $tokenArray[] = $counter;
                                            }
                                            echo implode(",",$tokenArray);
                                        }else{
                                            echo $doctor_data['data']['current']['token_number'];
                                        }
                                    }else{
                                        echo '-';
                                    }
                                    ?></label>
                            <?php }else{   $remark = !empty($doctor_data['data']['current']['remark'])?$doctor_data['data']['current']['remark']:"EMERGENCY";  ?>
                                <span class="blink" ><?php echo ucfirst(strtolower($remark)); ?></span>
                            <?php }  ?>
                        </td>
                    </tr>
                </table>

             </div>


     </div>
     <?php $counter++; }
 ?>

