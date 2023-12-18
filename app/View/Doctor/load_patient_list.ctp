<style>

    .consulting_type{
        font-size: 0.7rem;
        margin: 3%;
        width: 24%;
        text-align: center;
        background: #329813;
        position: absolute;
        left: -10px;
        padding: 1%;
        top: 15%;
        border-radius: 0px 20px 20px 0px;
        color: #fff;
        font-weight: 600;
    }

    .app_seccond_row_tr .token_number{
        text-align: center;
        display: block;
        border-radius: 16%;
        border: 1px solid;
        height: 50px;
        width: 60px;
        margin: 5% auto;
        font-size: 1.5rem;
        padding: 7%;
        font-weight: 600;
        color: #006a11;
    }
    .button_box_tr .app_action_btn{
        float: left;
        width: auto;

        background: blue;
        border: none;
        color: #fff;
        margin: 6px 1%;
        border-radius: 3px;
        padding: 1% 2%;

    }
    .button_box_tr{
        border-top: 1px solid #aeaeae;

    }
    .top_row_tr{
        border-bottom: 1px solid #aeaeae;
    }
    .date_row_tr td{
        padding: 0.5rem 0;

    }



    .app_seccond_row_tr td{
        padding: 0.8rem 0;
    }

    .top_row_tr td{
        text-align: center;
    }
    .top_row_tr td lable{
        display: block !important;
        font-weight: 600 !important;
        width: 100% !important;
        font-size: 0.9rem !important;
    }

    .reason_data_tr td{
        padding: 0.6rem 0px;
    }



    div[class^="html5gallery-elem-"]{
        box-shadow: none !important;
    }

    .card-body div[class^="col-"]{
        margin: 0;
        padding: 0;
    }
    .app_top_header div[class^="col-"]{
        margin: 0;
        padding: 6px 0px;
        border-bottom: 1px solid;
        text-align: center !important;
    }

    .app_footer div[class^="col-"]{
        margin: 0;
        padding: 6px 0px;
        text-align: center !important;
    }

    .app_footer{

    }
    .app_footer button{
        background: none;
        color: #2f920e;
        border: none;
        font-weight: 600;
    }

    .app_body label{
        font-weight: 600;
        display: block;
        width: 100%;
    }
    .app_body span{
        font-weight: 400;
    }
    .card_main_container .card{
        padding: 5px;
        margin: 5px;
        box-shadow: 0px 4px 10px #cecece;
    }

    .app_top_header{
        background: none;
        margin: 0;
        padding: 0;
        color: #000;
        border: none;
    }


    .blog-desc label{
        color: #686df7;
        font-weight: 100;
        font-style: normal;
        font-size: 13px;
    } .blog-desc p{
          margin: 0 0 4px;
      }
    .card_main_container  .card-body{
        padding: 6px 5px;
    }
    .blog-img{
        text-align: center;
    }


    div[class^="html5gallery-elem-"]{
        box-shadow: none !important;
    }

    .blog-desc label{
        color: #686df7;
        font-weight: 100;
        font-style: normal;
        font-size: 13px;
    } .blog-desc p{
          margin: 0 0 4px;
      }
    .blog-img{
        text-align: center;
    }

    .sub_detail{
        font-size: 0.9rem;
        display: block;
        width: 100%;
        padding: 4px 0px;
    }

</style>

<div class="col-12 card-deck card_main_container">
    <?php if(!empty($patientData)){ foreach($patientData as $key => $val) { ?>
    <div class="card">
        <div class="card-body">

            <table style="width: 100%;">

                <tr class="app_seccond_row_tr">
                    <td style="width: 25%;">
                        <?php

                        $patient_photo = Router::url("/img/profile.png",true);
                        if(!empty($val['patient_photo'])){
                            $patient_photo =$val['patient_photo'];
                        }
                        ?>
                        <img src="<?php echo $patient_photo; ?>" />
                    </td>
                    <td>
                        <h5><i class="fa fa-user"></i> <?php echo $val['patient_name']; ?></h5>
                        <label class="sub_detail"><i class="fa fa-phone"></i> <?php echo $val['patient_mobile']; ?></label>
                        <label class="sub_detail"><i class="fa fa-credit-card"></i> <?php echo $val['uhid']; ?></label>
                        

                        <?php if(!empty($val['gender'])){ ?>
                            <label class="sub_detail"><i class="fa fa-genderless"></i>  <span class="app_list_status"><?php echo $val['gender']; ?></span></label>
                        <?php } ?>

                        <?php if(!empty($val['address'])){ ?>
                            <label class="sub_detail"><i class="fa fa-map-pin"></i>  <span class="app_list_status"><?php echo $val['address']; ?></span></label>
                        <?php } ?>


                    </td>

                </tr>




                <tr class="button_box_tr">
                    <td  colspan="2">

                       <?php if($val['folder_id']){  ?>
                            <button type="button" class="app_action_btn patient_medical_record" data-name="<?php echo $val['patient_name'];?>"  data-fi="<?php echo base64_encode($val['folder_id']);?>" ><i class="fa fa fa-file-text-o"></i> Records</button>
                        <?php } ?>

                        <a href="tel:<?php echo $val['patient_mobile']; ?>" target="_blank" class="app_action_btn audio_btn"><i class="fa fa-phone" aria-hidden="true"></i> Call</a>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo $val['patient_mobile']; ?>" target="_blank" class="app_action_btn video_btn"><i class="fa fa-whatsapp" aria-hidden="true"></i> Chat</a>


                    </td>
                </tr>


            </table>
        </div>
    </div>
    <?php }}else{
        if($offset==0){
            $message = "Patient not found";
        }else{ ?>
            <?php $message="No more patient found";
        }
    ?>
    <h2 class="doc_not_available"><?php echo $message; ?></h2>

    <?php } ?>
</div>