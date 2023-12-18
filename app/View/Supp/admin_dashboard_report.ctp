<?php

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

$thinAppList = $this->SupportAdmin->getAllThinappDropdwon();
?>


<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>


    .select2-container .select2-selection--single{
        height:35px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }

    .col-md-2{
        width: 14%;
    }
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .middle-block {
        margin-top: 30px;
    }
    label {
        font-size: 0.8em;
    }
    .form-control{
        padding: 2px !important;
    }
    .search_row{
        padding-left: 15px;
    }
    tr td, th{
        padding: 2px 3px !important;
    }
    .search_row .col-md-1 ,.search_row .col-md-2, .col-md-3{
        padding-right: 1px;
        padding-left: 1px;
    }
    .search_row .col-md-1 input{
        padding: 6px 10px;

    }
    .search_row {
        margin-top: 30px;
    }
    .form-control{
        height: 35px;
    }
    .no_recode{
        width: 100%;
        text-align: center;
    }

    .report_tap a{
        width: auto !important;
        min-width: 15%;
        padding: 10px 10px !important;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">

            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <?php if($report_type=='dwc'){ ?>
                        <h3 class="screen_title">Doctor Wise Collection</h3>
                    <?php }else if($report_type=='dwcv'){ ?>
                        <h3 class="screen_title">Doctor Wise Clinic Visit</h3>
                    <?php }else if($report_type=='wac'){ ?>
                        <h3 class="screen_title">Web App Click</h3>
                    <?php }else if($report_type=='wai'){ ?>
                        <h3 class="screen_title">Wab App Installed</h3>
                    <?php }else if($report_type=='sms'){ ?>
                        <h3 class="screen_title">SMS Utility</h3>
                    <?php } else{ ?>
                        <h3 class="screen_title">Day Wise Report</h3>
                    <?php } ?>
                    <?php if($report_type=='dw'){  ?>
                        <div class="col-lg-12 right_box_div">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                            <?php echo $this->element('report_dashboard_tab'); ?>


                            <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report'),'admin'=>true)); ?>
                            <div class="form-group">

                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                </div>
                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                </div>


                                <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                    <div class="input text">
                                        <label style="display: block;">&nbsp;</label>
                                        <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report','admin'=>true)) ?>">Reset</a>
                                    </div>

                                </div>

                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                        <div class="Social-login-box payment_bx">

                            <div class="form-group row">

                                <?php if(!empty($list)){ ?>

                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Telemedicine With Token Fee</th>
                                                <th>Clinic Visit With Token Fee</th>
                                                <th>Telemedicine + Clinic Visit</th>
                                                <th>B2C Clinic Visit Without Token Fee</th>
                                                <th>Free Walk-in Token</th>
                                                <th>Total Clinic Visit Witout Token Fee</th>


                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $total = array(); foreach($list AS $key => $data){ ?>
                                                <tr>
                                                    <td><b><?php echo $key+1; ?>.</b></td>
                                                    <td><?php
                                                        echo date('l',strtotime($data['app_date']));

                                                        ?></td>
                                                    <td><?php echo $data['telemedicince_with_token']; $total['telemedicince_with_token'] +=$data['telemedicince_with_token'];   ?></td>
                                                    <td><?php echo $data['clinic_visit_with_token']; $total['clinic_visit_with_token'] +=$data['clinic_visit_with_token'];  ?></td>
                                                    <td><?php echo $tmp = ($data['telemedicince_with_token']+$data['clinic_visit_with_token']); $total['total_1'] +=$tmp;  ?></td>
                                                    <td><?php echo $data['b2c_clinic_visit_without_token']; $total['b2c_clinic_visit_without_token'] +=$data['b2c_clinic_visit_without_token'];  ?></td>
                                                    <td><?php echo $data['b2b_clinic_visit_without_token']; $total['b2b_clinic_visit_without_token'] +=$data['b2b_clinic_visit_without_token'];  ?></td>
                                                    <td><?php echo $tmp = ($data['b2c_clinic_visit_without_token']+$data['b2b_clinic_visit_without_token']); $total['total_2'] +=$tmp;  ?></td>
                                                </tr>
                                            <?php } ?>


                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th><?php echo $total['telemedicince_with_token']; ?></th>
                                                <th><?php echo $total['clinic_visit_with_token']; ?></th>
                                                <th><?php echo $total['total_1']; ?></th>
                                                <th><?php echo $total['b2c_clinic_visit_without_token']; ?></th>
                                                <th><?php echo $total['b2b_clinic_visit_without_token']; ?></th>
                                                <th><?php echo $total['total_2']; ?></th>
                                            </tr>





                                            </tbody>
                                            <tfoot

                                             <tr>
                                                <th></th>
                                                <th>Week Avg.</th>
                                                <th><?php echo Round($total['telemedicince_with_token']/$avgDivide); ?></th>
                                                <th><?php echo Round($total['clinic_visit_with_token']/$avgDivide); ?></th>
                                                <th><?php echo Round($total['total_1']/$avgDivide); ?></th>
                                                <th><?php echo Round($total['b2c_clinic_visit_without_token']/$avgDivide); ?></th>
                                                <th><?php echo Round($total['b2b_clinic_visit_without_token']/$avgDivide); ?></th>
                                                <th><?php echo Round($total['total_2']/$avgDivide); ?></th>
                                            </tr>


                                            </tfoot>




                                        </table>

                                    </div>


                                </div>
                                <?php }else{ ?>
                                    <h3 class="no_recode">No record found</h3>
                                <?php } ?>




                            </div>
                            <div class="clear"></div>

                        </div>



                    </div>
                    <?php }else if($report_type=='dwc'){  ?>
                        <div class="col-lg-12 right_box_div">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                                <?php echo $this->element('report_dashboard_tab'); ?>


                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report?rt=dwc'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report?rt=dwc','admin'=>true)) ?>">Reset</a>
                                        </div>

                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <?php if(!empty($list)){ ?>
                                            <div class="table table-responsive">
                                                <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>App Name</th>
                                                        <th>Video (fee x Token)</th>
                                                        <th>Audio (fee x Token)</th>
                                                        <th>Chat (fee x Token)</th>
                                                        <th>Offline Token</th>
                                                        <th>Video Token Fee</th>
                                                        <th>Total Token</th>
                                                        <th>Total Collection</th>
                                                        <th>Telemedicine Amount</th>
                                                        <th>Total Token Amount</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $total = array();
                                                    $counter = 1;

                                                    $total_array['total_token'] = 0;
                                                    $total_array['total_collection'] = 0;
                                                    $total_array['total_telemedicine'] = 0;
                                                    $total_array['total_token_amount'] = 0;

                                                    foreach($list AS $key => $data){
                                                        $all_total_token = 0;
                                                        $telemedicine_amount = 0;

                                                        ?>
                                                        <tr>
                                                            <td><b><?php echo $counter++; ?>.</b></td>
                                                            <td><?php echo $data['name']; ?></td>

                                                            <td><?php
                                                                    if(isset($data['VIDEO'])){
                                                                        foreach ($data['VIDEO'] as $fee =>$total_token){
                                                                            echo $fee.' X '.$total_token.'<br>';
                                                                            $all_total_token += $total_token;
                                                                            $telemedicine_amount += $fee * $total_token;
                                                                        }
                                                                    }
                                                            ?></td>



                                                            <td><?php
                                                                if(isset($data['AUDIO'])){
                                                                    foreach ($data['AUDIO'] as $fee =>$total_token){
                                                                        echo $fee.' X '.$total_token.'<br>';
                                                                        $all_total_token += $total_token;
                                                                        $telemedicine_amount += $fee * $total_token;
                                                                    }
                                                                }
                                                                ?></td>


                                                            <td><?php
                                                                if(isset($data['CHAT'])){
                                                                    foreach ($data['CHAT'] as $fee =>$total_token){
                                                                        echo $fee.' X '.$total_token.'<br>';
                                                                        $all_total_token += $total_token;
                                                                        $telemedicine_amount += $fee * $total_token;
                                                                    }
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                if(isset($data['OFFLINE'])){
                                                                    foreach ($data['OFFLINE'] as $fee =>$total_token){
                                                                        echo $fee.' X '.$total_token.'<br>';
                                                                        $all_total_token += $total_token;
                                                                    }
                                                                }
                                                                ?></td>

                                                            <td><?php echo $data['booking_convenience_fee_video'];  ?></td>
                                                            <td><?php
                                                                $total_array['total_token'] = $total_array['total_token']+$all_total_token;
                                                                    echo $all_total_token;


                                                                ?></td>
                                                            <td><?php

                                                                $total_array['total_collection'] = $total_array['total_collection']+$data['total_collection'];
                                                                echo $data['total_collection'];


                                                                ?></td>
                                                            <td><?php
                                                                $total_array['total_telemedicine'] = $total_array['total_telemedicine']+$telemedicine_amount;
                                                                echo $telemedicine_amount;

                                                                ?></td>
                                                            <td><?php
                                                                $total_array['total_token_amount'] = $total_array['total_token_amount']+$data['total_token_amount'];
                                                                echo $data['total_token_amount'];

                                                                ?></td>

                                                        </tr>
                                                    <?php } ?>








                                                    </tbody>


                                                    <tfoot

                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?php echo $total_array['total_token']; ?></th>
                                                        <th><?php echo $total_array['total_collection']; ?></th>
                                                        <th><?php echo $total_array['total_telemedicine']; ?></th>
                                                        <th><?php echo $total_array['total_token_amount']; ?></th>

                                                    </tr>


                                                    </tfoot>


                                                </table>
                                            </div>
                                        <?php }else{ ?>
                                            <h3 class="no_recode">No record found</h3>
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } else if($report_type=='dwcv'){  ?>

                        <div class="col-lg-12 right_box_div">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                                <?php echo $this->element('report_dashboard_tab'); ?>


                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report?rt=dwcv'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report?rt=dwcv','admin'=>true)) ?>">Reset</a>
                                        </div>

                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <?php if(!empty($list)){ ?>
                                        <div class="table table-responsive">
                                            <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>App Name</th>
                                                    <th>Clinic Visit With Token</th>
                                                    <th>Clinic Visite Without Token</th>


                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php $total = array();
                                                $total['clinic_visit_with_token'] = 0;
                                                $total['clinic_visit_no_token'] = 0;

                                                foreach($list AS $key => $data){ ?>
                                                    <tr>
                                                        <td><b><?php echo $key+1; ?>.</b></td>
                                                        <td><?php echo $data['app_name']; ?></td>
                                                        <td><?php echo $data['clinic_visit_with_token']; $total['clinic_visit_with_token'] +=$data['clinic_visit_with_token'];   ?></td>
                                                        <td><?php echo $data['clinic_visit_no_token']; $total['clinic_visit_no_token'] +=$data['clinic_visit_no_token'];  ?></td>

                                                    </tr>
                                                <?php } ?>








                                                </tbody>
                                                <tfoot
                                                <tr>
                                                    <th></th>
                                                    <th>Total</th>
                                                    <th><?php echo $total['clinic_visit_with_token']; ?></th>
                                                    <th><?php echo $total['clinic_visit_no_token']; ?></th>
                                                </tr>
                                                </tfoot>




                                            </table>
                                        </div>
                                        <?php }else{ ?>
                                            <h3 class="no_recode">No record found</h3>
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } else if($report_type=='wac'){  ?>

                        <div class="col-lg-12 right_box_div">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                                <?php echo $this->element('report_dashboard_tab'); ?>


                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report?rt=wac'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report?rt=wac','admin'=>true)) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <?php if(!empty($list)){ ?>
                                            <div class="table table-responsive">
                                                <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>App Name</th>
                                                        <th>Number of click</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $total = array();
                                                    $total['total'] = 0;


                                                    foreach($list AS $key => $data){ ?>
                                                        <tr>
                                                            <td><b><?php echo $key+1; ?>.</b></td>
                                                            <td><?php echo $data['name']; ?></td>
                                                            <td><?php echo $data['total']; $total['total'] +=$data['total'];   ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <tfoot
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?php echo $total['total']; ?></th>
                                                    </tr>
                                                    </tfoot>


                                                </table>
                                            </div>
                                        <?php }else{ ?>
                                            <h3 class="no_recode">No record found</h3>
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php }else if($report_type=='wai'){  ?>

                        <div class="col-lg-12 right_box_div">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                                <?php echo $this->element('report_dashboard_tab'); ?>


                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report?rt=wai'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report?rt=wai','admin'=>true)) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <?php if(!empty($list)){ ?>
                                            <div class="table table-responsive">
                                                <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>App Name</th>
                                                        <th>Doctor Name</th>
                                                        <th>Number of Installed</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $total = array();
                                                    $total['total_installed'] = 0;
                                                    foreach($list AS $key => $data){ ?>
                                                        <tr>
                                                            <td><b><?php echo $key+1; ?>.</b></td>
                                                            <td><?php echo $data['app_name']; ?></td>
                                                            <td><?php echo $data['doctor_name']; ?></td>
                                                            <td><?php echo $data['total_installed']; $total['total_installed'] +=$data['total_installed'];   ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <tfoot
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?php echo $total['total_installed']; ?></th>
                                                    </tr>
                                                    </tfoot>


                                                </table>
                                            </div>
                                        <?php }else{ ?>
                                            <h3 class="no_recode">No record found</h3>
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php }else if($report_type=='sms'){  ?>

                        <div class="col-lg-12 right_box_div">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">

                                <?php echo $this->element('report_dashboard_tab'); ?>


                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'search_dashboard_report?rt=sms'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp_id', array('id'=>'thinapp_id','type' => 'select','empty'=>'Show For All','options'=>$thinAppList,'label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'dashboard_report?rt=sms','admin'=>true)) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <?php if(!empty($list)){ ?>
                                            <div class="table table-responsive">
                                                <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>App Name</th>
                                                        <th>Total SMS</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $total = array();
                                                    $total['total'] = 0;
                                                    foreach($list AS $key => $data){ ?>
                                                        <tr>
                                                            <td><b><?php echo $key+1; ?>.</b></td>
                                                            <td><?php echo $data['app_name']; ?></td>
                                                            <td><?php echo $data['total']; $total['total'] +=$data['total'];   ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                    <tfoot
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?php echo $total['total']; ?></th>
                                                    </tr>
                                                    </tfoot>


                                                </table>
                                            </div>
                                        <?php }else{ ?>
                                            <h3 class="no_recode">No record found</h3>
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } ?>


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
    #example1_length {
        width: 32%;
        text-align: right;
    }
    .modal-header{
        background-color: #3a80bc;
        color: #FFFFFF;
        text-align: center;
    }
    .close {
        margin-top: 35px;
    }
</style>
<script>
    $(document).ready(function () {

        var report_type = "<?php echo $report_type; ?>";

        $(".report_tap").removeClass("active");
        $("#tab_"+report_type).addClass('active');



        $('#thinapp_id').select2();
        var columns = [];
        $("#data_table thead:first tr th").each(function (index,value) {
            if(!$(this).hasClass("action_btn")){
                columns.push(index);
            }
        });

        $('#data_table').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "aaSorting": [],
            "language": {
                "emptyTable": "No Data Found"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $title; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $title; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $title; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $title; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $title; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                }
            ]
        });

        var date = new Date();
        var last = (new Date(date.getTime() - (370 * 24 * 60 * 60 * 1000)));
        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy', startDate: last ,autoclose:true,orientation: "bottom auto",endDate: new Date()});
        $(".date_from, .date_to").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


        $(document).on('click','.pay_due_amount_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            var jc = $.confirm({
                title: 'Payment',
                content: 'Are you sure you have collected this amount?',
                type: 'yellow',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){

                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/pay_due_appointment",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('Wait..')
                                },
                                success:function(data){
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        jc.close();
                                        $(obj).closest("tr").find(".status_box").html("PAID");
                                        $btn.remove();

                                        var ID = response.order_id;
                                        window.open(baseurl+"app_admin/print_invoice/"+ID+"/DUE","_blank");
                                        window.location.reload();



                                    }else{
                                        $.alert(response.message);
                                        $btn.button('reset');
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });

        });
        $(document).on('click','.undo_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            var jc = $.confirm({
                title: 'Revert Payment',
                content: 'Are you sure you want to revert this amount?',
                type: 'yellow',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){

                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/refund_due_appointment",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('Wait..')
                                },
                                success:function(data){
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        jc.close();
                                        window.location.reload();

                                    }else{
                                        $.alert(response.message);
                                        $btn.button('reset');
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });

        });

        $("title").html("<?php echo $title; ?>");

    });
</script>
