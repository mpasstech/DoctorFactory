<?php
header("Access-Control-Allow-Origin: *");
include_once "ConnectionUtil.php";
include_once "WebservicesFunction.php";
include_once "WebServicesFunction_2_3.php";
include_once "QueueManagementWebService.php";
$web_obj = new WebservicesFunction();
$web_obj_2_3 = new WebServicesFunction_2_3();
$queueManage = new QueueManagementWebService();


$sss_post_res = file_get_contents("php://input");
$xnc_data = json_decode($sss_post_res, true);
$user_id = @$xnc_data['user_id'];
$thin_app_id = @$xnc_data['thin_app_id'];
$mobile = @$xnc_data['mobile'];
$file_name = date('Y-m-d') . "_" . $user_id;

if(empty($action) || $action=='services'){
    $style = "display: block;width: 40%;text-align: center;margin: 0 auto;padding: 4%;border-bottom: 1px solid #f8f8f8;";
    echo "<div style='$style'><img  style='display:block;margin:0 auto;' src='http://mengage.co.in/doctor/images/logo.png' /><h1>Welcome To MEngage</h1><div>";die;
}


if (empty($mobile) && empty($thin_app_id)) {
    $user_id = @$xnc_data['userID'];
    if (!empty($user_id)) {
        $user_data = Custom::get_user_by_id($user_id);
        if (!empty($user_data)) {
            $mobile = @$user_data['mobile'];
            $thin_app_id = @$user_data['thinapp_id'];
        }
    }

}
//$file_name = $action."__".date('Ymdhis');
//WebservicesFunction::createJson($file_name, '', 'CREATE','request');
Custom::add_request_hit($thin_app_id, $action);
/**** action coming from index.php *****/
if ($action == 'get_app_enabled_functionality') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "DASHBOARD", "DASHBOARD", $action);
    $web_obj::get_app_enabled_functionality();
} /* this is from starting*/
else if ($action == 'signup_revised') {
    $web_obj::signup_revised();
} else if ($action == 'verify_account') {
    $web_obj::verify_account();
} else if ($action == 'resend_code') {
    $web_obj::resend_code();
} else if ($action == 'check_app_version') {

    $web_obj::check_app_version();
} else if ($action == 'add_subscriber_revised') {
    $web_obj::add_subscriber_revised();
} else if ($action == 'add_message_revised') {
    $web_obj::add_message_revised();
} else if ($action == 'update_subscription_status_revised') {

    $web_obj::update_subscription_status_revised();
} else if ($action == 'delete_subscriber') {

    $web_obj::delete_subscriber();
} else if ($action == 'channel_delete') {

    $web_obj::channel_delete();
} else if ($action == 'add_channel') {

    $web_obj::add_channel();
} else if ($action == 'get_messages_static_data') {

    $web_obj::get_messages_static_data();
} else if ($action == 'broadcast_message') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "DOCTORS BLOG", "BROADCAST_MESSAGE", $action);
    $web_obj::broadcast_message();
} else if ($action == 'get_channel_dropdown_list') {

    $web_obj::get_channel_dropdown_list();
} else if ($action == 'get_channel_for_losefound_list') {

    $web_obj::get_channel_for_losefound_list();
} else if ($action == 'update_subscription_favourite') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "SUBSCRIBER", "FAVOURITE SUBSCRIBER", $action);
    $web_obj::update_subscription_favourite();
} else if ($action == 'send_collaborator_request') {

    $web_obj::send_collaborator_request();
} else if ($action == 'inactive_patient_status') {

    $web_obj::inactive_patient_status();
} else if ($action == 'get_collaborator_list') {

    $web_obj::get_collaborator_list();
} else if ($action == 'user_as_collaborator_list') {

    $web_obj::user_as_collaborator_list();
} else if ($action == 'accept_collaborator_request') {

    $web_obj::accept_collaborator_request();
} else if ($action == 'change_collaborator_role') {

    $web_obj::change_collaborator_role();
} else if ($action == 'cancel_collaborator_request') {

    $web_obj::cancel_collaborator_request();
} else if ($action == 'add_ticket') {
    $web_obj::add_ticket();
} else if ($action == 'get_ticket_process_count') {
    $web_obj::get_ticket_process_count();
} else if ($action == 'get_ticket_list') {
    $web_obj::get_ticket_list();
} else if ($action == 'get_ticket_detail') {
    $web_obj::get_ticket_detail();
} else if ($action == 'update_ticket_status') {
    $web_obj::update_ticket_status();
} else if ($action == 'delete_ticket') {
    $web_obj::delete_ticket();
} else if ($action == 'get_user_profile') {
    $web_obj::get_user_profile();
} else if ($action == 'appointment_edit_staff_address') {

    $web_obj::appointment_edit_staff_address();
} else if ($action == 'appointment_edit_staff_service') {

    $web_obj::appointment_edit_staff_service();
} else if ($action == 'update_tracker_voice_status') {

    $web_obj::update_tracker_voice_status();
} else if ($action == 'get_tracker_voice_status') {

    $web_obj::get_tracker_voice_status();
} else if ($action == 'get_poll_type_list') {
    $web_obj::get_poll_type_list();
} else if ($action == 'check_appointment_validity') {

    $web_obj::check_appointment_validity();
} else if ($action == 'create_poll') {
    $web_obj::create_poll();
} else if ($action == 'share_poll') {
    $web_obj::share_poll();
} else if ($action == 'create_poll_question_screen') {
    $web_obj::create_poll_question_screen();
} else if ($action == 'update_appointment_payment_status') {

    $web_obj::update_appointment_payment_status();
} else if ($action == 'send_chat_notification') {

    $web_obj::send_chat_notification();
} else if ($action == 'send_chat_notification_new') {

    $web_obj::send_chat_notification_new();
} else if ($action == 'cms_pages') {
    //$web_obj::addUserStaticToJson($file_name,$user_id,$thin_app_id,$mobile,"CMS_PAGES","VIEW_CMS",$action);
    $web_obj::cms_pages();
} else if ($action == 'get_subscriber_list') {
    //$web_obj::addUserStaticToJson($file_name,$user_id,$thin_app_id,$mobile,"CHAT","PATIENTS",$action);
    $web_obj::get_subscriber_list();
} else if ($action == 'get_my_channel_list') {
    //$web_obj::addUserStaticToJson($file_name,$user_id,$thin_app_id,$mobile,"CHANNEL","GET CHANNEL LIST",$action);
    $web_obj::get_my_channel_list();
} else if ($action == 'get_channel_factory_list') {
    $web_obj::get_channel_factory_list();
} else if ($action == 'get_poll_list') {
    $web_obj::get_poll_list();
} else if ($action == 'get_channel_messages_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "DOCTORS BLOG", "DOCTORS BLOG", $action);
    $web_obj::get_channel_messages_list();
} else if ($action == 'get_appointment_service_list') {

    $web_obj::get_appointment_service_list();
} else if ($action == 'get_list_all_payment_item') {
    $web_obj::get_list_all_payment_item();
} else if ($action == 'get_my_dues_list') {
    $web_obj::get_my_dues_list();
} else if ($action == 'get_my_history_list') {
    $web_obj::get_my_history_list();
} else if ($action == 'get_payment_item_detail') {
    $web_obj::get_payment_item_detail();
} else if ($action == 'get_my_order_hisotry') {
    $web_obj::get_my_order_hisotry();
} else if ($action == 'get_payment_item_order_detail') {
    $web_obj::get_payment_item_order_detail();
} else if ($action == 'get_all_order_list') {
    $web_obj::get_all_order_list();
} else if ($action == 'get_my_folder_list') {
	
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICAL RECORDS", "MY FOLDER", $action);
    $web_obj::get_my_folder_list();
} else if ($action == 'get_my_share_folder_list') {
	
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICAL RECORDS", "RECEIVED FOLDER", $action);
    $web_obj::get_my_share_folder_list();
} else if ($action == 'get_my_share_file_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICAL RECORDS", "RECEIVED FILES", $action);
    $web_obj::get_my_share_file_list();
} else if ($action == 'get_my_drive_data') {
	
    if ($xnc_data['list_for'] == "INSTRUCTION") {
        $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "INSTRUCTIONS", " OPEN", $action);
    } else {
        $label = "OPEN";
        if ($xnc_data['request_for'] == "MY_FOLDER") {
            $label = "MY FOLDER";
        } else if ($xnc_data['request_for'] == "SHARED_FOLDER") {
            $label = "RECEIVED FOLDER";
        } else if ($xnc_data['request_for'] == "SHARED_FILE") {
            $label = "RECEIVED FILES";
        }
        $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICAL RECORDS", $label, $action);
    }
    $web_obj::get_my_drive_data();

} else if ($action == 'get_file_list_from_folder') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICAL RECORDS", "OPEN FOLDER", $action);
    $web_obj::get_file_list_from_folder();
} else if ($action == 'view_appointment_service') {

    $web_obj::view_appointment_service();
} else if ($action == 'load_appointment_staff_profile') {

    $web_obj::load_appointment_staff_profile();
} else if ($action == 'appointment_get_staff_list') {

    $web_obj::appointment_get_staff_list();
} else if ($action == 'appointment_get_service_staff_list') {

    $web_obj::appointment_get_service_staff_list();
} else if ($action == 'load_appointment_staff_hours') {

    $web_obj::load_appointment_staff_hours();
} else if ($action == 'load_appointment_staff_breaks') {

    $web_obj::load_appointment_staff_breaks();
} else if ($action == 'load_appointment_staff_services') {

    $web_obj::load_appointment_staff_services();
} else if ($action == 'get_appointment_category_list') {

    $web_obj::get_appointment_category_list();
} else if ($action == 'get_appointment_service_list_by_category') {

    $web_obj::get_appointment_service_list_by_category();
} else if ($action == 'appointment_get_address_list') {

    $web_obj::appointment_get_address_list();
} else if ($action == 'get_list_all_payment_item') {
    $web_obj::get_list_all_payment_item();
} else if ($action == 'get_my_dues_list') {
    $web_obj::get_my_dues_list();
} else if ($action == 'get_my_history_list') {
    $web_obj::get_my_history_list();
} else if ($action == 'get_payment_item_detail') {
    $web_obj::get_payment_item_detail();
} else if ($action == 'get_my_order_hisotry') {
    $web_obj::get_my_order_hisotry();
} else if ($action == 'get_payment_item_order_detail') {
    $web_obj::get_payment_item_order_detail();
} else if ($action == 'get_all_order_list') {
    $web_obj::get_all_order_list();
} else if ($action == 'unassigned_service_category_list') {
    $web_obj::unassigned_service_category_list();
} else if ($action == 'get_unassigned_address_list_by_service') {
    $web_obj::get_unassigned_address_list_by_service();
} else if ($action == 'get_appointment_staff_profile') {

    $web_obj::get_appointment_staff_profile();
} else if ($action == 'get_appointment_staff_hours') {

    $web_obj::get_appointment_staff_hours();
} else if ($action == 'get_appointment_staff_services') {

    $web_obj::get_appointment_staff_services();
} else if ($action == 'appointment_get_service_unassigned_staff_list') {
    $web_obj::appointment_get_service_unassigned_staff_list();
} else if ($action == 'get_unassigned_service_list_for_category') {
    $web_obj::get_unassigned_service_list_for_category();
} else if ($action == 'get_appointment_customer_list') {

    $web_obj::get_appointment_customer_list();
} else if ($action == 'view_appointment_category') {

    $web_obj::view_appointment_category();
} else if ($action == 'get_all_service_list_for_category') {
    $web_obj::get_all_service_list_for_category();
} else if ($action == 'view_customer_detail') {

    $web_obj::view_customer_detail();
} else if ($action == 'get_appointment_staff_breaks') {

    $web_obj::get_appointment_staff_breaks();
} else if ($action == 'get_services_list_for_staff_schedule') {

    $web_obj::get_services_list_for_staff_schedule();
} else if ($action == 'appointment_get_staff_schedule') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "APPOINTMENT", "DOCTOR SCHEDULE", $action);
    $web_obj::appointment_get_staff_schedule();
} else if ($action == 'get_appointment_dashboard') {
    $web_obj::get_appointment_dashboard();
} else if ($action == 'get_all_services_list_for_customer') {
    $web_obj::get_all_services_list_for_customer();
} else if ($action == 'get_service_staff_list_for_customer') {
    $web_obj::get_service_staff_list_for_customer();
} else if ($action == 'get_customer_list_for_user') {
    $web_obj::get_customer_list_for_user();
} else if ($action == 'get_appointment_staff_address') {
    $web_obj::get_appointment_staff_address();
} else if ($action == 'get_address_list_of_service_for_customer') {
    $web_obj::get_address_list_of_service_for_customer();
} else if ($action == 'get_category_list_for_customer') {
    $web_obj::get_category_list_for_customer();
} else if ($action == 'get_staff_availability_for_customer') {
    $web_obj::get_staff_availability_for_customer();
} else if ($action == 'get_customer_appointment_list') {
    $web_obj::get_customer_appointment_list();
} else if ($action == 'get_customer_list_for_user_dashboard') {
    $web_obj::get_customer_list_for_user_dashboard();
} else if ($action == 'get_all_staff_list_for_admin') {
    $web_obj::get_all_staff_list_for_admin();
} else if ($action == 'refresh_subscriber_topic_login') {
    $web_obj::refresh_subscriber_topic_login();
} else if ($action == 'add_share') {
    $web_obj::add_share();
} else if ($action == 'rename_file') {

    $web_obj::rename_file();
} else if ($action == 'rename_folder') {

    $web_obj::rename_folder();
} else if ($action == 'delete_file') {
    $web_obj::delete_file();
} else if ($action == 'add_file') {
    $web_obj::add_file();
} else if ($action == 'add_folder') {
    $web_obj::add_folder();
} else if ($action == 'delete_folder') {
    $web_obj::delete_folder();
} else if ($action == 'delete_share') {

    $web_obj::delete_share();
} else if ($action == 'add_file_to_folder_permission') {

    $web_obj::add_file_to_folder_permission();
} else if ($action == 'add_user_log') {

    $web_obj::add_user_log();
} else if ($action == 'reschedule_appointment') {
    $web_obj::reschedule_appointment();
} else if ($action == 'close_appointment') {
    $web_obj::close_appointment();
} else if ($action == 'cancel_appointment') {
    $web_obj::cancel_appointment();
} else if ($action == 'add_new_appointment') {
    $web_obj::add_new_appointment();
} else if ($action == 'appointment_add_customer') {

    $web_obj::appointment_add_customer();
} else if ($action == 'appointment_edit_customer') {

    $web_obj::appointment_edit_customer();
} else if ($action == 'get_appointment_address_list') {


    $web_obj::get_appointment_address_list();
} else if ($action == 'add_appointment_address') {

    $web_obj::add_appointment_address();
} else if ($action == 'edit_appointment_address') {

    $web_obj::edit_appointment_address();
} else if ($action == 'get_folder_share_mobile') {

    $web_obj::get_folder_share_mobile();
} else if ($action == 'add_chat_user') {
    $web_obj::add_chat_user();
} else if ($action == 'get_chat_user_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "CHAT", "RECENT", $action);
    $web_obj::get_chat_user_list();
} else if ($action == 'add_user_profile') {

    $web_obj::add_user_profile();
} else if ($action == 'staff_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "CHAT", "MEMBERS", $action);
    $web_obj::staff_list();
} else if ($action == 'get_admin_subscriber_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "CHAT", "PATIENTS", $action);
    $web_obj::get_admin_subscriber_list();
} else if ($action == 'get_file_share_mobile') {

    $web_obj::get_file_share_mobile();
} else if ($action == 'one_to_one') {
    $web_obj::one_to_one();
} else if ($action == 'facebook_token') {
    $web_obj::facebook_token();
} else if ($action == 'get_app_locations') {
    $web_obj::get_app_locations();
} else if ($action == 'send_chat_link_sms') {
    $web_obj::send_chat_link_sms();
} else if ($action == 'get_default_channel_subscriber_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "CHAT", "PATIENTS", $action);
    $web_obj::get_default_channel_subscriber_list();
} else if ($action == 'get_service_offer_list') {
    $web_obj::get_service_offer_list();
} else if ($action == 'get_service_menu_list') {
    $web_obj::get_service_menu_list();
} else if ($action == 'get_service_menu_category_list') {
    $web_obj::get_service_menu_category_list();
} else if ($action == 'getThinAppList') {
    $web_obj::getThinAppList();
} else if ($action == 'addThiappFromLocal') {
    $web_obj::addThiappFromLocal();
} else if ($action == 'add_child') {

    $web_obj::add_child();
} else if ($action == 'edit_child') {

    $web_obj::edit_child();
} else if ($action == 'get_child_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "PATIENT/CHILD", $action);
    $web_obj::get_child_list();
} else if ($action == 'add_child_growth') {
    $web_obj::add_child_growth();
} else if ($action == 'get_child_graph') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "GROWTH CHART", $action);
    $web_obj::get_child_graph();
} else if ($action == 'get_child_graph_table') {
    $web_obj::get_child_graph_table();
} else if ($action == 'get_circle_doc_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "DOCTORS CIRCLE", "DOCTORS CIRCLE", $action);
    $web_obj::get_circle_doc_list();
} else if ($action == 'cms_doc_dashboard') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "HEALTH TIP", "HEALTH TIP", $action);
    $web_obj::cms_doc_dashboard();
} else if ($action == 'get_child_vaccination_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "VACCINE SCHEDULE", $action);
    $web_obj::get_child_vaccination_list();
} else if ($action == 'update_child_vaccination') {

    $web_obj::update_child_vaccination();
} else if ($action == 'get_child_vaccination_detail') {

    $web_obj::get_child_vaccination_detail();
} else if ($action == 'get_upcoming_vaccination_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "VACCINE SCHEDULE", $action);
    $web_obj::get_upcoming_vaccination_list();
} else if ($action == 'send_vaccination_alert') {
    $web_obj::send_vaccination_alert();
} else if ($action == 'update_uninstall_status') {
    $web_obj::update_uninstall_status();
} else if ($action == 'send_vaccination_alert_cron_job') {
    $web_obj::send_vaccination_alert_cron_job();
} else if ($action == 'get_department_categories') {

    $web_obj::get_department_categories();
} else if ($action == 'update_doc_cms_like') {
    $web_obj::update_doc_cms_like();
} else if ($action == 'update_doc_cms_view_count') {
    $web_obj::update_doc_cms_view_count();
} else if ($action == 'add_app_to_circle') {
    $web_obj::add_app_to_circle();
} else if ($action == 'update_app_installed_status_cron_job') {
    $web_obj::update_app_installed_status_cron_job();
} else if ($action == 'get_state_list') {

    $web_obj::get_state_list();
} else if ($action == 'get_city_list') {
    $web_obj::get_city_list();
} else if ($action == 'load_quick_appointment') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "APPOINTMENT", "QUICK APPOINTMENT", $action);
    $web_obj::load_quick_appointment();
} else if ($action == 'load_quick_appointment_for_ivr') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "APPOINTMENT", "QUICK APPOINTMENT", $action);
    $web_obj::load_quick_appointment_for_ivr();
} else if ($action == 'get_doctor_address') {
    $web_obj::get_doctor_address();
} else if ($action == 'get_doctor_time_slot') {
    $web_obj::get_doctor_time_slot();
} else if ($action == 'get_adolescent_vaccination_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "SPECIAL VACCINE", $action);
    $web_obj::get_adolescent_vaccination_list();
} else if ($action == 'add_child_adolescent_vaccination') {

    $web_obj::add_child_adolescent_vaccination();
} else if ($action == 'load_doctor_schedule_appointment') {

    $web_obj::load_doctor_schedule_appointment();
} else if ($action == 'get_doctor_appointment_list') {

    $web_obj::get_doctor_appointment_list();
} else if ($action == 'get_adolescent_vaccination_dose_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "VACCINE & GROWTH", "SPECIAL VACCINE", $action);
    $web_obj::get_adolescent_vaccination_dose_list();
} else if ($action == 'give_child_adolescent_vaccination') {

    $web_obj::give_child_adolescent_vaccination();
} else if ($action == 'delete_child') {

    $web_obj::delete_child();
} else if ($action == 'get_country_list') {
    $web_obj::get_country_list();
} else if ($action == 'get_file_categories') {
    $web_obj::get_file_categories();
} else if ($action == 'give_combine_vaccination') {

    $web_obj::give_combine_vaccination();
} else if ($action == 'add_appointment_reminder') {
    $web_obj::add_appointment_reminder();
} else if ($action == 'get_appointment_reminder_list') {
    $web_obj::get_appointment_reminder_list();
} else if ($action == 'delete_appointment_department') {
    $web_obj::delete_appointment_department();
} else if ($action == 'delete_appointment_doctor') {
    $web_obj::delete_appointment_doctor();
} else if ($action == 'delete_appointment_address') {
    $web_obj::delete_appointment_address();
} else if ($action == 'add_message_action') {
    $web_obj::add_message_action();
} else if ($action == 'delete_appointment_reminder') {
    $web_obj::delete_appointment_reminder();
} else if ($action == 'get_cms_view_user_list') {
    $web_obj::get_cms_view_user_list();
} else if ($action == 'get_child_alpha_list') {
    $web_obj::get_child_alpha_list();
} else if ($action == 'blocked_appointment_slot') {
    $web_obj::blocked_appointment_slot();
} else if ($action == 'get_customer_appointment_history') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "APPOINTMENT", "MY APPOINTMENT", $action);
    $web_obj::get_customer_appointment_history();
} else if ($action == 'get_subscriber_list_for_dropdown') {
    $web_obj::get_subscriber_list_for_dropdown();
} else if ($action == 'update_doc_cms_share_count') {
    $web_obj::update_doc_cms_share_count();
} else if ($action == 'get_message_user_action_list') {
    $web_obj::get_message_user_action_list();
} else if ($action == 'rollback_vaccination_action') {
    $web_obj::rollback_vaccination_action();
} else if ($action == 'test') {
    $web_obj::test();
} else if ($action == 'test_save') {
    $web_obj::test_save();
} /* THIS METHOD ARE DEFINED INTO WEB_SERVICES_2 CLASS*/


else if ($action == 'get_app_status_setting') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "SETTING", "VIEW", $action);

    $web_obj_2_3::get_app_status_setting();
} else if ($action == 'get_app_user_permission_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "SETTING", "VIEW PERMISSION", $action);

    $web_obj_2_3::get_app_user_permission_list();
} else if ($action == 'update_user_permission') {

    $web_obj_2_3::update_user_permission();
} else if ($action == 'update_social_setting') {

    $web_obj_2_3::update_social_setting();
} else if ($action == 'recharge_sms') {

    $web_obj_2_3::recharge_sms();
} else if ($action == 'get_sms_recharge_history') {
    $web_obj_2_3::get_sms_recharge_history();
} else if ($action == 'add_consent_template') {
    $web_obj_2_3::add_consent_template();
} else if ($action == 'get_consent_template_list') {
    $web_obj_2_3::get_consent_template_list();
} else if ($action == 'update_consent_template') {
    $web_obj_2_3::update_consent_template();
} else if ($action == 'send_consent') {
    $web_obj_2_3::send_consent();
} else if ($action == 'get_sent_consent_list') {
    $web_obj_2_3::get_sent_consent_list();
} else if ($action == 'delete_consent_template') {
    $web_obj_2_3::delete_consent_template();
} else if ($action == 'update_consent_action') {

    $web_obj_2_3::update_consent_action();
} else if ($action == 'get_child_timeline') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "TIMELINE", "VIEW", $action);

    $web_obj_2_3::get_child_timeline();
} else if ($action == 'edit_timeline') {

    $web_obj_2_3::edit_timeline();
} else if ($action == 'get_public_child_timeline_list') {

    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "TIMELINE", "VIEW", $action);
    $web_obj_2_3::get_public_child_timeline_list();
} else if ($action == 'add_timeline_action') {

    $web_obj_2_3::add_timeline_action();
} else if ($action == 'get_timeline_list_day_wise') {
    $web_obj_2_3::get_timeline_list_day_wise();
} else if ($action == 'get_timeline_children_list') {
    $web_obj_2_3::get_timeline_children_list();
} else if ($action == 'get_random_child_timline_image') {
    $web_obj_2_3::get_random_child_timline_image();
} else if ($action == 'add_annual_payment') {
    $web_obj_2_3::add_annual_payment();
} else if ($action == 'get_annual_payment_list') {
    $web_obj_2_3::get_annual_payment_list();
} else if ($action == 'add_user_payment') {
    $web_obj_2_3::add_user_payment();
} else if ($action == 'get_payment_history') {
    $web_obj_2_3::get_payment_history();
} else if ($action == 'update_birthday_template') {
    $web_obj_2_3::update_birthday_template();
} else if ($action == 'get_support_chat_app_list') {
    $web_obj_2_3::get_support_chat_app_list();
} else if ($action == 'send_support_chat_notification') {
    $web_obj_2_3::send_support_chat_notification();
} /**********ADDED BY VISHWAJEET START***********/
else if ($action == 'medicine_reminder_add') {
    $web_obj::medicine_reminder_add();
} else if ($action == 'medicine_reminder_list') {
    $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "MEDICINE REMINDER", "VIEW", $action);

    $web_obj::medicine_reminder_list();
} else if ($action == 'medicine_reminder_update_status') {
    $web_obj::medicine_reminder_update_status();
} else if ($action == 'medicine_reminder_edit') {
    $web_obj::medicine_reminder_edit();
} else if ($action == 'get_contest_data') {
    $user_data = Custom::get_user_by_id($user_id);
    $web_obj::addUserStaticToJson('', $user_id, $user_data['thinapp_id'], $user_data['mobile'], "CONTEST", "CONTEST LIST", $action);
    $web_obj::get_contest_data();
} else if ($action == 'get_contest_info') {
    $user_data = Custom::get_user_by_id($user_id);
    $web_obj::addUserStaticToJson('', $user_id, $user_data['thinapp_id'], $user_data['mobile'], "CONTEST", "VIEW", $action);

    $web_obj::get_contest_info();
} else if ($action == 'submit_text_response') {

    $web_obj::submit_text_response();
} else if ($action == 'submit_multi_response') {
    $web_obj::submit_multi_response();
} else if ($action == 'get_answer_data') {

    $user_data = Custom::get_user_by_id($user_id);
    $web_obj::addUserStaticToJson('', $user_id, $user_data['thinapp_id'], $user_data['mobile'], "CONTEST", "RESPONSE LIST", $action);
    $web_obj::get_answer_data();
} else if ($action == 'update_contest_answer_like') {

    $web_obj::update_contest_answer_like();
} else if ($action == 'get_winner_data') {

    $user_data = Custom::get_user_by_id($user_id);
    $web_obj::addUserStaticToJson('', $user_id, $user_data['thinapp_id'], $user_data['mobile'], "CONTEST", "WINNER LIST", $action);
    $web_obj::get_winner_data();
} else if ($action == 'getChildAgeMilestone') {
    $web_obj::getChildAgeMilestone();
} else if ($action == 'get_loc_language') {
    $web_obj::getLocLanguage();
} else if ($action == 'wallet_update_settings') {
    $web_obj::wallet_update_settings();
} else if ($action == 'wallet_redeem_referral_code') {
    $web_obj::wallet_redeem_referral_code();
} else if ($action == 'wallet_get_amount') {
    $web_obj::wallet_get_amount();
} else if ($action == 'wallet_get_history') {
    $web_obj::wallet_get_history();
} else if ($action == 'wallet_get_settings') {
    $web_obj::wallet_get_settings();
} else if ($action == 'wallet_get_user_referral_code') {
    $web_obj::wallet_get_user_referral_code();
} else if ($action == 'update_user_profile') {
    $web_obj::update_user_profile();
} else if ($action == 'add_doctor_refferals') {
    $web_obj::add_doctor_refferals();
} else if ($action == 'get_doctor_refferal_data') {
    $web_obj::get_doctor_refferal_data();
} else if ($action == 'getPresignedUrl') {
    $web_obj::getPresignedUrl();
} else if ($action == 'tab_update_image_certificate') {
    $web_obj::tab_update_image_certificate();
} else if ($action == 'refund_appointment') {
    $web_obj::refund_appointment();
} else if ($action == 'get_payment_stats') {
    $web_obj::get_payment_stats();
} else if ($action == 'update_app_logo') {
    $web_obj::update_app_logo();
} else if ($action == 'get_appointment_reminder') {
    $web_obj::get_appointment_reminder();
} else if ($action == 'get_app_stats') {
    $web_obj::get_app_stats();
}
/**********ADDED BY VISHWAJEET END***********/

/**********TELEMEDECINE START**********/

else if ($action == 'get_telemedicine_setting_data') {
    $web_obj::get_telemedicine_setting_data();
} else if ($action == 'add_telemedicine_availability') {
    $web_obj::add_telemedicine_availability();
} else if ($action == 'add_telemedicine_languages') {
    $web_obj::add_telemedicine_languages();
} else if ($action == 'add_telemedicine_services') {
    $web_obj::add_telemedicine_services();
} else if ($action == 'add_telemedicine_lead') {
    $web_obj::add_telemedicine_lead();
} else if ($action == 'get_telemedicine_setting_user') {
    $web_obj::get_telemedicine_setting_user();
} else if ($action == 'get_telemedicine_lead') {
    $web_obj::get_telemedicine_lead();
} else if ($action == 'update_status_telemedicine_lead') {
    $web_obj::update_status_telemedicine_lead();
} else if ($action == 'get_telemedicine_folder_data') {
    $web_obj::get_telemedicine_folder_data();
} else if ($action == 'add_telemedicine_media') {
    $web_obj::add_telemedicine_media();
} else if ($action == 'get_telemedicine_doctor_list') {
    $web_obj::get_telemedicine_doctor_list();
} else if ($action == 'add_user_payment_to_wallet') {
    $web_obj::add_user_payment_to_wallet();
} else if ($action == 'get_telemedicine_chat_question_list') {
    $web_obj::get_telemedicine_chat_question_list();
} else if ($action == 'add_telemedicine_chat_question_answer') {
    $web_obj::add_telemedicine_chat_question_answer();
} else if ($action == 'get_twilio_vedio_token') {
    $web_obj::get_twilio_vedio_token();
} else if ($action == 'get_twilio_access_token') {
    $web_obj::get_twilio_access_token();
} else if ($action == 'get_twilio_vedio_token') {
    $web_obj::get_twilio_vedio_token();
} else if ($action == 'get_telemedicine_lead_data') {
    $web_obj::get_telemedicine_lead_data();
} else if ($action == 'get_telemedicine_lead_user_list') {
    $web_obj::get_telemedicine_lead_user_list();
} else if ($action == 'send_video_disconnect_notification') {
    $web_obj::send_video_disconnect_notification();
} else if ($action == 'send_video_connect_notification') {
    $web_obj::send_video_connect_notification();
} else if ($action == 'log_audio_call_connect') {
    $web_obj::log_audio_call_connect();
} else if ($action == 'log_audio_call_disconnect') {
    $web_obj::log_audio_call_disconnect();
} else if ($action == 'log_video_call_connect') {
    $web_obj::log_video_call_connect();
} else if ($action == 'log_video_call_disconnect') {
    $web_obj::log_video_call_disconnect();
} else if ($action == 'get_telemedicine_history') {
    $web_obj::get_telemedicine_history();
} else if ($action == 'get_call_history_by_lead') {
    $web_obj::get_call_history_by_lead();
} else if ($action == 'refund_telemedicine') {
    $web_obj::refund_telemedicine();
} else if ($action == 'room_callback') {
    $web_obj::room_callback();
}else if ($action == 'audio_room_callback') {
    $web_obj::audio_room_callback();
}

/**********TELEMEDECINE END**********/

else if ($action == 'load_appointment_address_by_id') {
    $web_obj_2_3::load_appointment_address_by_id();
} else if ($action == 'get_appointment_category_by_id') {
    $web_obj_2_3::get_appointment_category_by_id();
} else if ($action == 'get_appointment_service_detail') {
    $web_obj_2_3::get_appointment_service_detail();
} else if ($action == 'appointment_tracker_detail') {
    $web_obj_2_3::appointment_tracker_detail();
} else if ($action == 'get_health_tip_detail') {
    $web_obj_2_3::get_health_tip_detail();
} else if ($action == 'get_doctor_blog_message_detail') {
    $web_obj_2_3::get_doctor_blog_message_detail();
} else if ($action == 'get_all_patients_folders') {
    $web_obj_2_3::get_all_patients_folders();
} else if ($action == 'add_new_patient_folder') {
    $web_obj_2_3::add_new_patient_folder();
} else if ($action == 'get_prescription_tag_list') {
    $web_obj_2_3::get_prescription_tag_list();
} else if ($action == 'edit_app_banner') {
    $web_obj_2_3::edit_app_banner();
} else if ($action == 'get_consent_detail') {
    $web_obj_2_3::get_consent_detail();
} else if ($action == 'update_instamojo_setting') {
    $web_obj_2_3::update_instamojo_setting();
} else if ($action == 'update_prescription_layout') {
    $web_obj_2_3::update_prescription_layout();
} else if ($action == 'get_folder_prescription_list') {
    $web_obj_2_3::get_folder_prescription_list();
} else if ($action == 'get_app_list_by_category') {
    $web_obj_2_3::get_app_list_by_category();
} else if ($action == 'search_mengage_store_app') {
    $web_obj_2_3::search_mengage_store_app();
} else if ($action == 'search_app_by_category') {
    $web_obj_2_3::search_app_by_category();
} else if ($action == 'get_app_detail') {
    $web_obj_2_3::get_app_detail();
} else if ($action == 'menage_add_follower') {
    $web_obj_2_3::menage_add_follower();
} else if ($action == 'mengage_followed_app_list') {
    $web_obj_2_3::mengage_followed_app_list();
} else if ($action == 'get_doctor_prescription_layout') {
    $web_obj_2_3::get_doctor_prescription_layout();
} else if ($action == 'get_doctor_prescription_signature') {
    $web_obj_2_3::get_doctor_prescription_signature();
} else if ($action == 'get_doctor_profile') {
    $web_obj_2_3::get_doctor_profile();
} /* TAP API START FROM HERE */
else if ($action == 'tab_get_general_info') {
    $web_obj_2_3::tab_get_general_info();
} else if ($action == 'tab_get_children_list') {
    $web_obj_2_3::tab_get_children_list();
} else if ($action == 'tab_update_general_info') {
    $web_obj_2_3::tab_update_general_info();
} else if ($action == 'tab_update_vital_info') {
    $web_obj_2_3::tab_update_vital_info();
} else if ($action == 'tab_manage_reminder') {
    $web_obj_2_3::tab_manage_reminder();
} else if ($action == 'tab_get_prescription_category_list') {
    $web_obj_2_3::tab_get_prescription_category_list();
} else if ($action == 'tab_get_prescription_sub_category_list') {
    $web_obj_2_3::tab_get_prescription_sub_category_list();
} else if ($action == 'tab_get_prescription_template_list') {
    $web_obj_2_3::tab_get_prescription_template_list();
} else if ($action == 'tab_get_category_step') {
    $web_obj_2_3::tab_get_category_step();
} else if ($action == 'tab_manage_step_tag') {
    $web_obj_2_3::tab_manage_step_tag();
} else if ($action == 'tab_add_template') {
    $web_obj_2_3::tab_add_template();
} else if ($action == 'tab_add_step') {
    $web_obj_2_3::tab_add_step();
} else if ($action == 'tab_mangage_step') {
    $web_obj_2_3::tab_mangage_step();
} else if ($action == 'tab_prescription_keywords') {
    $web_obj_2_3::tab_prescription_keywords();
} else if ($action == 'tab_mangage_template') {
    $web_obj_2_3::tab_mangage_template();
} else if ($action == 'tab_manage_sub_category') {
    $web_obj_2_3::tab_manage_sub_category();
} else if ($action == 'tab_save_prescription') {
    $web_obj_2_3::tab_save_prescription();
} else if ($action == 'tab_manage_template_bookmark') {
    $web_obj_2_3::tab_manage_template_bookmark();
} else if ($action == 'tab_update_patient_gender') {
    $web_obj_2_3::tab_update_patient_gender();
} else if ($action == 'tab_add_patient') {
    $web_obj_2_3::tab_add_patient();
} else if ($action == 'tab_save_offline_prescription') {
    $web_obj_2_3::tab_save_offline_prescription();
} else if ($action == 'tab_update_offline_status') {
    $web_obj_2_3::tab_update_offline_status();
} else if ($action == 'tab_add_patient_invoice') {
    $web_obj_2_3::tab_add_patient_invoice();
} else if ($action == 'tab_manage_prescription_type') {
    $web_obj_2_3::tab_manage_prescription_type();
} else if ($action == 'tab_save_medical_certificate') {
    $web_obj_2_3::tab_save_medical_certificate();
} /* TAB API END */

else if ($action == 'writing_save_prescription') {
    $web_obj_2_3::writing_save_prescription();
} else if ($action == 'get_app_features_list') {
    $web_obj_2_3::get_app_features_list();
} else if ($action == 'buy_app_feature') {
    $web_obj_2_3::buy_app_feature();
} else if ($action == 'doctor_refer_functionality_log') {
    $log_type = @$xnc_data['log_type'];
    if ($log_type == "INVITE_SCREEN" || $log_type == "VIEW_REFERRED_DOCTOR") {
        $label = str_replace("_", " ", $log_type);
        $web_obj::addUserStaticToJson($file_name, $user_id, $thin_app_id, $mobile, "REFER_DOCTOR", strtoupper($label), $action);
    }
    exit();
} /* HOSPITAL API START FROM HERE */

else if ($action == 'hospital_manage_department') {
    $web_obj_2_3::hospital_manage_department();
} else if ($action == 'hospital_get_department_list') {
    $web_obj_2_3::hospital_get_department_list();
} else if ($action == 'hospital_department_alpha_list') {
    $web_obj_2_3::hospital_department_alpha_list();
} else if ($action == 'hospital_manage_address') {
    $web_obj_2_3::hospital_manage_address();
} else if ($action == 'hospital_get_address_list') {
    $web_obj_2_3::hospital_get_address_list();
} else if ($action == 'hospital_manage_doctor_profile') {
    $web_obj_2_3::hospital_manage_doctor_profile();
} else if ($action == 'hospital_get_doctor_list') {
    $web_obj_2_3::hospital_get_doctor_list();
} else if ($action == 'hospital_get_doctor_list_all') {
    $web_obj_2_3::hospital_get_doctor_list_all();
} else if ($action == 'hospital_get_address_detail') {
    $web_obj_2_3::hospital_get_address_detail();
} else if ($action == 'hospital_get_doctor_detail') {
    $web_obj_2_3::hospital_get_doctor_detail();
} else if ($action == 'hospital_get_patient_list') {
    $web_obj_2_3::hospital_get_patient_list();
} else if ($action == 'hospital_get_branch_location_list') {
    $web_obj_2_3::hospital_get_branch_location_list();
}

/* HOSPITAL API END FROM HERE */
/* LAB PHARMACY  API START FROM HERE */
else if ($action == 'lab_send_request') {
    $web_obj_2_3::lab_send_request();
} else if ($action == 'lab_get_lab_pharmacy_user_list') {
    $web_obj_2_3::lab_get_lab_pharmacy_user_list();
} else if ($action == 'lab_get_user_detail') {
    $web_obj_2_3::lab_get_user_detail();
} else if ($action == 'lab_update_user_detail') {
    $web_obj_2_3::lab_update_user_detail();
} else if ($action == 'lab_update_request_status') {
    $web_obj_2_3::lab_update_request_status();
} else if ($action == 'lab_get_user_list') {
    $web_obj_2_3::lab_get_user_list();
} else if ($action == 'lab_upload_patient_record') {
    $web_obj_2_3::lab_upload_patient_record();
} else if ($action == 'lab_get_patient_upload_record_list') {
    $web_obj_2_3::lab_get_patient_upload_record_list();
} else if ($action == 'lab_upload_patient_record_from_folder') {
    $web_obj_2_3::lab_upload_patient_record_from_folder();
} else if ($action == 'lab_delete_user') {
    $web_obj_2_3::lab_delete_user();
} else if ($action == 'lab_get_doctor_list') {
    $web_obj_2_3::lab_get_doctor_list();
} else if ($action == 'lab_update_file_status') {
    $web_obj_2_3::lab_update_file_status();
} else if ($action == 'lab_upload_file_to_patient_child_folder') {
    $web_obj_2_3::lab_upload_file_to_patient_child_folder();
} else if ($action == 'lab_update_traker_token') {
    $web_obj_2_3::lab_update_traker_token();
} else if ($action == 'lab_add_patient') {
    $web_obj_2_3::lab_add_patient();
} else if ($action == 'lab_get_patient_list') {
    $web_obj_2_3::lab_get_patient_list();
} else if ($action == 'lab_add_custom_token') {
    $web_obj_2_3::lab_add_custom_token();
} else if ($action == 'tab_add_tag') {
    $web_obj_2_3::tab_add_tag();
} else if ($action == 'tab_get_tag_list') {
    $web_obj_2_3::tab_get_tag_list();
} else if ($action == 'tab_manage_tag') {
    $web_obj_2_3::tab_manage_tag();
} else if ($action == 'get_patient_history') {
    $web_obj::get_patient_history();
} else if ($action == 'get_speech_message_list') {
    $web_obj::get_speech_message_list();
} else if ($action == 'update_speech_message_play_status') {
    $web_obj::update_speech_message_play_status();
} /* LAB PHARMACY  API END FROM HERE */
else if ($action == 'appointment_update_current_token') {
    $web_obj_2_3::appointment_update_current_token();
} else if ($action == 'appointment_dashboard_tracker_doctor') {
    $web_obj_2_3::appointment_dashboard_tracker_doctor();
} else if ($action == 'clipboard_main_search') {
    $web_obj_2_3::clipboard_main_search();
} else if ($action == 'clipboard_add_patient') {
    $web_obj_2_3::clipboard_add_patient();
} else if ($action == 'add_banner_stats') {
    $web_obj_2_3::add_banner_stats();
} else if ($action == 'manage_prescription_setting') {
    $web_obj_2_3::manage_prescription_setting();
} else if ($action == 'get_prescription_setting_list') {
    $web_obj_2_3::get_prescription_setting_list();
} else if ($action == 'get_module_sent_sms_count_list') {
    $web_obj_2_3::get_module_sent_sms_count_list();
} else if ($action == 'manage_custom_prescription') {
    $web_obj_2_3::manage_custom_prescription();
} else if ($action == 'get_custom_layout') {
    $web_obj_2_3::get_custom_layout();
} else if ($action == 'edit_appointment_patient') {
    $web_obj_2_3::edit_appointment_patient();
} else if ($action == 'appointment_skip') {
    $web_obj_2_3::appointment_skip();
} else if ($action == 'get_sms_stats_list') {
    $web_obj_2_3::get_sms_stats_list();
} else if ($action == 'manage_folder_data') {
    $web_obj_2_3::manage_folder_data();
} else if ($action == 'upload_attachment') {
    $web_obj_2_3::upload_attachment();
} else if ($action == 'get_folder_attachment_list') {
    $web_obj_2_3::get_folder_attachment_list();
} else if ($action == 'delete_folder_attachment') {
    $web_obj_2_3::delete_folder_attachment();
} else if ($action == 'get_login_device_list') {
    $web_obj_2_3::get_login_device_list();
} else if ($action == 'send_login_verification_otp') {
    $web_obj_2_3::send_login_verification_otp();
} else if ($action == 'logout_from_device') {
    $web_obj_2_3::logout_from_device();
} else if ($action == 'manage_patient_illness_tag') {
    $web_obj_2_3::manage_patient_illness_tag();
} else if ($action == 'patient_illness_tag_list') {
    $web_obj_2_3::patient_illness_tag_list();
} else if ($action == 'update_hospital_profile') {
    $web_obj_2_3::update_hospital_profile();
} else if ($action == 'get_hospital_profile') {
    $web_obj_2_3::get_hospital_profile();
} else if ($action == 'get_recording_list') {
    $web_obj_2_3::get_recording_list();
} else if ($action == 'get_doctor_appointment_setting') {
    $web_obj_2_3::get_doctor_appointment_setting();
} else if ($action == 'update_doctor_appointment_setting') {
    $web_obj_2_3::update_doctor_appointment_setting();
} else if ($action == 'new_quick_appointment') {
    $web_obj_2_3::new_quick_appointment();
} else if ($action == 'get_appointment_request_data') {
    $web_obj_2_3::get_appointment_request_data();
} else if ($action == 'new_get_doctor_appointment_list') {
    $web_obj_2_3::new_get_doctor_appointment_list();
} else if ($action == 'add_illness_tag_to_patient') {
    $web_obj_2_3::add_illness_tag_to_patient();
} else if ($action == 'new_get_doctor_appointment_schedule') {
    $web_obj_2_3::new_get_doctor_appointment_schedule();
} else if ($action == 'get_validity_appointment_list') {
    $web_obj_2_3::get_validity_appointment_list();
} else if ($action == 'start_post_visit_chat') {
    $web_obj_2_3::start_post_visit_chat();
} else if ($action == 'get_post_visit_chat_history') {
    $web_obj_2_3::get_post_visit_chat_history();
} else if ($action == 'update_post_visit_fees') {
    $web_obj_2_3::update_post_visit_fees();
} else if ($action == 'close_post_visit_chat') {
    $web_obj_2_3::close_post_visit_chat();
} else if ($action == 'new_get_my_folder_list') {
    $web_obj_2_3::new_get_my_folder_list();
} else if ($action == 'ivr_response') {
    $web_obj_2_3::ivr_response();
} else if ($action == 'ivr_end_call') {
    $web_obj_2_3::ivr_end_call();
} else if ($action == 'get_patient_detail_via_barcode') {
    $web_obj_2_3::get_patient_detail_via_barcode();
} else if ($action == 'save_barcode_prescription') {
    $web_obj_2_3::save_barcode_prescription();
} else if ($action == 'save_pen_prescription') {
    $web_obj_2_3::save_pen_prescription();
} else if ($action == 'book_appointment_via_face_reader') {
    $web_obj_2_3::book_appointment_via_face_reader();
} else if ($action == 'get_app_category_list') {
    $web_obj_2_3::get_app_category_list();
} else if ($action == 'pen_get_doctor_list') {
    $web_obj_2_3::pen_get_doctor_list();
} else if ($action == 'pen_update_doctor_mac_address') {
    $web_obj_2_3::pen_update_doctor_mac_address();
} else if ($action == 'get_pen_prescription_upload_history') {
    $web_obj_2_3::get_pen_prescription_upload_history();
} else if ($action == 'tracker_manage_service') {
    $web_obj_2_3::tracker_manage_service();
} else if ($action == 'get_user_app_list') {
    $web_obj_2_3::get_user_app_list();
} else if ($action == 'edit_app_package') {
    $web_obj_2_3::edit_app_package();
} else if ($action == 'send_follow_up_alert') {
    $web_obj_2_3::send_follow_up_alert();
} else if ($action == 'upload_third_party_attachemnt') {
    $web_obj_2_3::upload_third_party_attachemnt();
} else if ($action == 'add_app_stack_trace') {
    $web_obj_2_3::add_app_stack_trace();
} else if ($action == 'check_connectivity') {
    $response['status'] = 1;
    $response['message'] = "Connected";
    echo json_encode($response);
    die;
} else if ($action == 'export_mobile_contact') {
    $web_obj_2_3::export_mobile_contact();
} else if ($action == 'get_contact_us_detail') {
    $web_obj_2_3::get_contact_us_detail();
} else if ($action == 'update_drive_setting') {
    $web_obj_2_3::update_drive_setting();
} else if ($action == 'manage_block_patient') {
    $web_obj_2_3::manage_block_patient();
} else if ($action == 'get_doctor_appointment_service_list') {
    $web_obj_2_3::get_doctor_appointment_service_list();
} else if ($action == 'kos_number_verification_otp') {
    $web_obj_2_3::kos_number_verification_otp();
} else if ($action == 'get_appointment_by_number') {
    $web_obj_2_3::get_appointment_by_number();
} else if ($action == 'trigger_send_custom_folder_file_add_notification') {
    $web_obj_2_3::trigger_send_custom_folder_file_add_notification();
} else if (explode("?", $action)[0] == 'trigger_send_custom_folder_file_add_notification') {
    $web_obj_2_3::trigger_send_custom_folder_file_add_notification();
} else if ($action == 'get_sms_template_list') {
    $web_obj_2_3::get_sms_template_list();
} else if ($action == 'send_kiosk_notification') {
    $web_obj_2_3::send_kiosk_notification();
} else if ($action == 'tab_service_category_list') {
    $web_obj_2_3::tab_service_category_list();
} else if ($action == 'tab_manage_category_and_service') {
    $web_obj_2_3::tab_manage_category_and_service();
} else if ($action == 'order_prescription_medicine') {
    $web_obj_2_3::order_prescription_medicine();
} else if ($action == 'add_patient_payment') {
    $web_obj_2_3::add_patient_payment();
} else if ($action == 'show_emergency_on_tracker') {
    $web_obj_2_3::show_emergency_on_tracker();
} else if ($action == 'tab_synchronize_data') {
    $web_obj_2_3::tab_synchronize_data();
} else if ($action == 'upload_file_to_aws') {
    $web_obj_2_3::upload_file_to_aws();
} else if ($action == 'get_dashboard_data') {
    $web_obj_2_3::get_dashboard_data();
} else if ($action == 'pharmacy_manage_reminder') {
    $web_obj_2_3::pharmacy_manage_reminder();
} else if ($action == 'pharmacy_reminder_list') {
    $web_obj_2_3::pharmacy_reminder_list();
} else if ($action == 'pharmacy_doctor_list') {
    $web_obj_2_3::pharmacy_doctor_list();
} else if ($action == 'pharmacy_add_reminder') {
    $web_obj_2_3::pharmacy_add_reminder();
    die();
} else if ($action == 'pharmacy_get_reminder') {
    $web_obj_2_3::pharmacy_get_reminder();
    die();
} else if ($action == 'pharmacy_get_patient_list') {
    $web_obj_2_3::pharmacy_get_patient_list();
    die();
} else if ($action == 'pharmacy_update_status') {
    $web_obj_2_3::pharmacy_update_status();
    die();
} else if ($action == 'pharmacy_appointment_list') {
    $web_obj_2_3::pharmacy_appointment_list();
} else if ($action == 'lab_appointment_list') {
    $web_obj_2_3::lab_appointment_list();
} else if ($action == 'get_social_link') {
    $web_obj_2_3::get_social_link();
} else if ($action == 'send_sms') {
    $web_obj::send_sms();
}else if ($action == 'search_patient_list') {
    $web_obj_2_3::search_patient_list();
}else if ($action == 'center_pharmacy_app_and_doctor_list') {
    $web_obj_2_3::center_pharmacy_app_and_doctor_list();
}else if ($action == 'get_doctor_running_token_detail') {
    $web_obj_2_3::get_doctor_running_token_detail();
}else if ($action == 'send_to_lab') {
    $web_obj_2_3::send_to_lab();
}else if ($action == 'check_in_patient') {
    $web_obj_2_3::check_in_patient();
}else if ($action == 'get_appointment_list_via_number') {
    $web_obj_2_3::get_appointment_list_via_number();
}else if ($action == 'whatsapp_callback') {
    $web_obj_2_3::whatsapp_callback();
}else if ($action == 'get_doctor_list_via_category_id') {
    $web_obj_2_3::get_doctor_list_via_category_id();
}else if ($action == 'chatboat_doctor_time_slot') {
    $web_obj_2_3::chatboat_doctor_time_slot();
}else if ($action == 'chatboat_book_appointment') {
    $web_obj_2_3::chatboat_book_appointment();
}else if ($action == 'chatboat_doctor_categories_list') {
    $web_obj_2_3::chatboat_doctor_categories_list();
}else if ($action == 'updateNextToken') {
    $queueManage::updateNextToken();
}else if ($action == 'pine_token_booking') {
    $queueManage::pine_token_booking();
}else if ($action=="mq_form_booking") {
    $queueManage::pine_token_booking($_POST);
}else if ($action == 'pine_token_list') {
    $queueManage::pine_token_list();
}else if ($action == 'IoT_close_token') {
    $queueManage::IoT_close_token();
}else if ($action == 'IoT_skip_token') {
    $queueManage::IoT_skip_token();
}else if ($action == 'IoT_send_to_billing_counter') {
    $queueManage::IoT_send_to_billing_counter();
}else if ($action == 'IoT_current_token') {
    $queueManage::IoT_current_token();
}else if ($action == 'pine_get_app_list') {
    $queueManage::pine_get_app_list();
}else if ($action == 'pine_set_current_token') {
    $queueManage::pine_set_current_token();
}else if ($action == 'IoT_previous_token') {
    $queueManage::IoT_previous_token();
}else if ($action == 'IoT_next_token') {
    $queueManage::IoT_next_token();
}else if ($action == 'IoT_play_current_token') {
    $queueManage::IoT_play_current_token();
}