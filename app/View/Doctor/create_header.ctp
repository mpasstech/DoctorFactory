<?php if(empty($fields)){ ?>
    <tr>
        <th align="left" width="16.666%">UHID: </th>
        <th align="left" width="16.666%"><?php echo $data['uhid']; ?></th>
        <th align="left" width="16.666%">Name: </th>
        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['patient_name']),0,40); ?></th>
        <th align="left" width="16.666%">Parents: </th>
        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['parents_name']),0,40); ?></th>
    </tr>
    <tr>
        <th align="left" width="16.666%">Age: </th>
        <?php
        $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
        if(empty($ageStr)){
            $ageStr = $data['age'];
        } ?>
        <th align="left" width="16.666%"><?php echo ( $ageStr); ?></th>
        <th align="left" width="16.666%">Gender: </th>
        <th align="left" width="16.666%"><?php echo ucfirst($data['gender']); ?></th>
        <th align="left" width="16.666%">Address: </th>
        <th align="left" width="16.666%"><?php echo mb_strimwidth($data['address'],0,40); ?></th>
    </tr>
    <tr>

        <th align="left" width="16.666%">Token No: </th>
        <th align="left" width="16.666%"><?php echo $data['queue_number']; ?></th>
        <th align="left" width="16.666%">Date: </th>
        <th align="left" width="16.666%"><?php echo date("d-m-Y");?></th>
        <th align="left" width="16.666%">Weight: </th>
        <th align="left" width="16.666%">____</th>
    </tr>
<?php }else{ ?>
    <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){ ?>
        <?php if( $field['status'] == 'ACTIVE' ){ ?>
            <?php if($keys == 1){ ?> <tr> <?php } ?>
            <?php
            $label = $field['label'];
            if($field['column']=="age"){
                $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']]);
                if(empty($value)){
                    $value = $data[$field['column']];
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
        <?php $size++; } ?>
<?php } ?>