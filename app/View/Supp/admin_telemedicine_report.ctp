<?php
$login = $this->Session->read('Auth.User');
echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

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
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_telemedicine_report','admin'=>true))); ?>
                                <div class="form-group">


                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('year', array('type' => 'select','empty'=>array('-1'=>'All'),'options'=>array("2019"=>"2019",'2020'=>'2020'),'label' => 'Year', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-md-2">
                                        <?php $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'); ?>
                                        <?php echo $this->Form->input('month', array('type' => 'select','empty'=>array('-1'=>'All'),'options'=>$months,'label' => 'Search By Month', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-md-2" style="width: 22%;">
                                        <?php $thinAppList = $this->SupportAdmin->getAllThinappDropdwon(); ?>

                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type'=>'select','label'=>"All App",'options'=>$thinAppList,'empty'=>'Select Thinapp','class'=>'form-control','value'=>@$this->request->query['t'])); ?>
                                    </div>



                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'telemedicine_report','admin'=>true)) ?>">Reset</a>

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
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>App Name</th>
                                                <th>Total Calls</th>

                                                <th>Doctor Share</th>
                                                <th>Call Charges</th>
                                                <th>GST Charges</th>
                                                <th>Menage Share</th>
                                                <th>Payment Getway</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $doctor_share=$payment_getway_share=$call_charges=$gst_share=$mengage_share=$payment_getway_share=0; $inr= '<i class="fa fa-inr" aria-hidden="true"></i> ';
                                            if(!empty($allData)){
                                            foreach ($allData as $key => $list){ ?>
                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td><?php echo $list['year_']; ?></td>
                                                <td><?php echo $list['month_name']; ?></td>
                                                <td><?php echo $list['app_name']; ?></td>
                                                <td><i class="fa fa-video-camera"></i> <?php echo $list['total_call']; ?></td>
                                                <!--<td><?php /*echo !empty($list['duration'])?ceil(($list['duration']/60)):'-'; */?></td>-->
                                                <td><?php $doctor_share += $list['doctor_share']; echo $inr; echo !empty($list['doctor_share'])?$list['doctor_share']:'0'; ?></td>
                                                <td><?php $call_charges += $list['call_charges']; echo $inr; echo !empty($list['call_charges'])?$list['call_charges']:'0'; ?></td>
                                                <td><?php $gst_share += $list['gst_share']; echo $inr; echo !empty($list['gst_share'])?$list['gst_share']:'0'; ?></td>
                                                <td><?php $mengage_share += $list['mengage_share']; echo $inr; echo !empty($list['mengage_share'])?$list['mengage_share']:'0'; ?></td>
                                                <td><?php $payment_getway_share += $list['payment_getway_share']; echo $inr; echo !empty($list['payment_getway_share'])?$list['payment_getway_share']:'0'; ?></td>

                                            <?php }} ?>
                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                               
                                                <th>Total</th>
                                                <th><?php echo $inr.$doctor_share; ?></th>
                                                <th><?php echo $inr.$call_charges; ?></th>
                                                <th><?php echo $inr.$gst_share; ?></th>
                                                <th><?php echo $inr.$mengage_share; ?></th>
                                                <th><?php echo $inr.$payment_getway_share; ?></th>

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

    .select2-container .select2-selection--single, .form-control{
        height:35px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style>


<script>
    $(document).ready(function(){


        $('#thinapp_id').select2();

        var column = [0,1,2,3,4,5,6,7,8,9,10];

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





