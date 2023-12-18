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
                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">


                    <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_stats_report'),'admin'=>true)); ?>
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                        </div>



                                           <div class="col-md-3">
                                               <?php echo $this->Form->input('report_for', array('type' => 'select','options'=>array('USER_STATS'=>'Download Stats','APPOINTMENT_STATS'=>'Appointment Stats','PRESCRIPTION_STATS'=>'Prescription Stats'),'label' => 'Report for', 'class' => 'form-control')); ?>
                                           </div>

                                        <div class="col-md-3">
                                            <label style="display: block;">Search All Dates</label>
                                            <?php echo $this->Form->input('ignore_date', array('div'=>false,'type' => 'checkbox','label' => 'Ignore from date and to date', 'class' => 'ignore_date')); ?>
                                        </div>


                                        <div class="col-sm-2 action_btn" >
                                            <div class="input text">
                                                <label style="display: block;">&nbsp;</label>
                                                <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                                <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'app_stats_report_list','admin'=>true)) ?>">Reset</a>

                                            </div>

                                        </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                    </div>

                        <div class="Social-login-box payment_bx">
                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <?php if(!empty($data_array)){ ?>
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>App Name</th>
                                                <th>
                                                    <?php
                                                    if($report_for == 'USER_STATS'){
                                                        echo "Total Download";
                                                    }else if($report_for == 'APPOINTMENT_STATS'){
                                                        echo "Total Appointment";
                                                    }else if($report_for == 'PRESCRIPTION_STATS'){
                                                        echo "Total Prescription";
                                                    }
                                                    ?>

                                                </th>
                                                <?php if($report_for == 'USER_STATS'){ ?>
                                                <th>Total Active User</th>
                                                <th>Total Inactive User</th>
                                                <?php } ?>

                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $download = $active = $inactive =0; foreach($data_array as $key => $item){    ?>

                                            <tr class="table_row">
                                                <td><?php echo $key+1; ?></td>
                                                <td class="table_pat_name"><?php echo $item['app_name']; ?></td>
                                                <td><?php echo $item['total']; $download += $item['total']; ?></td>
                                                <?php if($report_for == 'USER_STATS'){ ?>
                                                <td><?php echo $item['installed']; $active += $item['installed']; ?></td>
                                                <td><?php echo $item['uninstalled'];$inactive += $item['uninstalled']; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php }  ?>

                                            <tr class="download_row">
                                                <td></td>
                                                <td>Total</td>
                                                <td><?php echo $download; ?></td>
                                                <?php if($report_for == 'USER_STATS'){ ?>
                                                    <td><?php echo $active; ?></td>
                                                    <td><?php echo $inactive; ?></td>
                                                <?php } ?>
                                            </tr>



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
                 [ 10, 25, 50, 100, 150, 200, -1 ],
                 [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
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



        $("head > title").text("<?php echo strtolower($report_for)."_on_".date("d-M-Y"); ?>");


        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});


    });




</script>