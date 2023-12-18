<?php if(!empty($data['data']['reminder_list'])){ foreach($data['data']['reminder_list'] as $key => $list){ ?>
    <tr>
        <td><?php echo ($offset * $limit ) + ($key+1); ?></td>
        <td><?php echo $list['patient_name']; ?></td>
        <td><?php echo $list['patient_mobile']; ?></td>
        <td><?php echo $list['reminder_date']; ?></td>
        <td><?php echo $list['reminder_status']; ?></td>
        <td>
            <button type="button" class="send_alert btn btn-info btn-xs send_alert_btn" data-id="<?php echo base64_encode($list['id']);?>"  title = "Send alert to this patient" href="javascript:void(0);"><i class="fa fa-bell fa-1x fa-fw"></i> Send Alert</button>
        </td>
    </tr>
<?php }}else{ ?>
    <tr><td style="text-align: center;" colspan="6">No follow up list found</td></tr>
<?php } ?>
<script type="text/javascript">
    $(function () {
        var total = "<?php echo count($data['data']['reminder_list']); ?>";
        var limit = "<?php echo $limit; ?>";
        if(total == limit){
            $(".follow_load_more").show().attr('data-offset',parseInt($(".follow_load_more").attr('data-offset'))+1);
        }else{
            $(".follow_load_more").hide();
        }

    });
</script>
