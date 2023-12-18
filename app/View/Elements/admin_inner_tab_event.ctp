<?php
$act = $this->request->params['action'];
?>
<div class="progress-bar channel_tap">

    <a id="v_app_channel_list" <?php echo ($act == 'admin_event')?"class='active'":''; ?> href="<?php echo Router::url('/admin/admin/event'); ?>"><i class="fa fa-list"></i> Event List</a>

    <a id="v_add_channel"  <?php echo ($act == 'admin_add_event')?"class='active'":''; ?> href="<?php echo Router::url('/admin/admin/add_event'); ?>" ><i class="fa fa-television"></i> Add Event</a>

    <a id="v_add_channel"  <?php echo ($act == 'admin_permit_event')?"class='active'":''; ?> href="<?php echo Router::url('/admin/admin/permit_event'); ?>" ><i class="fa fa-globe"></i> Permit Event</a>
    
</div>
<style>
    .channel_tap a{ width:33% !important; }
</style>