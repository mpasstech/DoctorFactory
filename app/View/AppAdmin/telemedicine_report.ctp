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
                    <h3 class="screen_title">Telemedicine Report</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_telemedicine_report'))); ?>
                                <div class="form-group">


                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('service_type', array('type' => 'select','options'=>array(''=>'ALL','AUDIO_CALL'=>'AUDIO','VIDEO_CALL'=>'VIDEO','CHAT'=>'CHAT'),'label' => 'Service Type', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('status', array('type' => 'select','options'=>array(''=>'ALL','completed'=>'Completed','other'=>'Not Completed'),'label' => 'Status', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>

                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'booking_convenience_report')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>




                            <div class="row">

                                    <div class="table-responsive">

                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>name</th>
                                                <th>Mobile</th>
                                                <th>Doctor</th>
                                                <th>Service Type</th>
                                                <th>Minutes</th>
                                                <th>Call Charges</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $total=$call_charges=0;
                                            if(!empty($allData)){
                                            foreach ($allData as $key => $list){ ?>
                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td><?php echo $list['name']; ?></td>
                                                <td><?php echo $list['mobile']; ?></td>
                                                <td><?php echo $list['doctor_name']; ?></td>
                                                <td><?php echo $list['telemedicine_service_type']; ?></td>
                                                <td><?php echo !empty($list['duration'])?ceil(($list['duration']/60)):'-'; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> <?php $call_charges += $list['call_charges']; echo !empty($list['call_charges'])?$list['call_charges']:'0'; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> <?php $total += $list['amount']; echo !empty($list['amount'])?$list['amount']:'0'; ?></td>
                                                <td><?php echo ($list['connect_status']=='completed')?'Completed':'Not Completed'; ?></td>
                                                <td><?php
                                                    if (!empty($list['start_time']) && $list['start_time'] != '0000-00-00 00:00:00'){
                                                        echo date('H:i:s', strtotime($list['start_time']));
                                                    }else{
                                                        echo "-";
                                                    }?>
                                                </td>
                                                <td><?php
                                                    if (!empty($list['end_time']) && $list['end_time'] != '0000-00-00 00:00:00'){
                                                        echo date('H:i:s', strtotime($list['end_time']));
                                                    }else{
                                                        echo "-";
                                                    }?>
                                                </td>
                                                    <td><?php echo date('d/m/Y H:i',strtotime($list['created']));?></td>

                                                </tr>
                                            <?php }} ?>
                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $call_charges; ?></th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $total; ?></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tfoot>
                                        </table>

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
</style>


<script>
    $(document).ready(function(){

        var column = [0,1,2,3,4,5,6,7,8,9,10,11];

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
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                }
            ]
        });
        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('Telemedicine Report');

    });
</script>





