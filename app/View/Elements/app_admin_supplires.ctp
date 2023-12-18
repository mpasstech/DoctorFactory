<?php
$act = $this->request->params['action'];
?>
<div class="top_heading"><lable>Supplier & Order</lable></div>
<div class="progress-bar channel_tap">

    <a id="v_add_channel"  <?php echo ($act == 'list_supplier_order')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/list_supplier_order'); ?>" ><i class="fa fa-list"></i> List Order</a>

    <a id="v_add_channel"  <?php echo ($act == 'add_supplier_order')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/add_supplier_order'); ?>" ><i class="fa fa-edit"></i> Add Order</a>

    <a id="v_app_channel_list" <?php echo ($act == 'list_supplier')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/list_supplier'); ?>"><i class="fa fa-list"></i> List Supplier</a>

    <a id="v_add_channel"  <?php echo ($act == 'add_supplier')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/add_supplier'); ?>" ><i class="fa fa-edit"></i> Add Supplier</a>

    <a id="v_add_channel"  <?php echo ($act == 'supplier_order_setting')?"class='active'":''; ?> href="<?php echo Router::url('/app_admin/supplier_order_setting'); ?>" ><i class="fa fa-edit"></i> Order Setting</a>






</div>

<style>
    .top_heading {

        text-align: center;
        padding-top: 16px;
        font-size: large;
        font-weight: ;

    }
    .modal-title {

        color: #FFF;
        font-weight: bold;

    }
    .modal-header {

        min-height: 16.43px;
        padding: 15px;
        border-bottom: 1px solid #03a9f5;
        background: #03a9f5;

    }
    .channel_tap a{ width: 20% !important; }
</style>