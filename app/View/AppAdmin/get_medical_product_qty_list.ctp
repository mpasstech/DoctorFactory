<?php
if($type == 'pharma'){
    foreach($data AS $list){ ?>
        <tr class="added_row">
            <td></td>
            <td></td>
            <td><?php echo $list['MedicalProduct']['name']; ?>(<?php echo $list['MedicalProduct']['medicine_form']; ?>)</td>
            <td>
                <?php
                if($list['MedicalProductQuantity']['expiry_date'] != '0000-00-00')
                {
                    echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['expiry_date']));
                }
                else
                {
                    echo "Not Available";
                }
                ?>
            </td>
            <td><?php echo $list['MedicalProductQuantity']['batch']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
            <td><span class="anchor" data-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php echo $list['MedicalProductQuantity']['dead']; ?></span></td>
            <td><?php echo $list['MedicalProductQuantity']['quantity']-($list['MedicalProductQuantity']['dead']+$list['MedicalProductQuantity']['sold']); ?></td>
            <td><?php echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['created'])); ?></td>
            <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>
        </tr>
<?php } }
else if($type == 'lab') {
    foreach ($data AS $list) {
        ?>
        <tr class="added_row">
            <td></td>
            <td></td>
            <td><?php echo $list['MedicalProduct']['name']; ?>
                (<?php echo $list['MedicalProduct']['medicine_form']; ?>)
            </td>
            <td>
                <?php
                if ($list['MedicalProductQuantity']['expiry_date'] != '0000-00-00') {
                    echo date('d/m/Y', strtotime($list['MedicalProductQuantity']['expiry_date']));
                } else {
                    echo "Not Available";
                }
                ?>
            </td>
            <td><?php echo $list['MedicalProductQuantity']['batch']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
            <td><span class="anchor"
                      data-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php echo $list['MedicalProductQuantity']['dead']; ?></span>
            </td>
            <td><?php echo $list['MedicalProductQuantity']['quantity'] - ($list['MedicalProductQuantity']['dead'] + $list['MedicalProductQuantity']['sold']); ?></td>
            <td><?php echo date('d/m/Y', strtotime($list['MedicalProductQuantity']['created'])); ?></td>
            <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>
        </tr>
<?php } }
else if($type == 'hospital') {
    foreach ($data AS $list) {
        ?>
        <tr class="added_row">
            <td></td>
            <td></td>
            <td><?php echo $list['MedicalProduct']['name']; ?>(<?php echo $list['MedicalProduct']['medicine_form']; ?>)</td>
            <td>
                <?php
                if($list['MedicalProductQuantity']['expiry_date'] != '0000-00-00')
                {
                    echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['expiry_date']));
                }
                else
                {
                    echo "Not Available";
                }
                ?>
            </td>
            <td><?php echo $list['MedicalProductQuantity']['batch']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
            <td><span class="anchor" data-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php echo $list['MedicalProductQuantity']['dead']; ?></span></td>
            <td><?php echo $list['MedicalProductQuantity']['quantity']-($list['MedicalProductQuantity']['dead']+$list['MedicalProductQuantity']['sold']); ?></td>
            <td><?php echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['created'])); ?></td>
            <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
            <td><?php echo $list['MedicalProduct']['module_type']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>

        </tr>
<?php } }
else if($type == 'service') {
    foreach ($data AS $list) {
        ?>
        <tr class="added_row">
            <td></td>
            <td></td>
            <td><?php echo $list['MedicalProduct']['name']; ?>(<?php echo $list['MedicalProduct']['medicine_form']; ?>)</td>
            <td>
                <?php
                if($list['MedicalProductQuantity']['expiry_date'] != '0000-00-00')
                {
                    echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['expiry_date']));
                }
                else
                {
                    echo "Not Available";
                }
                ?>
            </td>
            <td><?php echo $list['MedicalProductQuantity']['batch']; ?></td>
            <!--td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td-->
            <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
            <td><span class="anchor" data-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php echo $list['MedicalProductQuantity']['dead']; ?></span></td>
            <!--td><?php echo $list['MedicalProductQuantity']['quantity']-($list['MedicalProductQuantity']['dead']+$list['MedicalProductQuantity']['sold']); ?></td-->
            <td><?php echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['created'])); ?></td>
            <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
            <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>
        </tr>
<?php } }?>