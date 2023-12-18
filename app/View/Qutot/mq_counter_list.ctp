<div class="modal fade" id="counterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-gear" aria-hidden="true"></i> Counter Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if(!empty($data)){ ?>
                        <table id="customers" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Counter</th>
                            <th>Assign For</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $key => $list) { ?>
                            <tr>
                                <td><?php echo $key+1; ?></td>
                                <td><?php echo $list['counter']; ?></td>
                                <td><?php echo $list['doctor_name']; ?></td>
                                <td class="btn_td">
                                    <button data-c="<?php echo $list['counter']; ?>" data-status="<?php echo $list['status']; ?>" data-id="<?php echo base64_encode($list['id'])?>" class="status_btn btn btn-sx btn-<?php echo ($list['status']=='OPEN')?'success':'danger'; ?>" ><?php echo $list['status']; ?></button>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php }else{ ?>
                        <h6 style="text-align: center;width: 100%;">No Counter List</h6>
                    <?php } ?>
                </div>
            </div>
        </div>

    <script>
        $(function () {
            $(document).off("click",".status_btn");

        })
    </script>
   </div>



