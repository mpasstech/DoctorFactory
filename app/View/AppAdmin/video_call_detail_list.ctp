<div class="modal fade" id="video_call_history_modal">

    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;"><?php echo @ucfirst(strtolower($final_array[0]['type']))?> Call History</h4>
            </div>
            <div class="modal-body">

                <?php $inr = "<i class ='fa fa-inr'></i> "; ?>

                <?php if(!empty($final_array)){ ?>
                    <div class="table-responsive">
                        <table id="assocate_table" class="table" style="margin-bottom:0;">
                            <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Call Start</th>
                                <th>Call End</th>
                                <th>Minutes</th>
                                <th>Call Charges</th>
                                <th>Call Status</th>
                                <th>Date</th>
                            </tr>


                            </thead>
                            <tbody>
                            <?php $total_pay = 0; foreach ($final_array as $key => $list){ ?>
                                <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo date('H:i',strtotime($list['start_time'])); ?></td>
                                    <td><?php echo date('H:i',strtotime($list['end_time'])); ?></td>
                                    <td><?php echo ceil($list['duration']/60); ?></td>
                                    <td><?php $total_pay += $list['call_charges']; echo $inr.$list['call_charges']; ?></td>
                                    <td><?php echo ucfirst($list['connect_status']); ?></td>
                                    <td><?php echo date('d/m/Y h:i A',strtotime($list['created'])); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align: right;">Total</th>
                                <th ><?php echo $inr.$total_pay; ?></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                <?php }else{ ?>
                    <h3 style="text-align: center;"> No video call history yet  </h3>
                <?php } ?>

            </div>







</div>


