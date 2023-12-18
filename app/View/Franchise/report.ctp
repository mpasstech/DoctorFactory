<?php
$login = $this->Session->read('Auth.User.SmartClinicMediator');
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
                    <h3 class="screen_title">Report</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx" style="float: left;">

                            <div class="form-group" style="float: left;width: 100%;">
                                <div class="col-sm-12" style="padding: 0px;">
                                    <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'franchise','action' => 'search_report'))); ?>
                                    <div class="form-group" style="float: left;width: 100%;background: #ededed;">




                                        <div class="col-md-1">
                                            <?php echo $this->Form->input('search_by', array('id'=>'search_by','type' => 'select','options'=>array("d"=>"Date",'m'=>'Month'),'label' => 'Search By', 'class' => 'form-control')); ?>
                                        </div>

                                        <div class="col-md-2 month_box">
                                            <?php $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'); ?>
                                            <?php echo $this->Form->input('month', array('type' => 'select','empty'=>'All','options'=>$months,'label' => 'Search By Month', 'class' => 'form-control')); ?>
                                        </div>


                                        <div class="col-md-2 date_box" >
                                            <?php echo $this->Form->input('from_date', array('autocomplete'=>'off', 'type'=>'text','placeholder' => '', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                        </div>
                                        <div class="col-md-2 date_box" >
                                            <?php echo $this->Form->input('to_date', array('autocomplete'=>'off', 'type'=>'text','placeholder' => '', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                        </div>



                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('thinapp_id', array('type' => 'select','empty'=>'All','options'=>$this->Franchise->getAssociateAppListOption($login['id']),'label' => 'Search By App', 'class' => 'form-control')); ?>
                                        </div>

                                        <div class="col-md-1" style="display: none;">
                                            <?php echo $this->Form->input('settled', array('type' => 'select','empty'=>'All','options'=>array("YES"=>"Yes",'NO'=>'No'),'label' => 'Settled', 'class' => 'form-control')); ?>
                                        </div>

                                        <div class="col-md-2">
                                            <?php

                                                $role_array = array(
                                                        'primary_owner_id'=>$this->Franchise->getFranchiseRoleLabel('primary_owner_id'),
                                                        'secondary_owner_id'=>$this->Franchise->getFranchiseRoleLabel('secondary_owner_id'),
                                                        'primary_mediator_id'=>$this->Franchise->getFranchiseRoleLabel('primary_mediator_id'),
                                                        'secondary_mediator_id'=>$this->Franchise->getFranchiseRoleLabel('secondary_mediator_id'),
                                                );

                                            echo $this->Form->input('role', array('type' => 'select','empty'=>'All','options'=>$role_array,'label' => 'Search By Role', 'class' => 'form-control')); ?>
                                        </div>




                                        <div class="col-sm-2" style="padding: 0;" >
                                            <div class="input text">
                                                <label style="display: block;">&nbsp;</label>
                                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                                <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'franchise','action'=>'report')) ?>"><i class="fa fa-refresh"></i> Reset</a>

                                            </div>

                                        </div>

                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                            <div class="form-group" style=" float: left; width: 100%;">
                                <div class="col-sm-12">
                                    <?php $final_total = 0; $primary_owner_total = $secondary_owner_total = $primary_mediator_total = $secondary_mediator_total = 0;  if(!empty($data_list)){ ?>
                                        <div class="table-responsive">

                                            <table id="report_table" class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>App Name</th>
                                                    <th><?php echo ($search_by=="MONTH")?"Month":"Date"; ?></th>
                                                    <th><?php echo $this->Franchise->getFranchiseRoleLabel('primary_owner_id')." Fee"; ?></th>
                                                    <th><?php echo $this->Franchise->getFranchiseRoleLabel('secondary_owner_id')." Fee"; ?></th>
                                                    <th><?php echo $this->Franchise->getFranchiseRoleLabel('primary_mediator_id')." Fee"; ?></th>
                                                    <th><?php echo $this->Franchise->getFranchiseRoleLabel('secondary_mediator_id')." Fee"; ?></th>
                                                    <th>Settled</th>
                                                    <th>Total Fee</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $role = @$this->request->query['r']; foreach ($data_list as $key => $list){  $total_fee=0; $inr = "<i class='fa fa-inr'></i> "; ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td><?php echo $list['app_name'] ?></td>
                                                        <td><?php echo $list['label'];?></td>

                                                        <td><?php
                                                                if((empty($role) || $role=='primary_owner_id')){
                                                                    echo $inr. $list['primary_owner_share_fee']; $total_fee  +=$list['primary_owner_share_fee'];
                                                                    $primary_owner_total += $list['primary_owner_share_fee'];
                                                                }
                                                             ?>
                                                        </td>
                                                        <td><?php
                                                            if((empty($role) || $role=='secondary_owner_id')){
                                                                echo $inr. $list['secondary_owner_share_fee']; $total_fee +=$list['secondary_owner_share_fee'];
                                                                 $secondary_owner_total +=$list['secondary_owner_share_fee'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php
                                                            if((empty($role) || $role=='primary_mediator_id')){
                                                                echo $inr. $list['primary_mediator_share_fee']; $total_fee +=$list['primary_mediator_share_fee'];
                                                                $primary_mediator_total +=$list['primary_mediator_share_fee'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php
                                                            if((empty($role) || $role=='secondary_mediator_id')){
                                                                echo $inr. $list['secondary_mediator_share_fee']; $total_fee +=$list['secondary_mediator_share_fee'];
                                                                $secondary_mediator_total +=$list['secondary_mediator_share_fee'];
                                                            }
                                                            ?>
                                                        </td>

                                                        <td><?php echo $list['is_settled'] ?></td>
                                                        <td><?php echo $inr.$total_fee; ?></td>

                                                    </tr>
                                                <?php $final_total += $total_fee; } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>

                                                        <th><?php echo $inr.$primary_owner_total; ?></th>
                                                        <th><?php echo $inr.$secondary_owner_total; ?></th>
                                                        <th><?php echo $inr.$primary_mediator_total; ?></th>
                                                        <th><?php echo $inr.$secondary_mediator_total; ?></th>


                                                        <th>Total</th>
                                                        <th><?php echo $inr.$final_total; ?></th>

                                                    </tr>
                                                </tfoot>
                                            </table>


                                        </div>
                                    <?php }else{ ?>
                                        <h2 class="no_data"> No record found  </h2>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- box 1 -->


    </div>
    <!--box 2 -->

</div>

<style>

    form div[class*="col-md-"]{
        padding: 0px 5px;
    }
    form .form-control{
        height: 35px;
    }
    .date_box, .month_box{
        width: 10%;
    }

    .searchBtn{ margin-top: 28px; }
    .huge{
        font-size: 40px;
    }
    .no_data{
        text-align: center;
        font-size: 20px;
    }

    .panel-heading{
        padding: 10px 4px;
    }
    .panel-heading .text-right{
        right: 17px;
        padding: 0px;
        text-align: right;

        position: absolute;
    }
    .table_row{
        border-top: 2px solid #afabab;
    }

    td, th{
        padding: 3px 2px !important;
    }
    .lbl_heading{
        font-size: 16px;
        padding: 20px 0px 0px 0px;
        margin: 0px;
        width: 100%;
        font-weight: 600;
    }
    .dt-buttons button{
        padding: 2px 2px !important;
    }
</style>
<script>






    $(document).ready(function(){

        $(document).on("change","#search_by",function () {
            if($(this).val()=="m"){
                $(".month_box").show();
                $(".date_box").hide();

            }else{
                $(".date_box").show();
                $(".month_box").hide();

            }
        });

        $("#search_by").trigger("change");

        var primary_owner_total = "<?php echo $primary_owner_total; ?>";
        var secondary_owner_total = "<?php echo $secondary_owner_total; ?>";
        var primary_mediator_total = "<?php echo $primary_mediator_total; ?>";
        var secondary_mediator_total = "<?php echo $secondary_mediator_total; ?>";


        var hide_columns =[];
        if(primary_owner_total == 0){
            hide_columns.push(3);
        }
        if(secondary_owner_total == 0){
            hide_columns.push(4);
        }if(primary_mediator_total == 0){
            hide_columns.push(5);
        }if(secondary_mediator_total == 0){
            hide_columns.push(6);
        }


        $('#report_table').DataTable({
            dom: 'Blfrtip',
            "aoColumnDefs": [{ "bVisible": false, "aTargets": hide_columns }],
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            "oLanguage": { "sSearch": "" },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: 'Report',
                    title: '',
                    exportOptions: {
                        columns:  [':visible :not(:last-child)']
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: 'Report',
                    title: '',
                    exportOptions: {
                        columns:  [':visible :not(:last-child)']
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: 'Report',
                    title: '',
                    exportOptions: {
                        columns:  [':visible :not(:last-child)']
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: 'Report',
                    title: '',
                    exportOptions: {
                        columns:  [':visible :not(:last-child)']
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: 'Report',
                    title: '',
                    exportOptions: {
                        columns:  [':visible :not(:last-child)']
                    }

                }
            ]
        });


        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});
    });
</script>



