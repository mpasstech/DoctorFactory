<?php

$login = $this->Session->read('Auth.User');

?>
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
    <div class="Myaccount-left" style="margin: 15px auto;">

        <div class="Myaccount-links" style="width: 100%; display: block;padding: 0px 12px;">
            <!--<p><a href="my-account.html" title="My Account"><i class="fa-dashboard fa"></i> &nbsp; My Account</a></p>-->

            <?php if($this->SupportAdmin->isMainSupportAdmin()){ ?>
            <p><a href="<?php echo Router::url('/admin/supp/dashboard_report');?>" title="Edit profile"><i class="fa-dashboard fa"></i> &nbsp; Dashboard</a></p>
                <p><a href="<?php echo Router::url('/admin/supp/support_list');?>" title="Edit Subscription"><i class="fa-bell-o fa"></i> &nbsp; Leads Manager</a></p>

            <?php } ?>
            <p><a href="<?php echo Router::url('/admin/supp/thinapp_list');?>" title="Thinapp Manager"><i class="fa-android fa"></i> &nbsp; Thin App Manager</a></p>

            <p><a href="<?php echo Router::url('/admin/supp/booking_convenience_report');?>" title="Booking Convenience Report"><i class="fa-bar-chart fa"></i> &nbsp; Booking Convenience Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/list_view_appointment');?>" title="Booking Convenience Report"><i class="fa-bar-chart fa"></i> &nbsp; Appointment List</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/franchise_report');?>" title="Franchise Report"><i class="fa-bar-chart fa"></i> &nbsp; Franchise Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/telemedicine_report');?>" title="Telemedicine Report"><i class="fa-bar-chart fa"></i> &nbsp; Telemedicine Report</a></p>




            <?php if($this->SupportAdmin->isMainSupportAdmin()){ ?>
            <p><a href="<?php echo Router::url('/admin/supp/support_admin_list')?>" title="reports"><i class="fa-user fa"></i> &nbsp; Support Admin User</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/app_stats_report_list')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Stats Reports</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/print_offline_barcode')?>" title="barcode"><i class="fa-barcode fa"></i> &nbsp; Offline Barcode</a></p>

            <p><a href="<?php echo Router::url('/admin/supp/inquiry_list');?>" title="Edit Inquiry"><i class="fa-dot-circle-o fa"></i> &nbsp; Enquiry Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/contact_list');?>" title="Edit Contact"><i class="fa-users fa"></i> &nbsp; Contact Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/static_query_list');?>" ><i class="fa-users fa"></i> &nbsp; Static Query Manager</a></p>
            <!--<p><a href="<?php /*echo Router::url('/admin/supp/function');*/?>" ><i class="fa-users fa"></i> &nbsp; App Functionality</a></p>-->
            <p><a href="<?php echo Router::url('/admin/supp/skype_list');?>" title="Edit Skype"><i class="fa-skype fa"></i> &nbsp; Skype Manager</a></p>

            <p><a href="<?php echo Router::url('/admin/supp/redeem_request_list');?>" title="Redeem Manager"><i class="fa-money fa"></i> &nbsp; Redeem Coin Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/user_sms_list');?>" title="SMS Manager"><i class="fa-envelope fa"></i> &nbsp; SMS Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/gift_list');?>" title="Gift Manager"><i class="fa-envelope fa"></i> &nbsp; Gift Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/quest');?>" title="Quest Manager"><i class="fa-calendar fa"></i> &nbsp; Quest Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/buy');?>" title="Buy Manager"><i class="fa-calendar fa"></i> &nbsp; Buy Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/sell');?>" title="Sell Manager"><i class="fa-calendar fa"></i> &nbsp; Sell Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/user_list');?>" title="Users"><i class="fa-users fa"></i> &nbsp;Doctor Factory Users</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/list_cms_doc_sub_category');?>" title="Doc CMS Subcategory Manager"><i class="fa-calendar fa"></i> &nbsp; Health & Emergency Category Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/cms_doc_dashboard');?>" title="Doc CMS Manager"><i class="fa-calendar fa"></i> &nbsp; Health & Emergency Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/doctor_reports')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Doctor Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/patient_reports')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Patient Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/tiger_report')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Tiger Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/tiger_potential_report')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Tiger Potential Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/patient_day_wise_reports')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Visitor Report</a></p>
                <p><a href="<?php echo Router::url('/admin/supp/user_statics')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; User Statics Report</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/child_milestone_list')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Child Milestone</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/prescription_tag_list')?>" title="reports"><i class="fa-bar-chart fa"></i> &nbsp; Prescription Tag</a></p>

            <p><a href="<?php echo Router::url('/admin/supp/list_contest');?>" title="Contest Manager"><i class="fa-calendar fa"></i> &nbsp; Contest Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/list_referral_users');?>" title="Refferal Manager"><i class="fa-calendar fa"></i> &nbsp; Refferal Manager</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/app_install_user')?>" title="reports"><i class="fa-android fa"></i> &nbsp; App Install Users</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/app_active_admin')?>" title="reports"><i class="fa-android fa"></i> &nbsp; App Active Admin</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/subscription_list')?>" title="reports"><i class="fa-list fa"></i> &nbsp; App Subscription List</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/send_sms_window')?>" title="SMS"><i class="fa-envelope fa"></i> &nbsp; Send Sms</a></p>
            <p><a href="<?php echo Router::url('/admin/supp/read_excel')?>" title="SMS"><i class="fa-envelope fa"></i> &nbsp; Synchronize Cashfee Report</a></p>
            
            <p><a href="<?php echo Router::url('/admin/supp/refund_convenience_fee')?>" title="SMS"><i class="fa-inr fa"></i> &nbsp; Cashfee Refund</a></p>
            
            
            <?php } ?>


        </div>



    </div>

</div>





