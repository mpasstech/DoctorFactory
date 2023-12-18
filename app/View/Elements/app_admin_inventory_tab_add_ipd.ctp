<?php

$login = $this->Session->read('Auth.User');
$total_admit =$this->AppAdmin->totalAdmitPateint($login['User']['thinapp_id'],'ADMIT');
$total_discharge =$this->AppAdmin->totalAdmitPateint($login['User']['thinapp_id'],'DISCHARGE');
$total_to_discharge =$this->AppAdmin->totalToDischargePateint($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);

?>
<div class="add_btn">
    <a href="javascript:void(0);" class="btn btn-success get_bed_status"><i class="fa fa-file-text-o"></i> Bed Status</a>
    <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>" class="btn btn-success billing_btn"><i class="fa fa-file-text-o"></i> Billing</a>
</div>

<style>
    /*.billing_btn{
        float: right;
        position: absolute;
        right: 0;
        top: -57px;
    } */
    .custom_form_box {

        margin: 40px 0px !important;

    }
    .add_btn {
        position: absolute;
        right: 0;


    }
</style>

<script>
    $(function(){
        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            if(!$('.modal').is(':visible')) {
                if(!$("input,textarea,select").is(":focus")){
                    clickShortCutEvent(e);
                }
            }
        });

        function clickShortCutEvent(e){


            if(e.keyCode==98){
                /*key code = B*/
                e.preventDefault();
                window.location.href = "<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>";


            }
        }



    });
</script>