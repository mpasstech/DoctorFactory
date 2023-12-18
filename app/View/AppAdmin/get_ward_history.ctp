<div class="modal fade" id="ward_history" role="dialog" keyboard="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center;font-weight: bold;">Ward/Bed History</h4>
                </div>
                <div class="modal-body">

                    <div class="table-responsive ward_tistory_table_container">
                        <table  class="table">
                            <?php if(!empty($dataToSend)){ ?>
                                <tr>
                                    <th>Occupancy</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Ward</th>
                                    <th>Bed/Room</th>
                                </tr>
                                <?php foreach($dataToSend AS $data){ ?>
                                    <tr>
                                        <td><?php echo $data['occupancy']; ?></td>
                                        <td><?php echo date("d/m/Y H:i",strtotime($data['from_date'])); ?></td>
                                        <td><?php echo $data['to_date']!=''?date("d/m/Y H:i",strtotime($data['to_date'])):''; ?></td>
                                        <td><?php echo $data['ward_name']; ?></td>
                                        <td><?php echo $data['bed_name']; ?></td>
                                    </tr>
                                <?php } ?>

                            <?php }else{ ?>
                                <tr><td align="center">No data found!</td></tr>
                            <?php } ?>
                        </table>

                    </div>

                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    <style>.modal-header {

            background-color: #03A9F5;
            color: #FFF;

        }</style>
</div>
