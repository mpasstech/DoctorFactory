<?php
$left = '0%';
$top = '0%';
$width = '60%';
$height = '8%';
$fontSize = '13px';
$fields = array();
$header_box_border =  "2px solid";
$header_rotate = $tb_rotate = 0;
if (!empty($data['setting_id'])) {
    $left = $data['left'];
    $top = $data['top'];
    $width = $data['width'];
    $height = $data['height'];
    $fontSize = $data['font_size'];
    $fontSize = $data['font_size'];
    $field_layout_type = $data['field_layout_type'];
    $header_box_border = !empty($data['header_box_border'])?$data['header_box_border']:$header_box_border;

    $header_rotate = !empty($data['header_rotate'])?$data['header_rotate']:$header_rotate;
    $tb_rotate = !empty($data['tb_rotate'])?$data['tb_rotate']:$tb_rotate;


    if(!empty($data['fields']))
    {
        $fields = json_decode($data['fields'],true);

        if(isset($fields['service_validity_time'])){
            $fields['service_validity_time']['label'] = "Validity Upto";
        }

        uasort($fields, function($item1, $item2){
            if ($item1['order'] == $item2['order']) return 0;
            return $item1['order'] < $item2['order'] ? -1 : 1;
        });
    }


}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseurl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php  echo $this->Html->script(array('jquery.js','printThis.js','JsBarcode.all.min')); ?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <?php  echo $this->Html->css(array('font-awesome.min.css')); ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>Print Prescription</title>
</head>
<body>
<div class="pres_layout">
    <div class="printThisDiv">
        <div  id="printThisPrescription">
            <?php if($data['barcode'] =='YES' && $data['barcode_on_prescription'] =="YES"){ ?>
                <div><img id="barcode"/></div>
            <?php } ?>
            <?php if($data['field_layout_type'] == "ORDER_LIST"){ ?>
                <div class="child" id="label_box"  style=" float:left; transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: auto;" >
                    <?php if(empty($fields)){ ?>

                        <li><span>UHID: </span>
                            <span><?php echo $data['uhid']; ?></span>
                        </li>
                        <li>
                            <span>Name:  </span>
                            <span><?php echo $data['patient_name']; ?> </span>
                        </li>

                        <li>
                            <span><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?></span>
                            <span> <?php echo $data['parents_name']; ?></span>
                        </li>

                        <li>
                            <span>Age: </span>
                            <span><?php
                                $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age'],false,true);
                                if(empty($ageStr)){
                                    $ageStr = $data['age'];
                                    if($ageStr == '0000-00-00' || $ageStr == '1970-01-01' ){
                                        $ageStr = "";
                                    }
                                } ?>
                                <?php echo ( $ageStr); ?></span>
                        </li>
                        <li>
                            <span>Gender: </span>
                            <span><?php echo ucfirst(strtolower($data['gender'])); ?></span>
                        </li>
                        <li>
                            <span>Address: </span>
                            <span><?php echo $data['patient_address']; ?></span>
                        </li>

                        <li>
                            <span>Token No: </span>
                            <span><?php echo $this->AppAdmin->create_queue_number($data); ?></span>
                        </li>
                        <li>
                            <span>Date:</span>
                            <span><?php echo date("d-m-Y");?></span>
                        </li>
                        <li>
                            <span> Weight(KG): </span>
                            <span> ____ </span>
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
                                        <span>   <?php echo $data['uhid']; ?></span>
                                        </li>
                                    <?php }else if($key == 'name'){ ?>
                                        <li>
                                            <span>   Name:</span>
                                            <span>    <?php echo $data['patient_name']; ?></span>
                                        </li>
                                    <?php }else if($key == 'parents'){ ?>
                                        <?php $relationPrefix = $data['relation_prefix']; ?>
                                        <li>
                                            <span>    <?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?></span>
                                            <span>    <?php echo $data['parents_name']; ?></span>
                                        </li>
                                    <?php }else if($key == 'age'){ ?>

                                        <li>
                                            <span>     Age: </span>
                                            <?php
                                            $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age'],false,true);
                                            if(empty($ageStr)){
                                                $ageStr = $data['age'];
                                                if($ageStr == '0000-00-00' || $ageStr == '1970-01-01' ){
                                                    $ageStr = "";
                                                }
                                            }
                                            ?>
                                            <span> <?php echo ( $ageStr); ?></span>
                                        </li>
                                    <?php }else if($key == 'gender'){ ?>
                                        <li>
                                            <span> Gender:</span>
                                            <span><?php echo ucfirst(strtolower($data['gender'])); ?></span>
                                        </li>
                                    <?php }else if($key == 'address'){ ?>
                                        <li>
                                            <span>  Address:</span>
                                            <span> <?php echo $data['patient_address']; ?></span>
                                        </li>
                                    <?php }else if($key == 'token_no'){ ?>
                                        <li>
                                            <span>  Token No:</span>
                                            <span> <?php echo $this->AppAdmin->create_queue_number($data); ?></span>
                                        </li>
                                    <?php }else if($key == 'date'){ ?>
                                        <li>
                                            <span> Date:</span>
                                            <span> <?php echo !empty($data['appointment_datetime'])?date("d-m-Y",strtotime($data['appointment_datetime'])):date('d-m-Y');?></span>
                                        </li>
                                    <?php }else if($key == 'weight'){ ?>
                                        <li>
                                            <span>  Weight(KG):</span>
                                            <span>  <?php echo $data['weight']; ?></span>
                                        </li>
                                    <?php }else if($key == 'mobile'){ ?>
                                        <li>
                                            <span>  Mobile:</span>
                                            <span>  <?php echo $data['mobile']; ?></span>
                                        </li>
                                    <?php }else if($key == 'OPD Fee'){ ?>
                                        <li>
                                            <span> OPD Fee:</span>
                                            <span> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo !empty($data['amount'])?$data['amount']:0; ?></span>
                                        </li>
                                    <?php }else if($key == 'height'){ ?>
                                        <li>
                                            <span> Height(CM):</span>
                                            <span> <?php echo $data['patient_height']; ?></span>
                                        </li>
                                    <?php }else if($key == 'BP Systolic'){ ?>
                                        <li>
                                            <span> BP Systolic:</span>
                                            <span> <?php echo $data['bp_systolic']; ?></span>
                                        </li>
                                    <?php }else if($key == 'head circumference'){ ?>
                                        <li>
                                            <span> Head Circumference:</span>
                                            <span> <?php echo $data['head_circumference']; ?></span>
                                        </li>

                                    <?php }else if($key == 'BP Diastolic'){ ?>
                                        <li>
                                            <span> BP Diastolic:</span>
                                            <span> <?php echo $data['bp_diasystolic']; ?></span>
                                        </li>
                                    <?php }else if($key == 'BMI'){ ?>
                                        <li>
                                            <span> BMI:</span>
                                            <span>
                                                <?php if( isset($data['weight']) && !empty($data['weight']) && isset($data['height']) && !empty($data['height']) && empty($data['bmi']) ){
                                                   echo round( ($data['weight'] / (($data['height']/100) * ($data['height']/100))),3 );
                                                }
                                                else
                                                {
                                                    echo $data['bmi'];
                                                } ?>

                                            </span>
                                        </li>
                                    <?php }else if($key == 'BMI Status'){ ?>
                                        <li>
                                            <span> BMI Status:</span>
                                            <span> <?php echo $data['bmi_status']; ?></span>
                                        </li>
                                    <?php }else if($key == 'temperature'){ ?>
                                        <li>
                                            <span> Temperature:</span>
                                            <span> <?php echo $data['temperature']; ?></span>
                                        </li>
                                    <?php }else if($key == 'O.Saturation'){ ?>
                                        <li>
                                            <span>O.Saturation:</span>
                                            <span><?php echo $data['o_saturation']; ?></span>
                                        </li>
                                    <?php }else if($key == 'validity time'){ ?>
                                        <li>
                                            <span>     Validity Time:</span>
                                            <span> <?php echo !empty($data['service_validity_time'])?$data['service_validity_time']:''; ?>Day(s)</span>
                                        </li>
                                    <?php }else if($key == 'doctor name'){ ?>
                                        <li>
                                            <span>Doctor Name:</span>
                                            <span> <?php echo $data['doctor_name'];?></span>
                                        </li>
                                    <?php }else if($key == 'token time'){ ?>
                                        <li>
                                            <span>Token Time:</span>
                                            <span><?php echo date('d M Y h:i A', strtotime($data['appointment_datetime']));?></span>
                                        </li>
                                    <?php }else if($key == 'receipt datetime'){ ?>
                                        <li>
                                            <span>Receipt Time:</span>
                                            <span><?php echo !empty($data['receipt_datetime'])?date('d M Y h:i A', strtotime($data['receipt_datetime'])):'';?></span>
                                        </li>
                                    <?php }else{ ?>
                                        <li>
                                            <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$field['label']:$key; ?>:</span>
                                            <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$data[$field['column']]:isset($data[$key])?$data[$key]:'___'; ?></span>
                                        </li>
                                    <?php } ?>

                                <?php } ?>

                            <?php }else{ ?>
                                <?php if( $field['status'] == 'ACTIVE'){ ?>

                                    <?php
                                    $label = $field['label'];
                                    if($field['column']=="bmi")
                                    {
                                        $value = $this->AppAdmin->calculateBmi($data['patient_id'],$data['patient_type']);
                                    }else if($field['column']=="age"){
                                        $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']],false,true);
                                        if(empty($value)){
                                            $value = $data[$field['column']];
                                            if($value == '0000-00-00' || $value == '1970-01-01' ){
                                                $value = "";
                                            }

                                        }


                                    }else if($field['column']=="country_id"){
                                        $value = "";
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="state_id"){
                                        $value = "";
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="city_id"){
                                        $value = $data['city_name'];
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="parents_name"){
                                        $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                        $value = @$data[$field['column']];
                                    }else if($field['column']=="gender" || $field['column']=="marital_status"){
                                        $value = @ucfirst(strtolower($data[$field['column']]));
                                    }else{
                                        $value = @$data[$field['column']];
                                    }
                                    ?>

                                    <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                        <?php if(($data['gender']=="FEMALE")){ ?>
                                            <li>
                                                <span><?php echo ucfirst($label); ?>:</span>
                                                <span ><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></span>
                                            </li>

                                        <?php } ?>
                                    <?php }else{ ?>

                                        <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>

                                        <li>
                                            <span><?php echo ucfirst($label); ?>:</span>
                                            <span ><?php echo $value; ?></span>
                                        </li>

                                    <?php } ?>

                                <?php } ?>
                            <?php  } ?>
                            <?php $size++; } ?>
                    <?php } ?>
                </div>
            <?php }else{ ?>
                <table class="child" id="label_box"  style="transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
                    <?php if(empty($fields)){ ?>
                        <tr>
                            <th align="left" width="16.666%">UHID: </th>
                            <th align="left" width="16.666%"><?php echo $data['uhid']; ?></th>
                            <th align="left" width="16.666%">Name: </th>
                            <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['patient_name']),0,40); ?></th>
                            <th align="left" width="16.666%"><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?>  </th>
                            <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['parents_name']),0,40); ?></th>
                        </tr>
                        <tr>
                            <th align="left" width="16.666%">Age: </th>
                            <?php
                            $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                            if(empty($ageStr)){
                                $ageStr = $data['age'];
                                if($ageStr == '0000-00-00' || $ageStr == '1970-01-01' ){
                                    $ageStr = "";
                                }
                            } ?>
                            <th align="left" width="16.666%"><?php echo ( $ageStr); ?></th>
                            <th align="left" width="16.666%">Gender: </th>
                            <th align="left" width="16.666%"><?php echo ucfirst($data['gender']); ?></th>
                            <th align="left" width="16.666%">Address: </th>
                            <th align="left" width="16.666%"><?php echo mb_strimwidth($data['patient_address'],0,40); ?></th>
                        </tr>
                        <tr>

                            <th align="left" width="16.666%">Token No: </th>
                            <th align="left" width="16.666%"><?php echo $this->AppAdmin->create_queue_number($data); ?></th>
                            <th align="left" width="16.666%">Date: </th>
                            <th align="left" width="16.666%"><?php echo date("d-m-Y");?></th>
                            <th align="left" width="16.666%">Weight(KG): </th>
                            <th align="left" width="16.666%">____</th>
                        </tr>
                    <?php }else{ ?>
                        <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                            $custom_condition = isset($field['custom_field'])?true:false;
                            ?>
                            <?php if($custom_condition===false){ ?>
                                <?php if( $field['status'] == 'ACTIVE'){ ?>
                                    <?php if($keys == 1){ ?> <tr> <?php } ?>
                                    <?php if($key == 'uhid'){ ?>
                                        <th align="left" width="16.666%">UHID: </th>
                                        <th align="left" width="16.666%"><?php echo $data['uhid']; ?></th>
                                    <?php }else if($key == 'name'){ ?>
                                        <th align="left" width="16.666%">Name: </th>
                                        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['patient_name']),0,40); ?></th>
                                    <?php }else if($key == 'parents'){ ?>
                                        <?php $relationPrefix = $data['relation_prefix']; ?>
                                        <th align="left" width="16.666%"><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?> </th>
                                        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['parents_name']),0,40); ?></th>
                                    <?php }else if($key == 'age'){ ?>

                                        <th align="left" width="16.666%">Age: </th>


                                        <?php
                                        $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                                        if(empty($ageStr)){
                                            $ageStr = $data['age'];
                                            if($ageStr == '0000-00-00' || $ageStr == '1970-01-01' ){
                                                $ageStr = "";
                                            }
                                        }
                                        ?>


                                        <th align="left" width="16.666%"><?php echo ( $ageStr); ?></th>
                                    <?php }else if($key == 'gender'){ ?>
                                        <th align="left" width="16.666%">Gender: </th>
                                        <th align="left" width="16.666%"><?php echo ucfirst($data['gender']); ?></th>
                                    <?php }else if($key == 'address'){ ?>
                                        <th align="left" width="16.666%">Address: </th>

                                        <th align="left" width="16.666%"><?php echo mb_strimwidth($data['patient_address'],0,40); ?></th>
                                    <?php }else if($key == 'token_no'){ ?>
                                        <th align="left" width="16.666%">Token No: </th>
                                        <th align="left" width="16.666%"><?php echo $this->AppAdmin->create_queue_number($data); ?></th>
                                    <?php }else if($key == 'date'){ ?>
                                        <th align="left" width="16.666%">Date: </th>
                                        <th align="left" width="16.666%"><?php echo !empty($data['appointment_datetime'])?date("d-m-Y",strtotime($data['appointment_datetime'])):date('d-m-Y');?></th>
                                    <?php }else if($key == 'weight'){ ?>
                                        <th align="left" width="16.666%">Weight(KG): </th>
                                        <th align="left" width="16.666%"><?php echo $data['weight']; ?></th>
                                    <?php }else if($key == 'mobile'){ ?>
                                        <th align="left" width="16.666%">Mobile: </th>
                                        <th align="left" width="16.666%"><?php echo $data['mobile']; ?></th>
                                    <?php }else if($key == 'OPD Fee'){ ?>
                                        <th align="left" width="16.666%">OPD Fee: </th>
                                        <th align="left" width="16.666%"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo !empty($data['amount'])?$data['amount']:0; ?></th>
                                    <?php }else if($key == 'height'){ ?>
                                        <th align="left" width="16.666%">Height: </th>
                                        <th align="left" width="16.666%"><?php echo $data['patient_height']; ?></th>
                                    <?php }else if($key == 'BP Systolic'){ ?>
                                        <th align="left" width="16.666%">BP Systolic: </th>
                                        <th align="left" width="16.666%"><?php echo $data['bp_systolic']; ?></th>
                                    <?php }else if($key == 'head circumference'){ ?>
                                        <th align="left" width="16.666%">Head Circumference: </th>
                                        <th align="left" width="16.666%"><?php echo $data['head_circumference']; ?></th>

                                    <?php }else if($key == 'BP Diastolic'){ ?>
                                        <th align="left" width="16.666%">BP Diastolic: </th>
                                        <th align="left" width="16.666%"><?php echo $data['bp_diasystolic']; ?></th>
                                    <?php }else if($key == 'BMI'){ ?>
                                        <th align="left" width="16.666%">BMI: </th>
                                        <th align="left" width="16.666%">
                                            <?php if(!empty($data['weight']) && isset($data['weight']) && !empty($data['patient_height']) && isset($data['patient_height']) && empty($data['bmi']) ){
                                                echo round( ($data['weight'] / (($data['patient_height']/100) * ($data['patient_height']/100))),3 );
                                            }
                                            else
                                            {
                                                echo $data['bmi'];
                                            } ?>

                                        </th>
                                    <?php }else if($key == 'BMI Status'){ ?>
                                        <th align="left" width="16.666%">BMI Status: </th>
                                        <th align="left" width="16.666%"><?php echo $data['bmi_status']; ?></th>
                                    <?php }else if($key == 'temperature'){ ?>
                                        <th align="left" width="16.666%">Temperature: </th>
                                        <th align="left" width="16.666%"><?php echo $data['temperature']; ?></th>
                                    <?php }else if($key == 'O.Saturation'){ ?>
                                        <th align="left" width="16.666%">O.Saturation: </th>
                                        <th align="left" width="16.666%"><?php echo $data['o_saturation']; ?></th>
                                    <?php }else if($key == 'validity time'){ ?>
                                        <th align="left" width="16.666%">Validity Time: </th>
                                        <th align="left" width="16.666%"><?php echo !empty($data['service_validity_time'])?$data['service_validity_time']:''; ?>Day(s)</th>
                                    <?php }else if($key == 'doctor name'){ ?>
                                        <th align="left" width="16.666%">Doctor Name: </th>
                                        <th align="left" width="16.666%"><?php echo $data['doctor_name'];?></th>
                                    <?php }else if($key == 'token time'){ ?>
                                        <th align="left" width="16.666%">Token Time: </th>
                                        <th align="left" width="16.666%"><?php echo date('d M Y h:i A', strtotime($data['appointment_datetime']));?></th>
                                    <?php }else if($key == 'receipt datetime'){ ?>
                                        <th align="left" width="16.666%">Receipt Time: </th>
                                        <th align="left" width="16.666%"><?php echo !empty($data['receipt_datetime'])?date('d M Y h:i A', strtotime($data['receipt_datetime'])):'';?></th>
                                    <?php }else{ ?>
                                        <th align="left" width="16.666%"><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$field['label']:$key; ?>: </th>
                                        <th align="left" width="16.666%"><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$data[$field['column']]:isset($data[$key])?$data[$key]:'___'; ?></th>
                                    <?php } ?>
                                    <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                <?php } ?>

                            <?php }else{ ?>
                                <?php if( $field['status'] == 'ACTIVE'){ ?>
                                    <?php if($keys == 1){ ?> <tr> <?php } ?>
                                    <?php
                                    $label = $field['label'];
                                    if($field['column']=="bmi")
                                    {
                                        $value = $this->AppAdmin->calculateBmi($data['patient_id'],$data['patient_type']);
                                    }else if($field['column']=="age"){
                                        $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']]);
                                        if(empty($value)){
                                            $value = $data[$field['column']];
                                            if($value == '0000-00-00' || $value == '1970-01-01' ){
                                                $value = "";
                                            }
                                        }
                                    }else if($field['column']=="country_id"){
                                        $value = "";
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="state_id"){
                                        $value = "";
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="city_id"){
                                        $value = $data['city_name'];
                                        if(!empty($data[$field['column']])){
                                            $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                            if(!empty($name)){
                                                $value = $name;
                                            }
                                        }

                                    }else if($field['column']=="parents_name"){
                                        $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                        $value = @$data[$field['column']];
                                    }else{
                                        $value = @$data[$field['column']];
                                    }
                                    ?>

                                    <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                        <?php if(($data['gender']=="FEMALE")){ ?>
                                            <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                            <th align="left" width="16.666%"><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></th>
                                            <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>
                                        <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                        <th align="left" width="16.666%"><?php echo ucfirst($value); ?></th>
                                        <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                    <?php } ?>


                                <?php } ?>
                            <?php  } ?>
                            <?php $size++; } ?>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>

    <div class="showLabel">
        <input type="checkbox" id="showLabel"> <label for="showLabel">Don't show patient details</label>
    </div>
    <div class="saveText">
        Note: Press enter to print or click here
    </div>

</div>
</body>
<script>

    $(function(){

        var barcode_status =  '<?php echo $data['barcode'] ?>';
        var barcode_on_prescription =  '<?php echo $data['barcode_on_prescription'] ?>';
        if(barcode_status=='YES' && barcode_on_prescription =="YES"){
            var barcode = '<?php echo "1A".$data['appointment_id']; ?>';
            JsBarcode("#barcode", barcode, {
                lineColor: "#000",
                format: "CODE128",
                width:1,
                height:50,
                displayValue: false,
                margin:0,
                background:'#ffffff'
            });

        }

        $(document).on('click','.saveText', function(e) {
            e.preventDefault();
            e.stopPropagation();
            print_dialog(e);
        });

        function print_dialog(){
            $('#printThisPrescription').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                header: false,               // prefix to html
                footer: false,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: " ",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
        }
        $(document).keypress(function(e) {
            e.preventDefault();
            e.stopPropagation();
            if(e.which == 13) {
                print_dialog();
            }
        });

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if (keyCode == 27) {
                window.close();
            }
        });



    });

    $("#showLabel").click(function(){
        if($(this).prop("checked") == true){

            $("#label_box").hide();

        }
        else
        {
            $("#label_box").show();
        }
    });

</script>
<style>

    #barcode {
        position: absolute;
        left: 1in;
        top: .30in;
    }
    .child{
        border:2px solid #000000;
        border-radius: 3px;
    }

    .child li{
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



    .child li span:nth-child(even){
        min-width: 8%;

    }

    .pres_layout{
        height: 297mm;
        width: 210mm;
        margin: 0 auto;
        box-shadow: #ccc 0 0 8px;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
        padding:0px;
    }

    .saveText {

        font-weight: bold;
        text-align: center;
        position: fixed;
        color: red;
        bottom: 20px;
        cursor: pointer;
        width: 50%;
    }
    .showLabel {

        font-weight: bold;
        text-align: center;
        position: fixed;
        /*color: red;*/
        bottom: 50px;
        cursor: pointer;
        width: 50%;
    }
    .printThisDiv{

        float: left;
        width: 100%;
        margin: 0px;
        padding: 0px;
        position: relative;

    }
    #label_box{
        position: relative;

    }
    .saveText button {
        background: cadetblue;
        border-radius: 7px;
        width: 100px;
        height: 35px;
        color: #FFFFFF;
    }
    #printThisPrescription{
        height: 297mm;
        width: 210mm;
        margin:0px;
        padding:0px;


    }
</style>


<style media="print">
    @page {
        size: A4;
        margin: 0;
    }
    @media print {

        .pres_layout{

            border:none !important;
            overflow: hidden;
            height: 297mm !important;
            width: 210mm !important;
        }


        .saveText{display:none !important;}
        .showLabel {display:none !important;}
        html, body {
            width: 210mm;
            /*height: 297mm;*/
            margin: 0px;
            padding: 0px;
        }


        #printThisPrescription{
            border: none !important;
            box-shadow:  none !important;
            height: 297mm !important;
            width: 210mm !important;
            margin:0px;
            padding:0px;
        }

    }


</style>
</html>