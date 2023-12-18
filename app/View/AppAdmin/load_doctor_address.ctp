<?php if(sizeof($list) == 1)
{ ?>
<!--style>
    .append_address{display: none;}
</style-->
<?php } ?>
<div class="col-md-4">
    <label>Select Address</label>
    <?php echo $this->Form->input('doctor',array('type'=>'select','label'=>false,'options'=>$list,'class'=>'form-control address_drp')); ?>
</div>







