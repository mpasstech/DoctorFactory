<div class="row" style="margin-top: 11px;">
    <!--  SLIDER IMAGE -->
    <div class="form-group">
        <div class="row">
            <div class=" billing_element col-md-12  col-md-offset-3">



                <div class="hospital_service_category col-lg-1 col-md-1">
                    <a href="<?php echo Router::url('/app_admin/hospital_service_category',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                            </div>
                            Service Category
                        </div>
                    </a>
                </div>

                <div class="hospital_tax_rate col-lg-1 col-md-1">
                    <a href="<?php echo Router::url('/app_admin/hospital_tax_rate',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/hospital_tax.png')?>">
                            </div>
                            Tax Rate
                        </div>
                    </a>
                </div>

                <div class="hospital_payment_type col-lg-1 col-md-1">
                    <a href="<?php echo Router::url('/app_admin/hospital_payment_type',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/hospital_payment.png'); ?>">
                            </div>
                            Payment Type
                        </div>
                    </a>
                </div>

                <div class="update_billing col-lg-1 col-md-1">
                    <a href="<?php echo Router::url('/app_admin/update_billing_title',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/title.png'); ?>">
                            </div>
                            Receipt
                        </div>
                    </a>
                </div>


                

            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<style>

    .Inner-banner{
        background: none;
    }
    .dash_img > img {

        height: unset;
        width: 100%;

    }
    .billing_element .col-lg-1{
        width: 10%;
    }
    .content_div{
        text-align: center !important;
        font-size: 9px !important;
        min-height: unset !important;
    }


</style>
<script>
    $(function () {
        $(".billing_element div").removeClass('active-tab-header');
        var action = "<?php echo $this->params['action']; ?>";

        if(action == 'add_hospital_receipt_search'){
            $('.add_receipt').addClass('active-tab-header');
        }else if(action == 'hospital_patient_list'){
            $('.patient_list').addClass('active-tab-header');
        }else if(action == 'hospital_service' || action =="add_hospital_service" || action=="edit_hospital_service"){
            $('.hospital_service').addClass('active-tab-header');
        }else if(action == 'hospital_service_category' || action == "add_hospital_service_category" || action =="edit_hospital_service_category"){
            $('.hospital_service_category').addClass('active-tab-header');
        }   else if(action == 'hospital_tax_rate' || action =='edit_hospital_tax_rate' || action =='add_hospital_tax_rate'){
            $('.hospital_tax_rate').addClass('active-tab-header');
        }else if(action == 'hospital_service_category'){
            $('.hospital_service_category').addClass('active-tab-header');
        }else if(action == 'hospital_payment_type' || action == 'edit_hospital_payment_type' || action=='add_hospital_payment_type'){
            $('.hospital_payment_type').addClass('active-tab-header');
        }else if(action == 'get_hospital_receipt_reports'){
            $('.billing_rep').addClass('active-tab-header');
        }else if(action == 'update_billing_title'){
            $('.update_billing').addClass('active-tab-header');
        }else if(action == 'get_inventory_report'){
            $('.inventory').addClass('active-tab-header');
        }

    })
</script>
