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
<style>
    .service_name_heading{
        text-align: center;
        margin: 0;
        background: yellow;
        padding: 0.8rem 0rem;
        display: block;
        float: left;
        width: 100%;
        font-size: 1.8rem;
    }
    .service_data_container{
        display: block;
        width: 100%;
        float: left;
    }
    .service_name{
        color: #000 !important;
    }
    .closedToken{
        color: red !important;
    }

</style>
<?php $counter = 0;

    $cookies_name = "tracker_display_".$thin_app_id;
    $counter_name_font_size = "3rem";
    $token_font_size ="3rem";
    $category_font_size="1.8rem";
    if(isset($_COOKIE[$cookies_name])){
        $setting = json_decode($_COOKIE[$cookies_name],true);
        $counter_name_font_size = $setting["counter_name_font_size"];
        $token_font_size =$setting["token_font_size"];
        $category_font_size=$setting["category_font_size"];
    }


    foreach ($result_array as $service_name =>$service_data){ $show_header=true; ?>

        <div  class="service_data_container">
             <?php if(count($result_array) > 1 && !empty($service_name)){ ?>
                <h3 style="font-size:<?php echo $category_font_size; ?>;" class="service_name_heading"><?php echo $service_name; ?></h3>
            <?php } ?>

                


            <?php foreach ($service_data as $doctor_id =>$doctor_data){ if(count($border_color) ==$counter){ $counter=0; }   $lg = (count($result_array) > 4 )?'6':'12';  ?>

                
                
                
                <?php
						    $widthclass = "";
                            $style = "margin:0; font-size:$counter_name_font_size;";
                            $tokenStyle = "margin:0; font-size:$token_font_size;";
                            
                        ?>
                

                <div style="padding: 0;"  class="<?php echo $widthclass; ?> load_tracker_div col-xs-12 col-sm-12 col-md-12 col-lg-<?php echo ($thin_app_id==EHCC_APP_ID)?"6":"12"; ?> ul_class">
                    <div  class="token_li" data-voice="<?php echo (array_key_exists($doctor_id,$voice_array))?$voice_array[$doctor_id]:''; ?>" id="<?php echo $doctor_id; ?>" style="border:3px solid #0973B6; background: #F1FAFF; color: #fff;">
                        <table style="width: 100%;">
                            <tr>



                                
                                <td style="width:80%;text-align: left;">
                                    <h6 class="service_name" style="<?php echo $style; ?>"><?php echo $doctor_data['doctor_name']; ?></h6>
                                    <?php if($thin_app_id == 607){
                                        $pat =$doctor_data['data']["current"]['patient_name'];
                                        if(strpos(strtolower($pat), "patient") !== false){
                                            $pat = "";
                                        }

                                        ?>
                                        <h6 class="service_name" style="color:red !important; <?php echo $style; ?>">Patient :- <span class="patient_name"><?php echo $pat; ?></span> </h6>
                                    <?php } ?>

                                </td>

                                <td style="width:20%;text-align: right;">
                                    <?php
                                    $tokenColor = "#000;";
                                    $closedClass = "";
                                    if($doctor_data['data']['current']['token_number']=='Closed'){
                                        $closedClass = "closedToken";
                                    }

                                    if($doctor_data['data']['current']['sub_token']=='NO'){ ?>

                                        <label class="token_number <?php echo $closedClass; ?>" style="<?php echo $tokenStyle; ?> color: <?php echo $tokenColor; ?>;"><?php
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
                                            <label class="token_number closedToken" style="<?php echo $tokenStyle; ?> color: <?php echo $tokenColor; ?>"><?php echo "Closed" ?></label>
                                        <?php } ?>

                                    <?php }  ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <?php if($thin_app_id == 607){ ?>
                        <h3 style="text-align: center;width: 100%;">Upcoming Patient List</h3>
                        <ul class="upcoming_patient_ul">

                        </ul>
                    <?php } ?>

                </div>
            <?php  } ?>
        </div>

        <?php $counter++; }

    if($patient_tracker ='yes' && empty($result_array)){ ?>

        <label style="    display: block;
    width: 100%;
    float: left;
    text-align: center;
    margin: 1rem 0;
    font-weight: 500;
    padding: 0.5rem;">
            Your token is in queue. Click on below refresh button to check your token status
            <button style="display: block;margin: 1rem auto;" onClick='window.location.reload();'><i class="fa fa-refresh"></i> Refresh </button>
        </label>

   <?php }

?>




