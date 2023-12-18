<?php
$login = $this->Session->read('Auth.User');
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Telemedicine Setting</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'telemedicine','action' => 'setting'))); ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                            <div class="table-responsive">
                                <h3>Question Setting</h3>
                                <table id="question_table" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 35%;">Question</th>
                                        <th style="width: 10%;">Type</th>
                                        <th>Option 1</th>
                                        <th>Option 2</th>
                                        <th>Option 3</th>
                                        <th>Option 4</th>
                                        <th style="width: 4%;">Order Number</th>
                                        <th style="width: 8%;">Status</th>

                                    </tr>
                                    </thead>
                                    <tbody >
                                    <?php if(!empty($question_list)){
                                        foreach ($question_list as $key => $list){ $id = $list['id'] ?>

                                            <tr class="active">
                                                <td class="sr_number"><?php echo $key+1; ?></td>

                                                <td><input data-name='question' name="update[<?php echo $id; ?>]['question']" class="form-control" type="text" value="<?php echo $list['question']; ?>"></td>
                                                <td>
                                                    <select data-name='type' name="update[<?php echo $id; ?>]['type']" class="form-control">
                                                        <option value="TEXT">Text</option>
                                                        <option value="CHECKBOX">Checkbox</option>
                                                        <option value="RADIO">Radio</option>
                                                    </select>
                                                </td>
                                                <td><input data-name='option_one' name="update[<?php echo $id; ?>]['option_one']" class="form-control" type="text" value="<?php echo $list['option_one']; ?>"></td>
                                                <td><input data-name='option_two' name="update[<?php echo $id; ?>]['option_two']" class="form-control" type="text" value="<?php echo $list['option_two']; ?>"></td>
                                                <td><input data-name='option_three' name="update[<?php echo $id; ?>]['option_three']" class="form-control" type="text" value="<?php echo $list['option_three']; ?>"></td>
                                                <td><input data-name='option_four' name="update[<?php echo $id; ?>]['option_four']" class="form-control" type="text" value="<?php echo $list['option_four']; ?>"></td>
                                                <td><input data-name='order_number' name="update[<?php echo $id; ?>]['order_number']" class="form-control" type="text" value="<?php echo $list['order_number']; ?>"></td>
                                                <td>
                                                    <select  data-name='status' name="update[<?php echo $id; ?>]['status']" class="form-control">
                                                        <option value="ACTIVE">Active</option>
                                                        <?php if($key > 0){ ?>
                                                            <option value="INACTIVE">Delete</option>
                                                        <?php } ?>

                                                    </select>
                                                </td>
                                            </tr>
                                        <?php }} ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                                <td colspan="5"></td>
                                                <td colspan="4" style="text-align: right;">
                                                    <button type="reset" class="btn btn-info add_more">Add New Queston</button>
                                                    <button type="reset" class="btn btn-warning resteButton">Reset</button>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>

                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>

<style>

    .form-control{
        height: 37px;
        padding: 6px 4px;
    }
    #example_length {
        width: 32%;
        text-align: right;
    }
    .heading_lable {
        text-align: center;
        background:#d6d6d6;
        padding: 5px 0px;
        float: left;
        width: 100%;
    }
    .all-orders {
        margin-top: -3px;
        float: left;
        display: table;
    }
    .remove_row{
        position: absolute;
        float: right;
        right: -23px;
        margin-top: 6px;
        font-size: 19px;
        background: red;
        color: #fff;
        padding: 5px;
        border-radius: 40px;
    }
</style>


<script>
    $(document).ready(function(){

        var column = [0,1,2,3,4,5,6,7,8,9,10,11];

        function resetNumber(){
            $("#question_table .sr_number").each(function (index,value) {
                $(this).html(index+1);
            });
        }
      $(document).on('click',".add_more",function () {
          var randodm = Math.floor(Math.random() * (999 + 1) + 1);
          var html = "<tr class='active "+randodm+"'>"+$("#question_table tbody tr:first").html()+"</tr>";
          $("#question_table tbody").append(html);
          var remove_btn = "<a href='javascript:void(0);' class='remove_row'><i class='fa fa-trash'></i></a>";
          $("#question_table tbody tr:last td:last").prepend(remove_btn);
          resetNumber();
          $("."+randodm).find('[type="text"]').attr('value',"");




      });


        $(document).on('click',".remove_row",function () {
           $(this).closest('tr').remove();
            resetNumber();
        })


        $("head > title").text('Telemedicine Report');

    });
</script>





