<?php
$act = $this->request->params['action'];
?>
<div class="progress-bar channel_tap">

    <a id="v_app_channel_list" <?php echo ($act == 'admin_ticket_event')?"class='active'":''; ?> href="<?php echo Router::url('/admin/admin/ticket_event/'.base64_encode($eventID)); ?>"><i class="fa fa-list"></i> Ticket List</a>

    <a id="v_add_channel"  <?php echo ($act == 'admin_add_ticket_event')?"class='active'":''; ?> href="<?php echo Router::url('/admin/admin/add_ticket_event/'.base64_encode($eventID)); ?>" ><i class="fa fa-television"></i> Add Ticket</a>
    
</div>