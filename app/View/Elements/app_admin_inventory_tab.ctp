<?php

$login = $this->Session->read('Auth.User');
$total_admit =$this->AppAdmin->totalAdmitPateint($login['User']['thinapp_id'],'ADMIT');
$total_discharge =$this->AppAdmin->totalAdmitPateint($login['User']['thinapp_id'],'DISCHARGE');
$total_to_discharge =$this->AppAdmin->totalToDischargePateint($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);

?>
<div class="add_btn">
    <a href="<?php echo Router::url('/app_admin/get_bed_occupancy',true); ?>" class="btn btn-success "><i class="fa fa-file-text-o"></i> Bed Occupancy</a>
    <a href="javascript:void(0);" class="btn btn-success get_bed_status"><i class="fa fa-file-text-o"></i> Bed Status</a>
    <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>" class="btn btn-success billing_btn"><i class="fa fa-file-text-o"></i> Billing</a>
</div>
<div class="progress-bar channel_tap">
    <a style="width: 20%;" id="all_tab" href="<?php echo Router::url('/app_admin/ipd_all'); ?>"><i class="fa fa-list"> </i> All Patients </a>
    <a style="width: 20%;" id="opd_tab" href="<?php echo Router::url('/app_admin/opd'); ?>" ><i class="fa fa-list"> </i> OPD Patients</a>
    <a style="width: 20%; border-right: none;" id="admit_tab" href="<?php echo Router::url('/app_admin/ipd?lt=a'); ?>" ><i class="fa fa-list"> </i> Admitted (<?php echo $total_admit; ?>) </a>
    <a style="width: 20%; border-right: none;" id="to_discharge_tab" href="<?php echo Router::url('/app_admin/ipd?lt=td'); ?>" ><i class="fa fa-list"> </i> To Discharge (<?php echo $total_to_discharge; ?>)</a>
    <a style="width: 20%; border-right: none;" id="discharge_tab" href="<?php echo Router::url('/app_admin/ipd?lt=d'); ?>" ><i class="fa fa-list"> </i> Discharged (<?php echo $total_discharge; ?>)</a>
</div>



<style>
    /*.billing_btn{
        float: right;
        position: absolute;
        right: 0;
        top: -57px;
    } */

    .add_btn {
        position: absolute;
        right: 0;
        position: absolute;
        bottom: 160px !important;

    }
</style>

<script>

    var action = '<?php echo $this->params['action']; ?>';
    $(function(){
        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            if(!$('.modal').is(':visible') && action !='ipd' && action != 'opd' && action!= 'ipd_all') {
                clickShortCutEvent(e);
            }
        });

        function clickShortCutEvent(e){


            if(e.keyCode==98){
                /*key code = B*/
                e.preventDefault();
                window.location.href = "<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>";
            }
        }


    })
</script>