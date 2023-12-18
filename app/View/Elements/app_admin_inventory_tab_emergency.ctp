<a title="Press 'B' for access billing" href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>" class="btn btn-success  billing_btn"><i class="fa fa-file-text-o"></i> Billing</a>

<div class="progress-bar channel_tap">
    <a style="width: 33.33%;" id="all_tab" href="<?php echo Router::url('/app_admin/ipd_all_emergency'); ?>"><i class="fa fa-list"> </i> All Patients</a>
    <a style="width: 33.33%;" id="opd_tab" href="<?php echo Router::url('/app_admin/opd_emergency'); ?>" ><i class="fa fa-list"> </i> OPD Patients</a>
    <a style="width: 33.33%; border-right: none;" id="emergency_tab" href="<?php echo Router::url('/app_admin/list_hospital_emergency'); ?>" ><i class="fa fa-list"> </i> Emergency Patients</a>
   </div>

<style>
    .billing_btn{
        float: right;
        position: absolute;
        right: 0;
        top: -42px;
    }
</style>

<script>
    $(function(){
        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            if(!$('.modal').is(':visible')) {
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