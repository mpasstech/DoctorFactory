<?php
$login = $this->Session->read('Auth.User');
$template = $this->AppAdmin->get_app_template($login['User']['thinapp_id']);
?>


<?php if($template == "THEME_1"){ ?>
    <div class="slide_fix billing_element">
        <div class="book_app add_receipt col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/add_receipt.png')?>">
                    </div>
                    Add Receipt
                </div>
            </a>
        </div>


        <?php if($login['USER_ROLE'] =='RECEPTIONIST' || $login['USER_ROLE'] =='DOCTOR' || $login['USER_ROLE'] =='STAFF' || $login['USER_ROLE'] =='ADMIN'){ ?>

            <div class="book_app add_receipt col-lg-12 col-md-12">
                <a href="<?php echo Router::url('/app_admin/add_package_receipt',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/add_receipt.png')?>">
                        </div>
                        Add Package Receipt
                    </div>
                </a>
            </div>

        <?php } ?>

        <div class="my_app add_receipt col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/app_admin/hospital_patient_list',true); ?>">
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                    </div>
                    Patient List
                </div>
            </a>
        </div>

        <div class="tracker_div add_receipt col-lg-12 col-md-12">

            <a href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports'); ?>" >
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/Report.png')?>">
                    </div>
                    Billing Reports
                </div>
            </a>


        </div>

        <div class="tracker_div add_receipt col-lg-12 col-md-12">

            <a href="<?php echo Router::url('/app_admin/get_commision_report'); ?>" >
                <div class="content_div">
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/Report.png')?>">
                    </div>
                    Commission Reports
                </div>
            </a>


        </div>

        <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUICK_APPOINTMENT") ){ ?>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="<?php echo Router::url('/app_admin/booking_convenience_report'); ?>" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/Report.png')?>">
                        </div>
                        Booking Convenience Report
                    </div>
                </a>

            </div>
        <?php } ?>




        <?php if($login['USER_ROLE'] =='LAB' || $login['USER_ROLE'] =='PHARMACY'){ ?>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_lab_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                        </div>
                        Add Lab Product/Service
                    </div>
                </a>


            </div>
        <?php }else{ ?>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_category" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                        </div>
                        Add Product/Service Category
                    </div>
                </a>


            </div>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_pharma_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                        </div>
                        Add Pharma Product/Service
                    </div>
                </a>


            </div>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_lab_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                        </div>
                        Add Lab Product/Service
                    </div>
                </a>


            </div>
            <div class="tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_service_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/service_inventory.png')?>">
                        </div>
                        Add Service & Package
                    </div>
                </a>


            </div>
        <?php } ?>




    </div>

    <div class="modal fade" id="addCategoryModal" role="dialog">

        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Product/Service Category</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive add_category_form_holder">

                    </div>
                </div>
                <div class="modal-footer">

                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="addPharmaModal" role="dialog">

        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Pharma Product/Service</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive add_pharma_form_holder">

                    </div>
                </div>
                <div class="modal-footer">

                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="addLabModal" role="dialog">

        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Lab Product/Service</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive add_lab_form_holder">

                    </div>
                </div>
                <div class="modal-footer">

                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="addServiceModal" role="dialog">

        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Service & Package</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive add_service_form_holder">

                    </div>
                </div>
                <div class="modal-footer">

                </div>

            </div>
        </div>

    </div>

    <style>

        .search_row{
            width: 100% !important;
        }
        .short_cut{
            font-size: 10px;
            float: left;
            position: absolute;
            left: -12px;
            transform: rotate(-90deg);
            top: 26px;
            font-weight: 600;
        }
        .Myaccount-left {
            width: 100%;
        }
        .add_receipt {
            margin-bottom: 1px;
            margin-top: 1px;
            text-align: center;
        }
        .content_div {
            border-radius: 1em;
        }
        .slide_fix {
            width: 10%;
            float: left;
            position: fixed;
            left: 0;
            z-index: 10;
            padding: 10px 0px;
            background: #fff;
            border-radius: 0px 10px 10px 0px;
            border: 1px solid rgba(184, 242, 245, 0.97);
            top:113px;
        }
        .main_block {
            float: right;
        }


        element {

        }
        .billing_element .content_div, .billing_dashboard .content_div {

            min-height: unset;

        }
        .dash_img > img {
            height: 60px;
            width: 60px;
        }
        .content_div {
            border-radius: 1em;
            line-height: 11px;
            font-size: 9px !important;
            padding: 2px 2px !important;
        }
    </style>


    <style>
        #search_cus_modal_without_token .modal-dialog{
            width:700px !important;
        }
        .app-detail{
            text-align: center;
        }
        .app-detail img{
            width: 50px;
        }
        .dashboard_icon_li li {

            text-align: center;
            width: 23%;

        }
        .container {
            width: 95%;
        }
        .Btn-typ3{width:100%;}
        .modal-header {
            background-color: #03a9f5;
            color: #FFFFFF;
        }
        .close {
            margin-top: 35px;
        }
        .modal-title{text-align: center;}
        .slide_fix.billing_element {
            height: 500px;
            overflow: auto;
        }
        .modal-footer {

            padding: 19px 20px 20px;
            margin-top: 15px;
            text-align: right;
            border-top: 1px solid #e5e5e5;

        }

    </style>

<?php }else if($template=="THEME_2"){ ?>
    <div class="slide_fix billing_element">


        <div class="book_app side_bar_box_btn col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/app_admin/dashboard',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/dashboard.jpg')?>">
                    </div>
                    Dashboard
                </div>
            </a>
        </div>


        <div class="side_bar_box_btn book_app add_receipt col-lg-12 col-md-12">
            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                <div class="content_div" >
                    <div class="dash_img">
                        <img src="<?php echo Router::url('/thinapp_images/add_receipt.png')?>">
                    </div>
                    Add Receipt
                </div>
            </a>
        </div>


        <?php if($login['USER_ROLE'] =='RECEPTIONIST' || $login['USER_ROLE'] =='DOCTOR' || $login['USER_ROLE'] =='STAFF' || $login['USER_ROLE'] =='ADMIN'){ ?>

            <div class="side_bar_box_btn book_app add_receipt col-lg-12 col-md-12">
                <a href="<?php echo Router::url('/app_admin/add_package_receipt',true); ?>">
                    <div class="content_div" >
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/add_receipt.png')?>">
                        </div>
                        Add Package Receipt
                    </div>
                </a>
            </div>

        <?php } ?>









        <?php if($login['USER_ROLE'] =='LAB'){ ?>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_category" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                        </div>
                        Add Product/Service Category
                    </div>
                </a>


            </div>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_lab_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                        </div>
                        Add Lab Product/Service
                    </div>
                </a>


            </div>
            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12" style="width: 130px;">


                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports'); ?>">
                    <i class="fa fa-file-o" aria-hidden="true"></i> Billing Reports
                </a>


                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/hospital_patient_list',true); ?>">
                    <i class="fa fa-users" aria-hidden="true"></i> Patient List
                </a>


                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/logout',true); ?>" >
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>


            </div>

        <?php }
        else if($login['USER_ROLE'] =='PHARMACY'){ ?>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_category" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                        </div>
                        Add Product/Service Category
                    </div>
                </a>


            </div>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_pharma_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                        </div>
                        Add Pharma Product/Service
                    </div>
                </a>


            </div>
            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12" style="width: 130px;">


               <!-- <a class="btn btn-xs extra_link" href="<?php /*echo Router::url('/app_admin/get_hospital_receipt_reports'); */?>">
                    <i class="fa fa-file-o" aria-hidden="true"></i> Billing Reports
                </a>-->

                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/hospital_patient_list',true); ?>">
                    <i class="fa fa-users" aria-hidden="true"></i> Patient List
                </a>

                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/logout',true); ?>" >
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>


            </div>



        <?php }
        else{ ?>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_category" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                        </div>
                        Add Product/Service Category
                    </div>
                </a>


            </div>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_pharma_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                        </div>
                        Add Pharma Product/Service
                    </div>
                </a>


            </div>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_lab_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                        </div>
                        Add Lab Product/Service
                    </div>
                </a>


            </div>
            <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                <a href="javascript:void(0)" class="add_service_product" >
                    <div class="content_div">
                        <div class="dash_img">
                            <img src="<?php echo Router::url('/thinapp_images/service_inventory.png')?>">
                        </div>
                        Add Service/Package
                    </div>
                </a>


            </div>


            <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUICK_APPOINTMENT") ){ ?>
                <div class="side_bar_box_btn tracker_div add_receipt col-lg-12 col-md-12">

                    <a href="<?php echo Router::url('/app_admin/booking_convenience_report',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/Report.png')?>">
                            </div>
                            Booking Convenience Report
                        </div>
                    </a>


                </div>
            <?php } ?>


            <div class="receipt_div side_bar_box_btn col-lg-12 col-md-12" style="width: 130px;">






                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports'); ?>">
                    <i class="fa fa-file-o" aria-hidden="true"></i> Billing Reports
                </a>



                <?php if($login['USER_ROLE'] !='LAB' && $login['USER_ROLE'] !='PHARMACY'){ ?>

                    <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/get_commision_report'); ?>">
                        <i class="fa fa-inr" aria-hidden="true"></i> Commission Reports
                    </a>
                <?php } ?>



                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/hospital_patient_list',true); ?>">
                    <i class="fa fa-users" aria-hidden="true"></i> Patient List
                </a>



                <a class="btn btn-xs extra_link" href="<?php echo Router::url('/app_admin/logout',true); ?>" >
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>


            </div>


        <?php } ?>




    </div>
    <style>


        .header .top-info{
            display: none;
        }

        .Myaccount-left {
            width: 100%;
        }
        .add_receipt {
            margin-bottom: 1px;
            margin-top: 1px;
            text-align: center;
        }
        .content_div {
            border-radius: 1em;
        }

        .main_block {
            float: right;
        }


        element {

        }
        .billing_element .content_div, .billing_dashboard .content_div {

            min-height: unset;

        }

        #search_cus_modal_without_token .modal-dialog{
            width:700px !important;
        }
        .app-detail{
            text-align: center;
        }
        .app-detail img{
            width: 50px;
        }
        .dashboard_icon_li li {

            text-align: center;
            width: 23%;

        }
        .container {
            width: 95%;
        }
        .Btn-typ3{width:100%;}
        .modal-header {
            background-color: #03a9f5;
            color: #FFFFFF;
        }
        .close {
            margin-top: 35px;
        }
        .modal-title{text-align: center;}

        .modal-footer {

            padding: 19px 20px 20px;
            margin-top: 15px;
            text-align: right;
            border-top: 1px solid #e5e5e5;

        }

    </style>

<?php } ?>



<div class="modal fade" id="addCategoryModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Product/Service Category</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive add_category_form_holder">

                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="addPharmaModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Pharma Product/Service</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive add_pharma_form_holder">

                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="addLabModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Lab Product/Service</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive add_lab_form_holder">

                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="addServiceModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Service & Package</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive add_service_form_holder">

                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>


<script>
    $(document).on("click",".add_category",function(){
        $.ajax({
            type: 'POST',
            url: baseurl + "app_admin/get_add_category_modal",
            success: function (data) {
                $(".add_category_form_holder").html(data);
                $("#addCategoryModal").modal("show");
            },
            error: function (data) {
                alert("Sorry something went wrong on server.");
            }
        });
    });
    $(document).on("click",".add_pharma_product",function(){
        $.ajax({
            type: 'POST',
            url: baseurl + "app_admin/get_add_hospital_service_modal",
            success: function (data) {
                $(".add_pharma_form_holder").html(data);
                $("#addPharmaModal").modal("show");
            },
            error: function (data) {
                alert("Sorry something went wrong on server.");
            }
        });
    });
    $(document).on("click",".add_lab_product",function(){
        $.ajax({
            type: 'POST',
            url: baseurl + "app_admin/get_add_hospital_service_lab_modal",
            success: function (data) {
                $(".add_lab_form_holder").html(data);
                $("#addLabModal").modal("show");
            },
            error: function (data) {
                alert("Sorry something went wrong on server.");
            }
        });
    });
    $(document).on("click",".add_service_product",function(){
        $.ajax({
            type: 'POST',
            url: baseurl + "app_admin/get_add_hospital_service_service_modal",
            success: function (data) {
                $(".add_service_form_holder").html(data);
                $("#addServiceModal").modal("show");
            },
            error: function (data) {
                alert("Sorry something went wrong on server.");
            }
        });
    });
</script>
