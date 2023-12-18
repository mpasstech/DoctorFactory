<style>
.message {
    width: 100% !important;
}
</style>
<?php
$reqData = $this->request->query;

?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Visitor Report</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('support_admin_leftsidebar'); ?>

                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                        <div class="Social-login-box dashboard_box" style="padding: 15px; 20px;">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_app_install_user'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-md-5">
                                    <?php echo $this->Form->input('name', array('type' => 'text',  'label' => 'App Name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $this->Form->input('username', array('type' => 'text', 'label' => 'Username Or Mobile', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php $list =array('INSTALLED'=>'INSTALLED','UNINSTALLED'=>'UNINSTALLED'); ?>
                                    <?php echo $this->Form->input('type', array('type' => 'select', 'options' => $list, 'label' => 'Status', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $this->Form->input('from_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'From Date', 'value'=>(@$reqData['f'])?$reqData['f']:date('d-m-Y'),'class' => 'form-control datetime from_date')); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $this->Form->input('to_date', array('type' => 'text', 'placeholder' => 'dd-mm-yyyy', 'label' => 'To Date', 'value'=>(@$reqData['t'])?$reqData['t']:date('d-m-Y'), 'class' => 'form-control datetime to_date')); ?>
                                </div>
                                <div class="col-md-3 btn_div">
                                    <label>Action</label><br>
                                    <button type="submit" class="btn btn-info" id="search">Search</button>
                                    <a href="<?php echo Router::url('/admin/supp/app_install_user'); ?>" class="btn btn-info">Reset</a>

                                </div>


                            </div>

                            <?php echo $this->Form->end(); ?>




                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-responsive ">

                                            <thead>
                                            <tr >
                                                <th>#</th>
                                                <th width="200">App Name</th>
                                                <th>Patient Name</th>
                                                <th>Patient Mobile</th>
                                                <th>Install Status</th>
                                                <th>Date</th>

                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php
                                            if(isset($data) && !empty($data))
                                            {
                                                foreach($data as $key => $val)
                                                {

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>

                                                        <td><?php  echo $val['Thinapp']['name']; ?></td>
                                                        <td><?php echo $val['User']['username']; ?></td>
                                                        <td><?php echo $val['User']['mobile']; ?></td>
                                                        <td><?php echo $val['User']['app_installed_status']; ?></td>
                                                        <?php $date  = ($val['User']['app_installed_status']=="INSTALLED")?$val['User']['created']:$val['User']['modified']; ?>
                                                        <td><?php echo date('d-m-Y',strtotime($date)); ?></td>

                                                    </tr>
                                                <?php }
                                            }else{ ?>
                                                <tr>
                                                    <td colspan="5"> No user found.</td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>


                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="clear"></div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        $('.from_date, .to_date').datepicker({autoclose:true,format:'dd-mm-yyyy'});


    });

</script>
