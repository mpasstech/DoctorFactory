<label class="min_head">Previous Package Detail</label>
<div class="form-group">

    <?php if(!empty($dataToShow)){ ?>
        <table class="table">
            <tr>
                <td>#</td>
                <td>Doctor</td>
                <td>Package</td>
                <td>Price</td>
                <td>Discount</td>
                <td>Total Amount</td>
                <td>Total Received Amount</td>
                <td>Total Remaining Amount</td>
                <td>Is Open</td>
                <td>Option</td>
            </tr>
        <?php $key = 1; foreach($dataToShow AS $value){ ?>
            <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $value['AppointmentStaff']['name']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['service']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['product_price']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['discount_amount']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['total_amount']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['total_received_amount']; ?></td>
                <td><?php echo $value['MedicalPackageDetail']['total_outstanding_amount']; ?></td>
                <td><?php echo ($value['MedicalPackageDetail']['is_open'] == 'Y')?'Yes':'No'; ?></td>
                <td>
                    <?php if($value['MedicalPackageDetail']['is_open'] == 'Y'){ ?>
                        <button type="button" class="btn btn-xs btn-info select_this" data-id="<?php echo $value['MedicalPackageDetail']['id']; ?>">Select</button>
                        <button type="button" class="btn btn-xs btn-info close_this" data-id="<?php echo $value['MedicalPackageDetail']['id']; ?>">Close</button>
                    <?php } ?>

                </td>
            </tr>
        <?php $key++; } ?>
            <tr>
                <td colspan="10" align="center">
                    <button type="button" class="btn btn-md btn-info add_more_package">Add New Package</button>
                </td>
            </tr>
        </table>
    <?php }else{ ?>
        <table class="table">
            <tr>
                <td align="center">
                    <button type="button" class="btn btn-md btn-info add_more_package">Add New Package</button>
                </td>
            </tr>
        </table>
    <?php } ?>

</div>