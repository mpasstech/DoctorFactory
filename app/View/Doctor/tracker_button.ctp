<?php if(!empty($data['data'])){  $data = $data['data']; ?>
    <div class="row row_box">
        <h4>Current Token Detail</h4>
        <div class="col-7 col_first">
            <table style="width: 100%;text-align: center;">
                <tr>
                    <th>Token Number</th>
                </tr>
                <tr>
                    <td><p style="width: 100%;display: block;text-align: center;float: left;"><?php echo  $data['queue_number'] ?> </p></td>
                </tr>
            </table>
        </div>
        <div class="col-5 col_second">
            <button data-id ='<?php echo base64_encode($data['appointment_id']) ?>' class="next_close_btn btn btn-xs btn-success">Close & Next</button>
        </div>
    </div>
<?php } ?>

<?php
    if(!empty($data['notification_array'])){
        Custom::send_process_to_background();
        Custom::close_appointment_notification($notification_array);
    }
?>
