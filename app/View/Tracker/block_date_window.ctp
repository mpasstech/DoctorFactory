<style>

    .window_datepicker .popover-content{
        padding: 0px !important;
    }
    #blocked_datepicker{
        margin-top: 22px;
    }

</style>
<div class="input-group date form-group" id="blocked_datepicker">
    <?php $date_string = $total_blocked = ''; if(!empty($blocked_dates)){ $total_blocked =count($blocked_dates); $date_string =implode(",",array_column($blocked_dates,'blocked_date')); }  ?>
    <input style="height: 30px;" onkeydown="return false" value="<?php echo $date_string; ?>" type="text" class="form-control" id="blocked_dates" name="Dates" placeholder="Select days" required />
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"><?php echo $total_blocked; ?></span></span>
</div>

<script>
    $(function () {



        $('#blocked_datepicker').datepicker({
            startDate: new Date(),
            multidate: true,
            format: "dd/mm/yyyy",
            daysOfWeekHighlighted: "5,6",
            orientation: "bottom",
            language: 'en'
        }).on('changeDate', function(e) {
            $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
        });



        $('#blocked_datepicker').datepicker('show');



    })
</script>