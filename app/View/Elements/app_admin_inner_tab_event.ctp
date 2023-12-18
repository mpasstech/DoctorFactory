<?php
$act = $this->request->params['action'];
?>
<div class="progress-bar channel_tap">

    <a id="v_app_channel_list" <?php echo ($act == 'event')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/event'); ?>"><i class="fa fa-list"></i> Event List</a>

    <a id="v_add_channel"  <?php echo ($act == 'add_event')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/add_event'); ?>" ><i class="fa fa-television"></i> Add Event</a>

    <a id="v_add_channel"  <?php echo ($act == 'permit_event')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/permit_event'); ?>" ><i class="fa fa-globe"></i> Permit Event</a>
    
</div>
<style>
    .channel_tap a{ width:33% !important; }
</style>