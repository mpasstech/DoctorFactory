<?php if(!empty($doctor_data)){ foreach ($doctor_data as $category_name => $doctor_list){ ?>
 <div class="category_div">


<h3 class="category_title"><?php

    $tmp = explode("##",$category_name);
    $category_name = $tmp[1];
    $cat_id = $tmp[0];

    echo $category_name; if(count($doctor_list) >= 7){ echo "<button data-c='".$cat_id."' class='btn btn-success btn-xs show_all'>Show All</button>"; } ?> </h3>
<?php foreach ($doctor_list  as $key => $doctor){


    $city = $doctor['city_name'];
    $state = $doctor['state_name'];
    $doctor_name = $doctor['name'];

    $doctor_id = base64_encode($doctor['id']);
    $url = Router::url('/doctor/index/',true);
    if(!empty($state)){
        $url .= '/'.strtolower(str_replace(' ','-',$state));
    }
    if(!empty($city)){
        $url .= '/'.strtolower(str_replace(' ','-',$city));
    }

    if(!empty($category_name)){
        $url .= '/'.strtolower(str_replace(' ','-',$category_name));
    }
    if(!empty($doctor_name)){
        $url .= '/'.strtolower(str_replace(' ','-',$doctor_name));
    }

    $url .= "/?t=".$doctor_id;

    ?>

    <div data-url="<?php echo $url; ?>" class="col-sm-2 doctor_div_section" >

        <?php $time_string = $doctor['available']; ?>

        <?php if(!empty($time_string)){
                $color = "#689F38";
                $tool_msg = "Doctor Available";
        }else{
               $color = "#ED3B3B";
            $tool_msg = "Doctor Not Available";
        } ?>

        <a class="doctor_img_box" href="javascript:void(0);" >

            <?php

            $path = $doctor['profile_photo'];
            if(empty($path)){
                $path = $doctor['logo'];
            }
            ?>
            <img alt="image" class="img-responsive" src="<?php echo $path; ?>">
        </a>

        <lable class="doctor_name_box"> <a class="box_doc_name_link" title="<?php echo $doctor['name']; ?>" href="javascript:void(0);" ><?php echo mb_strimwidth(ucwords(strtolower($doctor['name'])), 0, 25, ".."); ?> </a></lable>
        <ul title="Total Downloads" class="total_downloads">
            <li class="user-icn">

                <i class="fa fa-user"> <?php echo $doctor['downloads']; ?> </i>
            </li>
            <li class="online-icn">
                <span  title="<?php echo $tool_msg; ?>" style="cursor:pointer;background:<?php echo $color; ?>;" class="online_status"></span>
            </li>

        </ul>
        <p class="app_name_para"><span class="box_app_name"><i class="fa fa-building"></i> <?php echo mb_strimwidth(ucwords(strtolower($doctor['app_name'])), 0, 22, ".."); ?></span></p>



    </div>

<?php } ?>
 </div>
<?php }}else{ ?>
    <h3 class="no_doctor_available">No Doctor available for selected category.</h3>
<?php } ?>
