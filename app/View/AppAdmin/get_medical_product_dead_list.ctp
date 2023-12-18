<sctipr> var type= ''; </sctipr>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Dead List</h4>
        </div>
        <div class="modal-body">

            <form method="POST" id="add_form">

                <table class="table">
                    <tr>
                        <th colspan="3" align="center" style="text-align:center;"><button type="button" class="add_btn btn btn-primary btn-xs">Add</button></th>
                    </tr>
                    <tr class="hide_form" style="display: none;">
                        <td>
                            <div class="input text">
                                <input type="number" placeholder="Quantity" name="dead_quantity" class="form-control" value="1">
                                <input type="hidden" name="qty_id" value="<?php echo $qtyID; ?>">
                            </div>
                        </td>
                        <td>
                            <div class="input text">
                                <textarea name="comment" placeholder="Comments" style="height: 44px;" class="form-control" value=""></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="input submit">
                                <button type="submit" id="add" class="btn-success btn" >Submit</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hide_form_edit" style="display: none;">
                        <td>
                            <div class="input text">
                                <input type="number" placeholder="Quantity" id="dead_quantity"  name="dead_quantity_edit" class="form-control" value="1">
                                <input type="hidden" name="dead_id" id="dead_id" value="">
                            </div>
                        </td>
                        <td>
                            <div class="input text">
                                <textarea name="comment_edit" placeholder="Comments" id="comment" style="height: 44px;" class="form-control" value=""></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="input submit">
                                <button type="submit" id="edit" class="btn-success btn" >Update</button>
                            </div>
                        </td>
                    </tr>
                    <?php if(!empty($medicalProductDeadList)){ ?>
                        <tr>
                            <th>Qty</th>
                            <th>Comments</th>
                            <th>Option</th>
                        </tr>

                        <?php foreach($medicalProductDeadList AS $list){ ?>
                            <tr>
                                <td class="dead_qty"><?php echo $list['MedicalProductQuantityDead']['dead_quantity']; ?></td>
                                <td  class="comment"><?php echo $list['MedicalProductQuantityDead']['comment']; ?></td>
                                <td><button type="button" data-id="<?php echo $list['MedicalProductQuantityDead']['id']; ?>" class="edit_btn btn btn-primary btn-xs">Edit</button></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" align="center" style="text-align:center;">No List Found!</td>
                        </tr>
                    <?php } ?>

                </table>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).off('submit','#add_form');
    $(document).on('submit','#add_form',function(e){
        e.preventDefault();
        var dataToSend = $( this ).serialize();
        if(type == 'add')
        {
            var url = baseurl+"/app_admin/add_medical_product_dead_quantity";
        }
        else
        {
            var url = baseurl+"/app_admin/edit_medical_product_dead_quantity";
        }
        $.ajax({
            url:url,
            type:'POST',
            data:dataToSend,
            success:function(res){

                $('#dead_modal').modal('hide');

            },
            error:function () {
            }
        });
    });
</script>
<script>
    $(document).off('click','.add_btn');
    $(document).on('click','.add_btn',function(){
        type = 'add';
        $('.hide_form').show();
        $(this).parents('tr').hide();
        $('.hide_form_edit').hide();
    });
</script>
<script>
    $(document).off('click','.edit_btn');
    $(document).on('click','.edit_btn',function(){
        type = 'edit';
        $('.hide_form').hide();
        var deadQty = $(this).parent().siblings('.dead_qty').text();
        var comment = $(this).parent().siblings('.comment').text();
        var id = $(this).attr('data-id');
        $('#dead_quantity').val(deadQty);
        $('#dead_id').val(id);
        $('#comment').val(comment);
        $('.hide_form_edit').show();
    });
</script>
