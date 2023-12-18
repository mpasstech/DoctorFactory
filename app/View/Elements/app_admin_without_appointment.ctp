<?php

$login = $this->Session->read('Auth.User');
$action = $this->params['action'];
?>

<div class="progress-bar channel_tap">
    <a style="width: 33.333%;" <?php echo ($action == 'all_appointment_without_token')?'class="active"':''; ?> href="<?php echo Router::url('/app_admin/all_appointment_without_token'); ?>"><i class="fa fa-list"> </i> All Patient </a>
    <a style="width: 33.333%;" <?php echo ($action == 'get_appointment_without_token_list')?'class="active"':''; ?> href="<?php echo Router::url('/app_admin/get_appointment_without_token_list'); ?>"><i class="fa fa-list"> </i> All Appointment </a>
    <a style="width: 33.333%;" <?php echo ($action == 'add_appointment_without_token' || $action == 'book_appointment_without_token')?'class="active"':''; ?> href="<?php echo Router::url('/app_admin/add_appointment_without_token'); ?>" ><i class="fa fa-list"> </i> Add Appointment</a>
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