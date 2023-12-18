<?php
$login = $this->Session->read('Auth.User');
?>

<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    td,th{
        font-size: 16px;
    }
    .search_main{
        margin-top: 28px;
        padding: 0px 0px;
        border-bottom: 1px solid;
    }

    .search_main input{
      padding: 6px !important;
    }

    .search_main .form-group div{
        margin-left: 1px !important;
        margin-right: 1px !important;
        padding:0px;
    }
    .action_btn{

        position: absolute;
        float: right;
        right: -35px;
    }
    .ignore_date{
        margin: 3px 5px;
        margin-top: 1px\9;
        line-height: normal;
        width: 20px;
        height: 20px;
        border-radius: 25px;
        border: 1px solid red;
        float: left;
        color: red;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h2>Montry Report Synchronize Cashfree</h2>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                    <div class="row search_main">
                        <H3>Uploade cashfee month report of success tranjaction Also upload paytm refund CSV of 2 Months</H3>
                                <?php echo $this->Form->create('Search',array('enctype'=>'multipart/form-data','url'=>array('controller'=>'supp','action' => 'read_excel'),'admin'=>true)); ?>
                                    <div class="form-group">
                                           <div class="col-md-4">
                                               <?php echo $this->Form->input('excel', array('type' => 'file','label' => 'Upload Cashfee Month Report', 'class' => 'form-control')); ?>
                                           </div>

                                        <div class="col-md-4">
                                            <?php echo $this->Form->input('paytm_refund', array('type' => 'file','label' => 'Upload Paytm Last Two Month Refund CSV', 'class' => 'form-control')); ?>
                                        </div>




                                        <div class="col-sm-2 action_btn" >
                                            <div class="input text">
                                                <label style="display: block;">&nbsp;</label>
                                                <?php echo $this->Form->submit('Generate',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                                <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'read_excel','admin'=>true)) ?>">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                    </div>

                        <div class="Social-login-box payment_bx">
                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <?php if(!empty($final_array)){ ?>
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>App Name</th>
                                                <th>App ID & E-mail</th>
                                                <th>Telemedicine 1</th>
                                                <th>Telemedicine 2</th>
                                                <th>Telemedicine 3</th>
                                                <th>Offline Token</th>
                                                <th>Total Token</th>
                                                <th>Total Fee</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $main_counter = 1; foreach($final_array as $key => $item){ $couter_array = array();

                                                $counter = $total_token = $token_amount = $total_token_counts = 0;

                                                $tm1 =$tm2 =$tm3 = "";

                                                foreach ($item['counts'] as $amount =>$count){

                                                    if($counter == 0 && $amount < 80 ){
                                                        $total_token = $count;
                                                        $token_amount = $amount;
                                                        $total_token_counts= $count;
                                                    }else{

                                                        if(empty($token_amount)){
                                                            $token_amount = $app_list[$item['data']['app_id']]['booking_convenience_fee'];
                                                        }

                                                        $amount = $amount- $token_amount;
                                                        $total_token_counts += $count;
                                                        if(empty($tm1)){
                                                            $tm1 = "($amount) X <b>$count</b>";
                                                        }else if(empty($tm2)){
                                                            $tm2 = "($amount) X <b>$count</b>";
                                                        }else if(empty($tm3)){
                                                            $tm3 = "($amount) X <b>$count</b>";
                                                        }
                                                    }
                                                   $counter++;

                                                }
                                            ?>

                                            <tr class="table_row">
                                                <td><?php echo $main_counter++; ?></td>
                                                <td class="table_pat_name"><?php echo @$app_list[$item['data']['app_id']]['name']; ?></td>
                                                <td class=""><?php echo $item['data']['email']; ?></td>
                                                <td class=""><?php echo $tm1; ?></td>
                                                <td class=""><?php echo $tm2; ?></td>
                                                <td class=""><?php echo $tm3; ?></td>
                                                <td class=""><?php echo "($token_amount) X <b>$total_token</b>"; ?></td>
                                                <td class=""><?php echo "<b>$total_token_counts</b>"; ?></td>
                                                <td class=""><?php echo "<b>$token_amount</b>"; ?></td>

                                            </tr>
                                            <?php }  ?>
                                            </tbody>
                                        </table>
                                        <?php }else{ ?>
                                            <h3 style="text-align: center;">No record found</h3>
                                        <?php } ?>

                                    </div>


                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>



                    </div>


                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>



<style>

    .download_row td{
        background: #e5a8f5 !important;
        font-size: 17px;
        font-weight: 600;
    }
    #example_length {
        width: 32%;
        text-align: right;
    }
    .modal-header{
        background-color: #3a80bc;
        color: #FFFFFF;
        text-align: center;
    }
</style>
<script>
    $(document).ready(function () {

        var table_obj ;
        var columnCount =[];
        $('#example thead tr th').each(function (index,value) {
            columnCount.push(index);
        });


         $('#example').DataTable({
            dom: 'Blfrtip',
             lengthMenu: [
                 [ 50, 100, 150, 200, -1 ],
                 [ '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
             ],
             "language": {
                "emptyTable": "No Data Found Related To Search"
                },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: columnCount
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: columnCount
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: columnCount
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: columnCount
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: columnCount
                    }

                }
            ]
        });



        $("head > title").text("<?php echo'Synchronize_report'."_on_".date("d-M-Y"); ?>");


        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});


    });




</script>