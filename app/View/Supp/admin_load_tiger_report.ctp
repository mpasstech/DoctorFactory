
<div class="table-responsive">
    <?php if(!empty($app_list)){ ?>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>App Start</th>
            <th>Total Days</th>
            <th>Total Users</th>
            <th>Average</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($app_list as $key => $list){

            ?>
            <tr>
                <td class="td_valign"><?php echo $key+1; ?></td>
                <td class="td_valign"><a  class="app_user_btn" href="javascript:void(0)" app-id="<?php echo $list['thinapp_id']; ?>"><?php echo $list['app_name']; ?></a> </td>
                <td class="td_valign"><?php echo $list['app_start_date']; ?></td>
                <td class="td_valign"><?php echo $list['app_total_days']; ?></td>
                <td class="td_valign"><?php echo $list['total_users']; ?></td>
                <td class="td_valign"><?php echo $list['app_per_day_user']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>



<?php }else { ?>
<div  style="text-align: center; font-size: 15px;"> No app found</div>
<?php } ?>


</div>