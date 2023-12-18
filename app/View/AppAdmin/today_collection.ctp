<?php if(!empty($payment_list)){ ?>
    <li class="today_collection_lbl">
        <strong>
            <?php if(date('Y-m-d')==$searchDate){ ?>
                <span style="color: green;">Today's</span> Collection
            <?php }else{ ?>
                <span style="color: green;"><?php echo date('d M',strtotime($searchDate))?></span> Collection
            <?php } ?>
        </strong>
    </li>


<?php foreach($payment_list AS $val){ ?>
    <li class="total_amount_lbl payment_type_<?php echo $val['id']; ?>" data-amount="<?php echo $val['total']; ?>"><?php echo $val['payment_type']; ?>: <strong><i class="fa fa-inr"></i> <?php echo $val['total']; ?></strong></li>
<?php }}else{ ?>
    <li class="today_collection_lbl"><strong>No Collection Yet</strong></li>
<?php } ?>
