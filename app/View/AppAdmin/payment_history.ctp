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
                    <h3 class="screen_title">Payment History</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>

                        <?php echo $this->element('app_admin_payment_history_inner_tab'); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_payment_history'))); ?>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Sender Name', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Sender Mobile', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>



                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('account_type', array('type' => 'select','options'=>array('ALL'=>'Both','MENGAGE'=>'MEngage','APP_ADMIN'=>$login['Thinapp']['name']),'label' => 'A/c Type', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'payment_history')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                            <div class="row">
                                <?php if(!empty($subscriber)){ ?>
                                    <div class="table-responsive">

                                    <table id="example" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Account Type</th>
                                        <th>Sender name</th>
                                        <th>Sender Mobile</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($subscriber as $key => $list){ ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><?php

                                                echo ($list['UserPayment']['payment_receive_account']=='APP_ADMIN')?$list['Thinapp']['name']:"MEngage";

                                            ?></td>
                                            <td><?php echo $list['Sender']['username'];?></td>
                                            <td><?php echo $list['UserPayment']['payment_sender'];?></td>
                                            <td><?php echo $list['UserPayment']['total_price'];?></td>
                                            <td><?php echo $list['UserPayment']['transaction_status'];?></td>
                                            <td><?php echo date('d-m-Y H:i',strtotime($list['UserPayment']['created']));?></td>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>
                           <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No payment history found</h2>
                            </div>
                        <?php } ?>
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
</style>


<script>
    $(document).ready(function(){


        $("#app_pay").removeClass('active');
        $("#user_pay").addClass('active');
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
                        columns: [1,2,3,4]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }

                }
            ]
        });
        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('user_payment');

    });
</script>





