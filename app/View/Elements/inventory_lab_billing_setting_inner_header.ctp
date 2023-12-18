<div class="row" style="margin-top: 11px;">
    <!--  SLIDER IMAGE -->
    <div class="form-group">
        <div class="row">
            <div class=" billing_element col-lg-12 col-md-12">


                <div class="hospital_service col-lg-12 col-md-12">
                    <a href="<?php echo Router::url('/app_admin/hospital_service_lab',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/product_and_service.png')?>">
                            </div>
                            Product & Service
                        </div>
                    </a>
                </div>

                <div class="inventory col-lg-12 col-md-12">
                    <a href="<?php echo Router::url('/app_admin/get_inventory_report_lab',true); ?>" >
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/inventory.png')?>">
                            </div>
                            Inventory
                        </div>
                    </a>
                </div>
                

            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<style>


    .active-tab-header{
        padding: 2px 0px !important;
    }
    .inventory, .hospital_service{
        padding: 0px;
    }
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
        font-size: 11px !important;
        min-height: unset !important;
    }

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
        font-size: 11px !important;
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
        }else if(action == 'hospital_service_lab' || action =="add_hospital_service_lab" || action=="edit_hospital_service_lab"){
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
        }else if(action == 'get_inventory_report_lab' || action == 'list_hospital_service_inventory_lab' || action == 'add_product_quantity_lab' || action == 'edit_product_quantity_lab'){
            $('.inventory').addClass('active-tab-header');
        }

    })
</script>
