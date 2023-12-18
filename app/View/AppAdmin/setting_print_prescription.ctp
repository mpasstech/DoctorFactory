<?php
$login = $this->Session->read('Auth.User');
$left = $tb_left = '0%';
$top = '0%';
$tb_top= '20%';
$width = $tb_width= '60%';
$height = '8%';
$fontSize = $tb_text_font_size= $tb_label_font_size= $tb_heading_font_size= '13px';
$fields = $data =array();
$barcode = 'NO';
$background_img = "";
$header_box_border = $tag_box_border = "2px solid";
$header_rotate = $tb_rotate = 0;
$field_layout_type = "ORDER_LIST";
$patient_detail_box = "YES";
if (isset($availableData['SettingWebPrescription']) && !empty($availableData['SettingWebPrescription'])) {

    $data = $availableData['SettingWebPrescription'];
    $field_layout_type = $availableData['SettingWebPrescription']['field_layout_type'];
    $patient_detail_box = $availableData['SettingWebPrescription']['patient_detail_box'];

    $left = $data['left'];
    $top = $data['top'];
    $width = $data['width'];
    $height = $data['height'];
    $fontSize = $data['font_size'];
    $barcode = $data['barcode'];


    $tb_left = $data['tb_left'];
    $tb_top = !empty($data['tb_top'])?$data['tb_top']:$tb_top;
    $tb_width = $data['tb_width'];

    $tb_text_font_size = !empty($data['tb_text_font_size'])?$data['tb_text_font_size']:$tb_text_font_size;
    $tb_label_font_size = !empty($data['tb_label_font_size'])?$data['tb_label_font_size']:$tb_label_font_size;
    $tb_heading_font_size = !empty($data['tb_heading_font_size'])?$data['tb_heading_font_size']:$tb_heading_font_size;
    $header_box_border = !empty($data['header_box_border'])?$data['header_box_border']:$header_box_border;
    $tag_box_border = !empty($data['tag_box_border'])?$data['tag_box_border']:$tag_box_border;

    $header_rotate = !empty($data['header_rotate'])?$data['header_rotate']:$header_rotate;
    $tb_rotate = !empty($data['tb_rotate'])?$data['tb_rotate']:$tb_rotate;




    $prescription = $data['prescription'];
    if(!empty($prescription)){
        $background_img = "background-image:url('".$prescription."');";
    }


    if(!empty($availableData['SettingWebPrescription']['fields']))
    {
        $fields = json_decode($availableData['SettingWebPrescription']['fields'],true);
        uasort($fields, function($item1, $item2){
            if ($item1['order'] == $item2['order']) return 0;
            return $item1['order'] < $item2['order'] ? -1 : 1;
        });
    }

}

$default_field_values = array(
    'uhid'=>'20181215',
    'patient_name'=>'John Doe',
    'parents_name'=>'Madelyn Duke',
    'parents_mobile'=>'9999999999',
    'age'=>'28 Years',
    'email'=>'testmail@gmail.com',
    'gender'=>'Male',
    'address'=>'222, test nagar jaipur',
    'patient_address'=>'222, test nagar jaipur',
    'queue_number'=>'51',
    'appointment_datetime'=>date('d-m-Y'),
    'receipt_datetime'=>date('d-m-Y H:i'),
    'slot_time'=>date('h:i A'),
    'dob'=>date('d-m-Y'),
    'weight'=>'12',
    'blood_group'=>'O+',
    'mobile'=>'+919999999999',
    'amount'=>'<i class="fa fa-inr" aria-hidden="true"></i> 500',
    'patient_height'=>'5',
    'bp_systolic'=>'2',
    'bp_diastolic'=>'5',
    'marital_status'=>'Married',
    'bmi'=>'9',
    'bmi_status'=>'Good',
    'notes'=>'Remark',
    'reason_of_appointment'=>'Fever',
    'third_party_uhid'=>'D3567',
    'referred_by'=>'Refer by',
    'referred_by_mobile'=>'9999999999',
    'doctor_name'=>'Dr. Mahendra Saini',
    'temperature'=>'12',
    'o_saturation'=>'4',
    'city_name'=>'Jaipur',
    'country_id'=>'India',
    'city_id'=>'Jaipur',
    'state_id'=>'Rajasthan',
    'service_validity_time'=>'2 Day(s)',
    'doctor name'=>'Dr Joey',
    'token time'=>'12 Sep 2019 03:46 PM',
    'head_circumference'=>'98 CM',
    'receipt_datetime'=>'17 Mar 2019 03:15 PM',
    'field1'=>'field1',
    'field2'=>'field2',
    'field3'=>'field3',
    'field4'=>'field4',
    'field5'=>'field5',
    'field6'=>'field6',

);



?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseurl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php  echo $this->Html->script(array('jquery.js','printThis.js','JsBarcode.all.min')); ?>
    <?php  echo $this->Html->script(array('jquery.js','printThis.js')); ?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<?php  echo $this->Html->css(array('font-awesome.min.css')); ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>Setting Print Prescription</title>
</head>
<body>

<div class="saveText">
    <h2 style="margin-bottom:0px; border-bottom: 1px solid;">Edit Options</h2>
    <ul>
        <li>
            <label>Upload Prescription</label>
            <span class="a4_label">Upload only A4 size prescription </span>
            <input type="file" name="file" class="upload_prescription">

            <a style="display:<?php echo !empty($background_img)?'block':'none'; ?>" href="javascript:void(0);" class="remove_prescription">Remove Prescription</a>

        </li>
        <li> <label>Barcode</label>
            <select id="barcode_btn">
                <option value="NO">NO</option>
                <option value="YES">YES</option>
            </select>
        </li>




       <!-- <li> <label>Header Box Setting</label>
           <button class="btn btn-info show_header_btn btn-xs" style="float: right;"><i class="fa fa-edit"></i> Edit</button>
        </li>
-->

         <li>
            <h3 class="sub_heading">Patient Box Setting</h3>
            <ul class="inner_url">

                <li style="display: <?php echo ($setting_for=='WEB_PRESCRIPTION')?'block':'none'; ?>;">
                    <span>Show Patient Detail</span>
                    <select id="show_patient_detail">
                        <option value="YES" <?=($patient_detail_box=="YES")?"selected":'';?>>Yes</option>
                        <option value="NO" <?=($patient_detail_box=="NO")?"selected":'';?>>No</option>
                    </select>
                </li>


                <li> <span>Column Layout</span>
                    <select id="field_layout_type">
                        <option value="TABLE" <?=($field_layout_type=="TABLE")?"selected":'';?>>Fixed Column</option>
                        <option value="ORDER_LIST" <?=($field_layout_type=="ORDER_LIST")?"selected":'';?>>Auto Adjust</option>
                    </select>
                </li>

                <li> <span > Select Font Size: </span>
                    <select id="fontSize" name="fontSize">
                        <option value="1px" <?php echo ($fontSize == '1px')? "selected":""; ?> >1</option>
                        <option value="1.5px" <?php echo ($fontSize == '1.5px')? "selected":""; ?> >1.5</option>
                        <option value="2px" <?php echo ($fontSize == '2px')? "selected":""; ?> >2</option>
                        <option value="2.5px" <?php echo ($fontSize == '2.5px')? "selected":""; ?> >2.5</option>
                        <option value="3px" <?php echo ($fontSize == '3px')? "selected":""; ?> >3</option>
                        <option value="3.5px" <?php echo ($fontSize == '3.5px')? "selected":""; ?> >3.5</option>
                        <option value="4px" <?php echo ($fontSize == '4px')? "selected":""; ?> >4</option>
                        <option value="4.5px" <?php echo ($fontSize == '4.5px')? "selected":""; ?> >4.5</option>
                        <option value="5px" <?php echo ($fontSize == '5px')? "selected":""; ?> >5</option>
                        <option value="5.5px" <?php echo ($fontSize == '5.5px')? "selected":""; ?> >5.5</option>
                        <option value="6px" <?php echo ($fontSize == '6px')? "selected":""; ?> >6</option>
                        <option value="6.5px" <?php echo ($fontSize == '6.5px')? "selected":""; ?> >6.5</option>
                        <option value="7px" <?php echo ($fontSize == '7px')? "selected":""; ?> >7</option>
                        <option value="7.5px" <?php echo ($fontSize == '7.5px')? "selected":""; ?> >7.5</option>
                        <option value="8px" <?php echo ($fontSize == '8px')? "selected":""; ?> >8</option>
                        <option value="8.5px" <?php echo ($fontSize == '8.5px')? "selected":""; ?> >8.5</option>
                        <option value="9px" <?php echo ($fontSize == '9px')? "selected":""; ?> >9</option>
                        <option value="9.5px" <?php echo ($fontSize == '9.5px')? "selected":""; ?> >9.5</option>
                        <option value="10px" <?php echo ($fontSize == '10px')? "selected":""; ?> >10</option>
                        <option value="10.5px" <?php echo ($fontSize == '10.5px')? "selected":""; ?> >10.5</option>
                        <option value="11px" <?php echo ($fontSize == '11px')? "selected":""; ?> >11</option>
                        <option value="11.5px" <?php echo ($fontSize == '11.5px')? "selected":""; ?> >11.5</option>
                        <option value="12px" <?php echo ($fontSize == '12px')? "selected":""; ?> >12</option>
                        <option value="12.5px" <?php echo ($fontSize == '12.5px')? "selected":""; ?> >12.5</option>
                        <option value="13px" <?php echo ($fontSize == '13px')? "selected":""; ?> >13</option>
                        <option value="13.5px" <?php echo ($fontSize == '13.5px')? "selected":""; ?> >13.5</option>
                        <option value="14px" <?php echo ($fontSize == '14px')? "selected":""; ?> >14</option>
                        <option value="14.5px" <?php echo ($fontSize == '14.5px')? "selected":""; ?> >14.5</option>
                        <option value="15px" <?php echo ($fontSize == '15px')? "selected":""; ?> >15</option>
                        <option value="15.5px" <?php echo ($fontSize == '15.5px')? "selected":""; ?> >15.5</option>
                        <option value="16px" <?php echo ($fontSize == '16px')? "selected":""; ?> >16</option>
                        <option value="16.5px" <?php echo ($fontSize == '16.5px')? "selected":""; ?> >16.5</option>
                        <option value="17px" <?php echo ($fontSize == '17px')? "selected":""; ?> >17</option>
                        <option value="17.5px" <?php echo ($fontSize == '17.5px')? "selected":""; ?> >17.5</option>
                        <option value="18px" <?php echo ($fontSize == '18px')? "selected":""; ?> >18</option>
                        <option value="18.5px" <?php echo ($fontSize == '18.5px')? "selected":""; ?> >18.5</option>
                        <option value="19px" <?php echo ($fontSize == '19px')? "selected":""; ?> >19</option>
                        <option value="19.5px" <?php echo ($fontSize == '19.5px')? "selected":""; ?> >19.5</option>
                        <option value="20px" <?php echo ($fontSize == '20px')? "selected":""; ?> >20</option>
                        <option value="20.5px" <?php echo ($fontSize == '20.5px')? "selected":""; ?> >20.5</option>
                    </select></li>

                <li> <span > Box Border: </span>
                    <select id="header_box_border">
                        <option value="0px solid" <?php echo ($header_box_border == '0px solid')? "selected":""; ?> >0</option>
                        <option value="1px solid" <?php echo ($header_box_border == '1px solid')? "selected":""; ?> >1</option>
                        <option value="2px solid" <?php echo ($header_box_border == '2px solid')? "selected":""; ?> >2</option>
                        <option value="3px solid" <?php echo ($header_box_border == '3px solid')? "selected":""; ?> >3</option>
                        <option value="4px solid" <?php echo ($header_box_border == '4px solid')? "selected":""; ?> >4</option>
                        <option value="5px solid" <?php echo ($header_box_border == '5px solid')? "selected":""; ?> >5</option>
                    </select>
                </li>

                <li> <label>Rotate</label>
                    <select id="header_rotate">
                        <option value="0">0 Degree</option>
                        <option value="90">90 Degree</option>
                        <option value="180">180 Degree</option>
                        <option value="270">270 Degree</option>

                    </select>
                </li>
            </ul>
        </li>


         <li style="display: <?php echo ($setting_for == "WEB_PRESCRIPTION")?'block':'none';?>">
            <h3 class="sub_heading">Tag Box Setting</h3>
            <ul class="inner_url">

                <li> <span > Box Border: </span>
                    <select id="tag_box_border">
                        <option value="0px solid" <?php echo ($tag_box_border == '0px solid')? "selected":""; ?> >0</option>
                        <option value="1px solid" <?php echo ($tag_box_border == '1px solid')? "selected":""; ?> >1</option>
                        <option value="2px solid" <?php echo ($tag_box_border == '2px solid')? "selected":""; ?> >2</option>
                        <option value="3px solid" <?php echo ($tag_box_border == '3px solid')? "selected":""; ?> >3</option>
                        <option value="4px solid" <?php echo ($tag_box_border == '4px solid')? "selected":""; ?> >4</option>
                        <option value="5px solid" <?php echo ($tag_box_border == '5px solid')? "selected":""; ?> >5</option>
                    </select>
                </li>

                <li> <span >Heading Font Size: </span>
                    <select id="tb_heading_font">
                        <option value="1px" <?php echo ($tb_heading_font_size == '1px')? "selected":""; ?> >1</option>
                        <option value="1.5px" <?php echo ($tb_heading_font_size == '1.5px')? "selected":""; ?> >1.5</option>
                        <option value="2px" <?php echo ($tb_heading_font_size == '2px')? "selected":""; ?> >2</option>
                        <option value="2.5px" <?php echo ($tb_heading_font_size == '2.5px')? "selected":""; ?> >2.5</option>
                        <option value="3px" <?php echo ($tb_heading_font_size == '3px')? "selected":""; ?> >3</option>
                        <option value="3.5px" <?php echo ($tb_heading_font_size == '3.5px')? "selected":""; ?> >3.5</option>
                        <option value="4px" <?php echo ($tb_heading_font_size == '4px')? "selected":""; ?> >4</option>
                        <option value="4.5px" <?php echo ($tb_heading_font_size == '4.5px')? "selected":""; ?> >4.5</option>
                        <option value="5px" <?php echo ($tb_heading_font_size == '5px')? "selected":""; ?> >5</option>
                        <option value="5.5px" <?php echo ($tb_heading_font_size == '5.5px')? "selected":""; ?> >5.5</option>
                        <option value="6px" <?php echo ($tb_heading_font_size == '6px')? "selected":""; ?> >6</option>
                        <option value="6.5px" <?php echo ($tb_heading_font_size == '6.5px')? "selected":""; ?> >6.5</option>
                        <option value="7px" <?php echo ($tb_heading_font_size == '7px')? "selected":""; ?> >7</option>
                        <option value="7.5px" <?php echo ($tb_heading_font_size == '7.5px')? "selected":""; ?> >7.5</option>
                        <option value="8px" <?php echo ($tb_heading_font_size == '8px')? "selected":""; ?> >8</option>
                        <option value="8.5px" <?php echo ($tb_heading_font_size == '8.5px')? "selected":""; ?> >8.5</option>
                        <option value="9px" <?php echo ($tb_heading_font_size == '9px')? "selected":""; ?> >9</option>
                        <option value="9.5px" <?php echo ($tb_heading_font_size == '9.5px')? "selected":""; ?> >9.5</option>
                        <option value="10px" <?php echo ($tb_heading_font_size == '10px')? "selected":""; ?> >10</option>
                        <option value="10.5px" <?php echo ($tb_heading_font_size == '10.5px')? "selected":""; ?> >10.5</option>
                        <option value="11px" <?php echo ($tb_heading_font_size == '11px')? "selected":""; ?> >11</option>
                        <option value="11.5px" <?php echo ($tb_heading_font_size == '11.5px')? "selected":""; ?> >11.5</option>
                        <option value="12px" <?php echo ($tb_heading_font_size == '12px')? "selected":""; ?> >12</option>
                        <option value="12.5px" <?php echo ($tb_heading_font_size == '12.5px')? "selected":""; ?> >12.5</option>
                        <option value="13px" <?php echo ($tb_heading_font_size == '13px')? "selected":""; ?> >13</option>
                        <option value="13.5px" <?php echo ($tb_heading_font_size == '13.5px')? "selected":""; ?> >13.5</option>
                        <option value="14px" <?php echo ($tb_heading_font_size == '14px')? "selected":""; ?> >14</option>
                        <option value="14.5px" <?php echo ($tb_heading_font_size == '14.5px')? "selected":""; ?> >14.5</option>
                        <option value="15px" <?php echo ($tb_heading_font_size == '15px')? "selected":""; ?> >15</option>
                        <option value="15.5px" <?php echo ($tb_heading_font_size == '15.5px')? "selected":""; ?> >15.5</option>
                        <option value="16px" <?php echo ($tb_heading_font_size == '16px')? "selected":""; ?> >16</option>
                        <option value="16.5px" <?php echo ($tb_heading_font_size == '16.5px')? "selected":""; ?> >16.5</option>
                        <option value="17px" <?php echo ($tb_heading_font_size == '17px')? "selected":""; ?> >17</option>
                        <option value="17.5px" <?php echo ($tb_heading_font_size == '17.5px')? "selected":""; ?> >17.5</option>
                        <option value="18px" <?php echo ($tb_heading_font_size == '18px')? "selected":""; ?> >18</option>
                        <option value="18.5px" <?php echo ($tb_heading_font_size == '18.5px')? "selected":""; ?> >18.5</option>
                        <option value="19px" <?php echo ($tb_heading_font_size == '19px')? "selected":""; ?> >19</option>
                        <option value="19.5px" <?php echo ($tb_heading_font_size == '19.5px')? "selected":""; ?> >19.5</option>
                        <option value="20px" <?php echo ($tb_heading_font_size == '20px')? "selected":""; ?> >20</option>
                        <option value="20.5px" <?php echo ($tb_heading_font_size == '20.5px')? "selected":""; ?> >20.5</option>
                    </select>
                </li>
                <li> <span >Label Font Size: </span>
                    <select id="tb_label_font">
                        <option value="1px" <?php echo ($tb_label_font_size == '1px')? "selected":""; ?> >1</option>
                        <option value="1.5px" <?php echo ($tb_label_font_size == '1.5px')? "selected":""; ?> >1.5</option>
                        <option value="2px" <?php echo ($tb_label_font_size == '2px')? "selected":""; ?> >2</option>
                        <option value="2.5px" <?php echo ($tb_label_font_size == '2.5px')? "selected":""; ?> >2.5</option>
                        <option value="3px" <?php echo ($tb_label_font_size == '3px')? "selected":""; ?> >3</option>
                        <option value="3.5px" <?php echo ($tb_label_font_size == '3.5px')? "selected":""; ?> >3.5</option>
                        <option value="4px" <?php echo ($tb_label_font_size == '4px')? "selected":""; ?> >4</option>
                        <option value="4.5px" <?php echo ($tb_label_font_size == '4.5px')? "selected":""; ?> >4.5</option>
                        <option value="5px" <?php echo ($tb_label_font_size == '5px')? "selected":""; ?> >5</option>
                        <option value="5.5px" <?php echo ($tb_label_font_size == '5.5px')? "selected":""; ?> >5.5</option>
                        <option value="6px" <?php echo ($tb_label_font_size == '6px')? "selected":""; ?> >6</option>
                        <option value="6.5px" <?php echo ($tb_label_font_size == '6.5px')? "selected":""; ?> >6.5</option>
                        <option value="7px" <?php echo ($tb_label_font_size == '7px')? "selected":""; ?> >7</option>
                        <option value="7.5px" <?php echo ($tb_label_font_size == '7.5px')? "selected":""; ?> >7.5</option>
                        <option value="8px" <?php echo ($tb_label_font_size == '8px')? "selected":""; ?> >8</option>
                        <option value="8.5px" <?php echo ($tb_label_font_size == '8.5px')? "selected":""; ?> >8.5</option>
                        <option value="9px" <?php echo ($tb_label_font_size == '9px')? "selected":""; ?> >9</option>
                        <option value="9.5px" <?php echo ($tb_label_font_size == '9.5px')? "selected":""; ?> >9.5</option>
                        <option value="10px" <?php echo ($tb_label_font_size == '10px')? "selected":""; ?> >10</option>
                        <option value="10.5px" <?php echo ($tb_label_font_size == '10.5px')? "selected":""; ?> >10.5</option>
                        <option value="11px" <?php echo ($tb_label_font_size == '11px')? "selected":""; ?> >11</option>
                        <option value="11.5px" <?php echo ($tb_label_font_size == '11.5px')? "selected":""; ?> >11.5</option>
                        <option value="12px" <?php echo ($tb_label_font_size == '12px')? "selected":""; ?> >12</option>
                        <option value="12.5px" <?php echo ($tb_label_font_size == '12.5px')? "selected":""; ?> >12.5</option>
                        <option value="13px" <?php echo ($tb_label_font_size == '13px')? "selected":""; ?> >13</option>
                        <option value="13.5px" <?php echo ($tb_label_font_size == '13.5px')? "selected":""; ?> >13.5</option>
                        <option value="14px" <?php echo ($tb_label_font_size == '14px')? "selected":""; ?> >14</option>
                        <option value="14.5px" <?php echo ($tb_label_font_size == '14.5px')? "selected":""; ?> >14.5</option>
                        <option value="15px" <?php echo ($tb_label_font_size == '15px')? "selected":""; ?> >15</option>
                        <option value="15.5px" <?php echo ($tb_label_font_size == '15.5px')? "selected":""; ?> >15.5</option>
                        <option value="16px" <?php echo ($tb_label_font_size == '16px')? "selected":""; ?> >16</option>
                        <option value="16.5px" <?php echo ($tb_label_font_size == '16.5px')? "selected":""; ?> >16.5</option>
                        <option value="17px" <?php echo ($tb_label_font_size == '17px')? "selected":""; ?> >17</option>
                        <option value="17.5px" <?php echo ($tb_label_font_size == '17.5px')? "selected":""; ?> >17.5</option>
                        <option value="18px" <?php echo ($tb_label_font_size == '18px')? "selected":""; ?> >18</option>
                        <option value="18.5px" <?php echo ($tb_label_font_size == '18.5px')? "selected":""; ?> >18.5</option>
                        <option value="19px" <?php echo ($tb_label_font_size == '19px')? "selected":""; ?> >19</option>
                        <option value="19.5px" <?php echo ($tb_label_font_size == '19.5px')? "selected":""; ?> >19.5</option>
                        <option value="20px" <?php echo ($tb_label_font_size == '20px')? "selected":""; ?> >20</option>
                        <option value="20.5px" <?php echo ($tb_label_font_size == '20.5px')? "selected":""; ?> >20.5</option>
                    </select>
                </li>
                <li> <span >Font Size: </span>
                    <select id="tb_font" >
                        <option value="1px" <?php echo ($tb_text_font_size == '1px')? "selected":""; ?> >1</option>
                        <option value="1.5px" <?php echo ($tb_text_font_size == '1.5px')? "selected":""; ?> >1.5</option>
                        <option value="2px" <?php echo ($tb_text_font_size == '2px')? "selected":""; ?> >2</option>
                        <option value="2.5px" <?php echo ($tb_text_font_size == '2.5px')? "selected":""; ?> >2.5</option>
                        <option value="3px" <?php echo ($tb_text_font_size == '3px')? "selected":""; ?> >3</option>
                        <option value="3.5px" <?php echo ($tb_text_font_size == '3.5px')? "selected":""; ?> >3.5</option>
                        <option value="4px" <?php echo ($tb_text_font_size == '4px')? "selected":""; ?> >4</option>
                        <option value="4.5px" <?php echo ($tb_text_font_size == '4.5px')? "selected":""; ?> >4.5</option>
                        <option value="5px" <?php echo ($tb_text_font_size == '5px')? "selected":""; ?> >5</option>
                        <option value="5.5px" <?php echo ($tb_text_font_size == '5.5px')? "selected":""; ?> >5.5</option>
                        <option value="6px" <?php echo ($tb_text_font_size == '6px')? "selected":""; ?> >6</option>
                        <option value="6.5px" <?php echo ($tb_text_font_size == '6.5px')? "selected":""; ?> >6.5</option>
                        <option value="7px" <?php echo ($tb_text_font_size == '7px')? "selected":""; ?> >7</option>
                        <option value="7.5px" <?php echo ($tb_text_font_size == '7.5px')? "selected":""; ?> >7.5</option>
                        <option value="8px" <?php echo ($tb_text_font_size == '8px')? "selected":""; ?> >8</option>
                        <option value="8.5px" <?php echo ($tb_text_font_size == '8.5px')? "selected":""; ?> >8.5</option>
                        <option value="9px" <?php echo ($tb_text_font_size == '9px')? "selected":""; ?> >9</option>
                        <option value="9.5px" <?php echo ($tb_text_font_size == '9.5px')? "selected":""; ?> >9.5</option>
                        <option value="10px" <?php echo ($tb_text_font_size == '10px')? "selected":""; ?> >10</option>
                        <option value="10.5px" <?php echo ($tb_text_font_size == '10.5px')? "selected":""; ?> >10.5</option>
                        <option value="11px" <?php echo ($tb_text_font_size == '11px')? "selected":""; ?> >11</option>
                        <option value="11.5px" <?php echo ($tb_text_font_size == '11.5px')? "selected":""; ?> >11.5</option>
                        <option value="12px" <?php echo ($tb_text_font_size == '12px')? "selected":""; ?> >12</option>
                        <option value="12.5px" <?php echo ($tb_text_font_size == '12.5px')? "selected":""; ?> >12.5</option>
                        <option value="13px" <?php echo ($tb_text_font_size == '13px')? "selected":""; ?> >13</option>
                        <option value="13.5px" <?php echo ($tb_text_font_size == '13.5px')? "selected":""; ?> >13.5</option>
                        <option value="14px" <?php echo ($tb_text_font_size == '14px')? "selected":""; ?> >14</option>
                        <option value="14.5px" <?php echo ($tb_text_font_size == '14.5px')? "selected":""; ?> >14.5</option>
                        <option value="15px" <?php echo ($tb_text_font_size == '15px')? "selected":""; ?> >15</option>
                        <option value="15.5px" <?php echo ($tb_text_font_size == '15.5px')? "selected":""; ?> >15.5</option>
                        <option value="16px" <?php echo ($tb_text_font_size == '16px')? "selected":""; ?> >16</option>
                        <option value="16.5px" <?php echo ($tb_text_font_size == '16.5px')? "selected":""; ?> >16.5</option>
                        <option value="17px" <?php echo ($tb_text_font_size == '17px')? "selected":""; ?> >17</option>
                        <option value="17.5px" <?php echo ($tb_text_font_size == '17.5px')? "selected":""; ?> >17.5</option>
                        <option value="18px" <?php echo ($tb_text_font_size == '18px')? "selected":""; ?> >18</option>
                        <option value="18.5px" <?php echo ($tb_text_font_size == '18.5px')? "selected":""; ?> >18.5</option>
                        <option value="19px" <?php echo ($tb_text_font_size == '19px')? "selected":""; ?> >19</option>
                        <option value="19.5px" <?php echo ($tb_text_font_size == '19.5px')? "selected":""; ?> >19.5</option>
                        <option value="20px" <?php echo ($tb_text_font_size == '20px')? "selected":""; ?> >20</option>
                        <option value="20.5px" <?php echo ($tb_text_font_size == '20.5px')? "selected":""; ?> >20.5</option>
                    </select>
                </li>
                <li> <label>Rotate</label>
                    <select id="tb_rotate">
                        <option value="0" >0 Degree</option>
                        <option value="90">90 Degree</option>
                        <option value="180">180 Degree</option>
                        <option value="270">270 Degree</option>

                    </select>
                </li>
            </ul>
        </li>


        <li class="button_section">
            <button class="btn btn-xs btn-success" id="save" ><i class="fa fa-save"></i> Save</button>
            <button class="btn btn-xs btn-info" id="print"><i class="fa fa-print"></i> Print</button>
            <?php if($login['USER_ROLE'] != 'RECEPTIONIST' && $login['USER_ROLE'] != 'DOCTOR' && $login['USER_ROLE'] != 'STAFF'){
                $url = "window.location=baseurl+'app_admin/prescription_setting_fields'";
                if($setting_for=='WEB_PRESCRIPTION'){
                    $url = "window.location=baseurl+'app_admin/prescription_setting_fields/web'";
                }

                ?>
                <button onclick="<?php echo $url; ?>">Fields</button>
            <?php } ?>

        </li>

    </ul>
</div>

<div class="pres_layout">
    <div id="printThis" class="printThisDiv" data-url="<?php echo @$data['prescription']; ?>" style="background-size: cover !important; <?php echo $background_img; ?>" >
        <div><img id="barcode"/></div>
        <div style="display: block" id="prescription_header_content"></div>
        <div id="header_box" class="header_box child un_order_list_layout"   style="display:<?php echo ($field_layout_type=='ORDER_LIST')?'block':'none'; ?>;transform: rotate(<?php echo $header_rotate.'deg'; ?>); margin-top:<?php echo $top; ?> border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
            <?php if(empty($fields)){ ?>

                <li><span>UHID: </span>
                    <span><?php echo $default_field_values['uhid']; ?></span>
                </li>
                <li>
                    <span>Name:  </span>
                    <span><?php echo mb_strimwidth(ucfirst($default_field_values['patient_name']),0,40); ?> </span>
                </li>

                <li>
                    <span>S/0</span>
                    <span> <?php echo mb_strimwidth(ucfirst($default_field_values['parents_name']),0,40); ?></span>
                </li>

                <li>
                    <span>Age: </span>
                    <span><?php
                        $ageStr = $this->AppAdmin->getAgeStringFromDob($default_field_values['age'],false,true);
                        if(empty($ageStr)){
                            $ageStr = $default_field_values['age'];
                        } ?>
                        <?php echo ( $ageStr); ?></span>
                </li>
                <li>
                    <span>Gender: </span>
                    <span><?php echo ucfirst($default_field_values['gender']); ?></span>
                </li>
                <li>
                    <span>Address: </span>
                    <span><?php echo mb_strimwidth($default_field_values['patient_address'],0,40); ?></span>
                </li>

                <li>
                    <span>Token No: </span>
                    <span><?php echo $default_field_values['queue_number']; ?></span>
                </li>
                <li>
                    <span>Date:</span>
                    <span><?php echo date("d-m-Y");?></span>
                </li>
                <li>
                    <span> Weight: </span>
                    <span> </span>
                </li>

            <?php }else{ ?>
                <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                    $custom_condition = isset($field['custom_field'])?true:false;
                    ?>
                    <?php if($custom_condition===false){ ?>
                        <?php if( $field['status'] == 'ACTIVE'){ ?>

                            <?php if($key == 'uhid'){ ?>
                                <li>
                                      <span>  <span>UHID:</span>
                                        <span>   <?php echo $default_field_values['uhid']; ?></span>
                                </li>
                            <?php }else if($key == 'name'){ ?>
                                <li>
                                    <span>   Name:</span>
                                    <span>    <?php echo mb_strimwidth(ucfirst($default_field_values['patient_name']),0,40); ?></span>
                                </li>
                            <?php }else if($key == 'parents'){ ?>

                                <li>
                                    <span>S/0</span>
                                    <span>    <?php echo mb_strimwidth(ucfirst($default_field_values['parents_name']),0,40); ?></span>
                                </li>
                            <?php }else if($key == 'age'){ ?>

                                <li>
                                    <span>     Age: </span>
                                    <?php
                                    $ageStr = $this->AppAdmin->getAgeStringFromDob($default_field_values['age'],false,true);
                                    if(empty($ageStr)){
                                        $ageStr = $default_field_values['age'];
                                    }
                                    ?>
                                    <span> <?php echo ( $ageStr); ?></span>
                                </li>
                            <?php }else if($key == 'gender'){ ?>
                                <li>
                                    <span> Gender:</span>
                                    <span><?php echo ucfirst($default_field_values['gender']); ?></span>
                                </li>
                            <?php }else if($key == 'address'){ ?>
                                <li>
                                    <span>  Address:</span>
                                    <span> <?php echo mb_strimwidth($default_field_values['patient_address'],0,40); ?></span>
                                </li>
                            <?php }else if($key == 'token_no'){ ?>
                                <li>
                                    <span>  Token No:</span>
                                    <span> <?php echo $default_field_values['queue_number']; ?></span>
                                </li>
                            <?php }else if($key == 'date'){ ?>
                                <li>
                                    <span> Date:</span>
                                    <span> <?php echo !empty($default_field_values['appointment_datetime'])?date("d-m-Y",strtotime($default_field_values['appointment_datetime'])):date('d-m-Y');?></span>
                                </li>
                            <?php }else if($key == 'weight'){ ?>
                                <li>
                                    <span>  Weight:</span>
                                    <span>  <?php echo $default_field_values['weight']; ?></span>
                                </li>
                            <?php }else if($key == 'mobile'){ ?>
                                <li>
                                    <span>  Mobile:</span>
                                    <span>  <?php echo $default_field_values['mobile']; ?></span>
                                </li>
                            <?php }else if($key == 'OPD Fee'){ ?>
                                <li>
                                    <span> OPD Fee:</span>
                                    <span> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo !empty($default_field_values['amount'])?$default_field_values['amount']:0; ?></span>
                                </li>
                            <?php }else if($key == 'height'){ ?>
                                <li>
                                    <span> Height:</span>
                                    <span> <?php echo $default_field_values['patient_height']; ?></span>
                                </li>
                            <?php }else if($key == 'BP Systolic'){ ?>
                                <li>
                                    <span> BP Systolic:</span>
                                    <span> <?php echo $default_field_values['bp_systolic']; ?></span>
                                </li>
                            <?php }else if($key == 'head circumference'){ ?>
                                <li>
                                    <span> Head Circumference:</span>
                                    <span> <?php echo $default_field_values['head_circumference']; ?></span>
                                </li>

                            <?php }else if($key == 'BP Diastolic'){ ?>
                                <li>
                                    <span> BP Diastolic:</span>
                                    <span> <?php echo $default_field_values['bp_diasystolic']; ?></span>
                                </li>
                            <?php }else if($key == 'BMI'){ ?>
                                <li>
                                    <span> BMI:</span>
                                    <span>  <?php echo $default_field_values['bmi']; ?></span>
                                </li>
                            <?php }else if($key == 'BMI Status'){ ?>
                                <li>
                                    <span> BMI Status:</span>
                                    <span> <?php echo $default_field_values['bmi_status']; ?></span>
                                </li>
                            <?php }else if($key == 'temperature'){ ?>
                                <li>
                                    <span> Temperature:</span>
                                    <span> <?php echo $default_field_values['temperature']; ?></span>
                                </li>
                            <?php }else if($key == 'O.Saturation'){ ?>
                                <li>
                                    <span>O.Saturation:</span>
                                    <span><?php echo $default_field_values['o_saturation']; ?></span>
                                </li>
                            <?php }else if($key == 'validity time'){ ?>
                                <li>
                                    <span>     Validity Time:</span>
                                    <span> <?php echo !empty($default_field_values['service_validity_time'])?$default_field_values['service_validity_time']:''; ?>Day(s)</span>
                                </li>
                            <?php }else if($key == 'doctor name'){ ?>
                                <li>
                                    <span>Doctor Name:</span>
                                    <span> <?php echo $default_field_values['doctor_name'];?></span>
                                </li>
                            <?php }else if($key == 'token time'){ ?>
                                <li>
                                    <span>Token Time:</span>
                                    <span><?php echo date('d M Y h:i A', strtotime($default_field_values['appointment_datetime']));?></span>
                                </li>
                            <?php }else if($key == 'receipt datetime'){ ?>
                                <li>
                                    <span>Receipt Time:</span>
                                    <span><?php echo !empty($default_field_values['receipt_datetime'])?date('d M Y h:i A', strtotime($default_field_values['receipt_datetime'])):'';?></span>
                                </li>
                            <?php }else{ ?>
                                <li>
                                    <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$field['label']:$key; ?>:</span>
                                    <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$default_field_values[$field['column']]:isset($default_field_values[$key])?$default_field_values[$key]:'___'; ?></span>
                                </li>
                            <?php } ?>

                        <?php } ?>

                    <?php }else{ ?>
                        <?php if( $field['status'] == 'ACTIVE'){ ?>

                            <?php
                            $label = $field['label'];
                            if($field['column']=="parents_name"){
                                $label = "S/0";
                                $value = @$default_field_values[$field['column']];
                            }else{
                                $value = @$default_field_values[$field['column']];
                            }
                            ?>

                            <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                <?php if(($default_field_values['gender']=="FEMALE")){ ?>
                                    <li>
                                        <span><?php echo ucfirst($label); ?>:</span>
                                        <span ><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></span>
                                    </li>

                                <?php } ?>
                            <?php }else{ ?>

                                <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>

                                <li>
                                    <span><?php echo ucfirst($label); ?>:</span>
                                    <span ><?php echo ucfirst(strtolower($value)); ?></span>
                                </li>

                            <?php } ?>

                        <?php } ?>
                    <?php  } ?>
                    <?php $size++; } ?>
            <?php } ?>
        </div>


        <table id="header_box" class="header_box child table_layout"   style="display:<?php echo ($field_layout_type=='TABLE')?'block':'none'; ?>;transform: rotate(<?php echo $header_rotate.'deg'; ?>); margin-top:<?php echo $top; ?> border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
            <?php if(empty($fields)){ ?>
                <tr>
                    <th align="left" width="16.666%">UHID: </th>
                    <th align="left" width="16.666%">20181215</th>
                    <th align="left" width="16.666%">Name: </th>
                    <th align="left" width="16.666%">John Doe</th>
                    <th align="left" width="16.666%">Parents: </th>
                    <th align="left" width="16.666%">Madelyn</th>

                </tr>
                <tr>
                    <th align="left" width="16.666%">Age: </th>
                    <th align="left" width="16.666%">28 Years</th>
                    <th align="left" width="16.666%">Gender: </th>
                    <th align="left" width="16.666%">Male</th>
                    <th align="left" width="16.666%">Address: </th>
                    <th align="left" width="16.666%">Jaipur</th>
                </tr>
                <tr>

                    <th align="left" width="16.666%">Token No: </th>
                    <th align="left" width="16.666%">51</th>
                    <th align="left" width="16.666%">Date: </th>
                    <th align="left" width="16.666%"><?php echo date("d-m-Y");?></th>
                    <th align="left" width="16.666%">Weight: </th>
                    <th align="left" width="16.666%">____</th>
                </tr>
            <?php }else{ ?>
                <?php $keys = 1; $size = 1; $totalSize = sizeof($fields);
                foreach ($fields AS $key => $field){ ?>
                    <?php if( $field['status'] == 'ACTIVE' ){ ?>
                        <?php if($keys == 1){ ?> <tr> <?php } ?>
                        <th align="left" width="16.666%"><?php echo !empty($field['label'])?$field['label']:$key; ?>: </th>
                        <th align="left" width="16.666%"><?php echo @$default_field_values[$key]; ?></th>
                        <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                    <?php } ?>
                    <?php $size++; } ?>
            <?php } ?>
        </table>


        <table class="child tag_box" id="tag_box"   style="transform: rotate(<?php echo $tb_rotate.'deg'; ?>); margin-top=<?php echo $tb_top; ?>; display:<?php echo ($setting_for == "WEB_PRESCRIPTION")?'block':'none';?>; border:<?php echo $tag_box_border; ?>; left: <?php echo $tb_left; ?>; font-size: <?php echo $tb_text_font_size; ?>; top: <?php echo $tb_top; ?>; width: <?php echo $tb_width; ?>; height: auto;" >

            <?php for($i=1; $i < 3;$i++ ) { ?>
            <tr>
                <td><label class="heading_label" style="font-size: <?php echo $tb_heading_font_size; ?>;">Heading <?php echo $i;?></label></td>
            </tr>
            <tr>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Label <?php echo $i;?></th>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Label <?php echo $i+1;?></th>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Lable <?php echo $i+2;?></th>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Lable <?php echo $i+3;?></th>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Lable <?php echo $i+4;?></th>
               <th style="font-size: <?php echo $tb_label_font_size; ?>;">Lable <?php echo $i+5;?></th>
           </tr>
            <tr>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i;?></td>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i+1;?></td>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i+2;?></td>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i+3;?></td>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i+4;?></td>
                <td style="font-size: <?php echo $tb_text_font_size; ?>;" >Text <?php echo $i+5;?></td>
            </tr>
            <?php } ?>
        </table>



    </div>


</div>

<?php if(!empty($doctor_list)){ ?>
    <div class="doctor_list_box">
        <h2 style="border-bottom: 1px solid;">Doctor Barcode Setting</h2>
        <ul>
            <?php if(!empty($doctor_list)){ foreach($doctor_list as $key => $doctor){




                $doctor_image = Router::url('/images/channel-icon.png',true);
                if(!empty($doctor['profile_photo'])){
                    $doctor_image =$doctor['profile_photo'];
                }


                ?>

                <li>
                    <img src="<?php echo $doctor_image; ?>" style="width: 30px;height: 30px;">
                    <label class="doc_name">
                        <?php echo $doctor['name']; ?>
                        <span><?php echo $doctor['mobile']; ?></span>

                    </label>

                    <div class="option">
                        <label>
                            Show Barcode on prescription
                        </label>
                        <select data-id="<?php echo base64_encode($doctor['id']); ?>" class="doctor_barcode">
                            <option value="YES" <?php echo ($doctor['barcode_on_prescription']=='YES')?'selected':'' ?> >Yes</option>
                            <option value="NO" <?php echo ($doctor['barcode_on_prescription']=='NO')?'selected':'' ?>>No</option>
                        </select>
                    </div>
                </li>

            <?php }} ?>


        </ul>

    </div>
<?php } ?>
</body>
<style>

    .header_box li{
        float: left;
        list-style: none;
        font-weight: 600;
        padding: 5px 3px;
        margin-right: 8px;
        min-width: 10%;
    }

    .child li span:nth-child(2){
        font-weight: 300 !important;
    }

    ::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .a4_label{
        font-size: 12px;
        color: #d70000;
        margin-top: 6px;
        width: 100%;
        display: block;
    }
    .doctor_list_box{
        position: fixed;
        float: right;
        display: block;
        top: 0;
        right: 0;
        width: 18%;
        overflow-y: auto;
        overflow-x: hidden;
    }


    .doctor_list_box ul{
        display: block;
        float: left;
        list-style: none;
        width: 100%;
        padding: 0px;
        margin: 0px;
    }




    .doctor_list_box ul li{
        width: 100%;
        float: left;
        display: block;
        padding: 5px 2px;
        border-bottom: 1px solid #e9e4e4;
    }

    .doctor_list_box li img{
        width: 40px;
        height: 40px;
        float: left;
        display: block;
        border-radius: 29px;
        margin: 0px 2px;
    }
    .doctor_list_box li .doc_name{
        display: block;
        padding: 0px;
        margin: 0px;
        width: 100%;
    }

    .doctor_list_box li span{
        font-size: 10px;
        display: block;
        width: 100%;
    }

    .doctor_list_box .option{
        display: block;
        width: 100%;
        float: left;
        margin-top: 10px;
    }
    .doctor_list_box .option label{
        font-size: 12px;
        width: 80%;
        float: left;

    }

    .doctor_list_box .option select{
        width: 19%;
        font-size: 12px;
        float: left;
    }

    .doctor_list_box .checkbox-inline{
        display: contents;
        font-size: 12px;
        width: 100%;
    }

    .doctor_list_box .checkbox-inline input{
        margin: 0px;
        padding: 0px;
    }

    .tag_box td, .tag_box th{
        text-align: left;
    }

    .inner_url li span{
        font-size: 13px;
    }
    .sub_heading{
        margin: 0px;
    }

    #printThisDiv{
        background-size: cover;
    }
    .barcode_btn_div {
        width: 0%;
        float: right;
        margin-top: -11px;
        clear: both;

    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 25px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 7px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    #barcode {
        position: absolute;
        left: 1in;
        top: .30in;
    }



    .pres_layout{
        height: 297mm;
        width: 210mm;
        margin: 0 auto;
        box-shadow: #ccc 0 0 8px;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }

    .saveText ul{
        width: 97%;
        list-style: none;
        float: left;
        display: block;

        text-align: left;
        padding: 0px;
    }

    .saveText ul li{
        width: 100%;
        float: left;
        display: block;
        padding: 9px 4px;
        border-bottom: 1px solid #e1dede;
    }

    .inner_url li{
        border-bottom: none !important;
    }
    .saveText ul li span, .saveText ul li label{

    }

    .saveText ul li select{
        float: right;
        padding: 3px 6px;
        border-radius: 2px;
    }
    .saveText {

        font-weight: bold;
        text-align: center;
        position: fixed;
        overflow-y: auto;
        overflow-x: hidden;
        /* bottom: 20px; */
        cursor: pointer;
        width: 20%;
        top: 0px;
        left: 0;
    }
    .saveTextSave{
        font-weight: bold;
        text-align: center;
        position: absolute;
        bottom: 0;
        position: fixed;
        color: red;
        padding-left: 20px;
        cursor: pointer;
    }
    .printThisDiv{
        height: 297mm !important;
        width: 210mm !important;
    }
    .button_section button {
        background: #2b7e80;
        border-radius: 3px;
        width: 30%;
        padding: 6px 5px;
        color: #FFFFFF;
        border: none;
    }
</style>
<script>


    $(document).ready(function(){




        $(document).on('change','.upload_prescription',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        var height = this.height;
                        var width = this.width;
                        $('#printThis').css('background-image', "url('"+e.target.result+"')");
                        $('#printThis').attr('data-url', "");
                        $('.remove_prescription').show();
                    };
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('click','.remove_prescription',function(e){

            $("#printThis").css('background-image', 'none');
            $('#printThis').attr('data-url', "");
            $(this).fadeOut(100);
        });








        var barcode = '<?php echo "1A1234567"; ?>';
        JsBarcode("#barcode", barcode, {
            lineColor: "#000",
            format: "CODE128",
            width:1,
            height:50,
            displayValue: false,
            margin:0,
            background:'#ffffff'
        });

        var barcode = '<?php echo $barcode; ?>';

        $('#barcode_btn').val(barcode);
        if(barcode=='YES'){
            $("#barcode").show();
        }else{
            $("#barcode").hide();
        }

        $(document).on('change',"#barcode_btn", function(){
            if($('#barcode_btn').val()=='YES'){
                $("#barcode").show();
            }else{
                $("#barcode").hide();
            }

        });
        $(document).on('change',"#field_layout_type", function(){

            if($("#show_patient_detail").val()=='YES'){
                $(".table_layout, .un_order_list_layout").hide();
                if($('#field_layout_type').val()=='TABLE'){
                    $(".table_layout").show();
                }else{
                    $(".un_order_list_layout").show();
                }
            }

        });


        function rotate_header_box(){
            var deg = $("#header_rotate").val();
            var rotate = "rotate("+deg+"deg)";
            $(".header_box").css({
                "-webkit-transform": rotate,
                "-moz-transform": rotate,
                "transform":rotate

            });
        }
        $(document).on('change',"#header_rotate", function(){
            rotate_header_box();

        });

        $(document).on('change',"#tb_rotate", function(){
            var deg = $("#tb_rotate").val();
            var rotate = "rotate("+deg+"deg)";
            $("#tag_box").css({
                "-webkit-transform": rotate,
                "-moz-transform": rotate,
                "transform":rotate
            });

        });

        $(document).on("change","#fontSize",function(){
            var size = $(this).val();
            $('.header_box').css("font-size",size);
        });

         $(document).on("change","#tb_heading_font",function(){
            var size = $(this).val();
            $('#tag_box .heading_label').css("font-size",size);
        });

         $(document).on("change","#tb_label_font",function(){
                var size = $(this).val();
                $('#tag_box th').css("font-size",size);
        });

          $(document).on("change","#tb_font",function(){
                    var size = $(this).val();
                    $('#tag_box td').css("font-size",size);
            });

         $(document).on("change","#tag_box_border",function(){
                var size = $(this).val();
                $('#tag_box').css("border",size);
        });

         $(document).on("change","#header_box_border",function(){
                    var size = $(this).val();
                    $('.header_box').css("border",size);
          });






        $('.header_box, .tag_box').resizable({
            handles: 'ne, nw, se, sw, n, w, s, e'

        });
        $( ".tag_box, .header_box" ).draggable();


        $(document).on('click','#save', function() {

            var thisButton = $(this);

            var layout_class = ($("#field_layout_type").val()=="ORDER_LIST")?'.un_order_list_layout':'.table_layout';

            var top = $(layout_class).css("top");
            var left = $(layout_class).css("left");
            var width = $(layout_class).css("width");
            var height = $(layout_class).css("height");

            var setting_for = "<?php echo $setting_for; ?>";
            var fontSize = $('.header_box').css("font-size");
            var tb_heading_font = $('#tb_heading_font').val();
            var tb_label_font = $('#tb_label_font').val();
            var tb_font = $('#tb_font').val();
            var header_box_border = $('#header_box_border').val();
            var tag_box_border = $('#tag_box_border').val();
            var header_rotate = $('#header_rotate').val();
            var tb_rotate = $('#tb_rotate').val();
            var show_patient_detail = $('#show_patient_detail').val();
            var field_layout_type = $('#field_layout_type').val();




            var tb_top = $(".tag_box").css('top');
            var tb_left = $(".tag_box").css('left');
            var tb_width = $(".tag_box").css('width');
            var prescription = $('.printThisDiv').css('background-image');
            prescription = prescription.replace('url(','').replace(')','').replace(/\"/gi, "");

            var data_url = $('#printThis').attr('data-url');
            var dataToSend = {show_patient_detail:show_patient_detail,setting_for:setting_for,field_layout_type:field_layout_type,header_rotate:header_rotate,tb_rotate:tb_rotate,du:data_url, hbb:header_box_border, tbb:tag_box_border, thf:tb_heading_font, tlf:tb_label_font, tf:tb_font, prescription:prescription,font_size:fontSize,top:top,left:left,width:width,height:height,tb_top:tb_top,tb_left:tb_left,tb_width:tb_width,barcode:$('#barcode_btn').val()};
            var last_html = $(thisButton).html();

            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/save_setting_prescription",
                data: dataToSend,
                beforeSend:function(){
                    $(thisButton).html('Saving..')
                },
                success: function (data) {
                    $(thisButton).html(last_html);
                    alert("Setting saved successfully!");
                },
                error: function (data) {
                    $(thisButton).html(last_html);
                    alert("Sorry something went wrong on server.");
                }
            });

        });
        $(document).on('click','#print', function() {
            $('#printThis').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: " ",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
        });


        $(".doctor_list_box, .saveText").height($(window).height());



        $(document).on('change','.doctor_barcode', function() {

            var thisButton = $(this);
            var di = $(this).attr('data-id');
            var barcode = $(this).val();
            var dataToSend = {di:di,barcode:barcode};
            var last_html = $(thisButton).html();
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/update_doctor_pre_setting",
                data: dataToSend,
                beforeSend:function(){

                },
                success: function (data) {

                    alert("Setting saved successfully!");
                },
                error: function (data) {
                    $(thisButton).html(last_html);
                    alert("Sorry something went wrong on server.");
                }
            });



        });

        $("#header_rotate").val("<?php echo $header_rotate; ?>");
        $("#tb_rotate").val("<?php echo $tb_rotate; ?>");
        $("#header_box_border").trigger('change');



       /* CKEDITOR.replace( "header_content",{
            toolbarGroups: [
                { name: 'links', groups: [ 'links' ] },
                { name: 'colors', groups: [ 'colors' ] },
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'styles', groups: ['styles']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
            ],
            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
            autoParagraph :false,
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        } );*/



        $(document).on('click','.show_header_btn',function(e){
            $("#editHeaderModal").modal('show');
        });

        $(document).on('click','.close_dialog',function(e){
            $("#editHeaderModal").modal('hide');
        });


        $(document).on('click','.save_header_btn',function(e){
            $("#prescription_header_content").html(CKEDITOR.instances['header_content'].getData());
            $("#editHeaderModal").modal('hide');
        });

         $(document).on('change','#show_patient_detail',function(e){
            $(".header_box").hide();
            
           if($(this).val()=="YES"){
               var obj_cls = ($("#field_layout_type").val()=='TABLE')?".table_layout":".un_order_list_layout";
                $(obj_cls).show();
           }
        });

        $("#show_patient_detail").trigger('change');


    });



</script>



<style media="print">
    @page {
        size: A4;
        margin: 0;
    }
	@media print {
  html, body {
    width: 210mm;
    /*height: 297mm;*/
  }
 }
</style>

<div style="display: none;" class="modal fade" id="editHeaderModal" role="dialog">
    <div class="modal-dialog" style="width: 70%;">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_dialog" >&times;</button>
                    <h4 class="modal-title">Edit Header Content</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <textarea name="header" rows="5" id="header_content"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close_dialog" ><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning" ><i class="fa fa-refresh"></i> Reset</button>
                    <button type="button" class="btn btn-success save_header_btn"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>



</div>

<!--
--><?php
/*echo $this->Html->script(array('bootstrap.min.js','ckeditor/ckeditor.js'),array('fullBase' => true));

echo $this->Html->css(array('bootstrap.min.css'),array("media"=>'all','fullBase' => true));
*/?>

</html>