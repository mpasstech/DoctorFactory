<ul class="app_label_box" style="overflow-x:auto;white-space:nowrap;" >
<?php   foreach ($totalArray as $key => $total) { ?>
    <li class="<?php echo ($key=='Total Booked')?'active':''; ?>" data-label="<?php echo $key; ?>" ><label><?php echo $key; ?> : <b class="count_b"><?php echo $total; ?></b></label></li>

<?php  } ?>
</ul>