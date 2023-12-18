<?php if(!empty($statusHistory)){ ?>

    <table class="table">
        <tr>
            <th>STATUS</th>
            <th>COMMENTS</th>
            <th>TIME</th>
        </tr>
        <?php foreach($statusHistory AS $history){ ?>
            <tr>
                <td>
                    <lable><?php echo $history['SupplireOrderStatus']['status']; ?></lable>
                </td>
                <td>
                    <?php echo $history['SupplireOrderStatus']['comment']; ?>
                </td>
                <td>
                    <?php echo date("d/m/Y H:i:s",strtotime($history['SupplireOrderStatus']['created'])); ?>
                </td>
            </tr>
        <?php } ?>
    </table>

<?php } ?>


<?php if($hasForm == 'YES'){ ?>
    <form id="updateStatusHistoryForm" method="POST">
        <div class="form-group">
            <div class="col-sm-12">
                <input type="hidden" name="orderID" value="<?php echo $orderID; ?>">
                <input type="hidden" name="thinappID" value="<?php echo $thinappID; ?>">
                <select name="status" class="form-control cnt" required>
                    <option value="NEW">New</option>
                    <option value="ACCEPTED">Accepted</option>
                    <option value="REJECTED">Rejected</option>
                    <option value="INPROGRESS">Inprogress</option>
                    <option value="COMPLETED">Completed</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <input type="text" name="comment" placeholder="Comments" class="form-control cnt" >
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit"  name="submitForm" id="updateStatusBtn" class="form-control btn btn-primary" >Update</button>
            </div>
        </div>
    </form>

    <style>
        .modal-title {

            color: #FFF;
            font-weight: bold;

        }
        .modal-header {

            min-height: 16.43px;
            padding: 15px;
            border-bottom: 1px solid #03a9f5;
            background: #03a9f5;

        }
    </style>

    <script>
        $(document).off("submit","#updateStatusHistoryForm");
        $(document).on("submit","#updateStatusHistoryForm",function(e){
            e.preventDefault();
            var dataToSend = $(this).serialize();
            var $btn = $("#updateStatusBtn");
            $.ajax({
                url: baseurl+'/supplier/update_order_status_history',
                data:dataToSend,
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    var result = JSON.parse(result);
                    if(result.status != 1)
                    {
                        alert(result.message);
                    }
                    else
                    {
                        var ID = $('[name=orderID]').val();

                        $("#"+ID+"").text(result.message);
                        $("#editModal").modal("hide");
                    }
                    $btn.button('reset');

                }
            });

        });
    </script>

<?php } ?>