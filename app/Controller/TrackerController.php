<?php

App::uses('AppController', 'Controller');
include(WWW_ROOT . "webservice" . DS . "ConnectionUtil.php");
include(WWW_ROOT . "webservice" . DS . "WebservicesFunction.php");
include(WWW_ROOT . "webservice" . DS . "WebServicesFunction_2_3.php");

class TrackerController extends AppController
{


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();

        $action = @$this->request->params['action'];
        Custom::add_request_hit(@$this->Session->read('Auth.User.User.thinapp_id'), $action);
    }


    /* testing function */
    public function display()
    {
        $this->layout = false;
        $searchData = $this->request->query;
        $thin_app_id = ($searchData['t']);
        $option_string = "<option value='ALL'>All Doctors</option>";
        if (!empty($thin_app_id)) {
            $thin_app_id = base64_decode($thin_app_id);
            $tmp_list = Custom::get_all_app_doctor_id_name($thin_app_id);
            foreach ($tmp_list as $doc_id => $doc_name) {
                $doctor_ids[] = $doc_id;

                $option_string .= "<option value='$doc_id'>$doc_name</option>";
            }
            $app_data = Custom::getThinAppData($thin_app_id);
            $ivr_number = Custom::getAppIvrNumber($thin_app_id);
            $refresh_list_seconds = !empty($app_data['refresh_tracker_list_second']) ? $app_data['refresh_tracker_list_second'] * 1000 : 30000;
            $change_doctor_seconds = !empty($app_data['change_tracker_doctor_second']) ? $app_data['change_tracker_doctor_second'] * 1000 : 15000;

            if (!empty($app_data) && !empty($doctor_ids)) {
                $doctor_ids = implode(",", $doctor_ids);
                $this->set(compact('option_string', 'ivr_number', 'app_data', 'doctor_ids', 'refresh_list_seconds', 'change_doctor_seconds', 'thin_app_id'));
            }
        }
    }

    public function load_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $template_id = ($this->request->data['template_id']);
            $show_time = ($this->request->data['st']);
            $show_patient_name = ($this->request->data['spn']);
            $address_id = @($this->request->data['ai']);
            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $app_name = ($this->request->data['app_name']);
            $logo = ($this->request->data['logo']);
            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $get_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                $return_data = array();
                if (!empty($get_admin_data)) {
                    if ($template_id == 4) {
                        $tracker_data = Custom::getDoctorWebTrackerDataUpcomingList($doctor_id_array, $thin_app_id, 6, $address_id);
                        $tracker_data = @array_values($tracker_data);
                    } else {
                        $tracker_data = Custom::getDoctorWebTrackerData($doctor_id_array, true, $thin_app_id);
                    }
                    $this->layout = 'ajax';
                    $break_array = array();
                    foreach ($doctor_id_array as $key => $doctor_id) {
                        $break_data = Custom::emergency_patient($doctor_id);
                        if (!empty($break_data)) {
                            $break_array[$doctor_id] = json_decode($break_data, true);
                        }
                    }
                    $this->set(compact('thin_app_id', 'lab_and_report_patient', 'tracker_data', 'template_id', 'logo', 'show_time', 'show_patient_name', 'break_array'));
                    $this->render('load_tracker', 'ajax');
                } else {
                    $this->layout = false;
                    echo  "";
                    die;
                }
            }
        }
    }

    public function display_lab_pharmacy($thinappID, $labUserId)
    {

        $this->layout = false;

        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $app_data = Custom::getThinAppData($thin_app_id);
            if (!empty($app_data)) {
                $this->set(compact('app_data', 'thinappID', 'labUserId'));
            }
        }
    }

    public function load_tracker_lab_pharmacy()
    {

        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $labUserId = ($this->request->data['l']);

            $app_name = ($this->request->data['app_name']);
            if (!empty($thin_app_id) && !empty($labUserId)) {
                $thin_app_id = base64_decode($thin_app_id);
                $labUserId = base64_decode($labUserId);
                $tracker_data = Custom::getLabWebTrackerData($thin_app_id, $labUserId);
                if (!empty($tracker_data)) {

                    $this->layout = 'ajax';
                    $this->set('tracker_data', $tracker_data);
                    $this->render('load_tracker_lab_pharmacy', 'ajax');
                } else {
                    $this->layout = false;
                    echo  "";
                    die;
                }
            }
        }
    }


    public function tracker_setting()
    {
        $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        $thin_app_id = $login['thinapp_id'];
        if (!empty($thin_app_id)) {
            $app_data = Custom::getThinAppData($thin_app_id);
            $template_list = Custom::get_tracker_template_list();
            $doctor_list = Custom::get_all_doctor_list($thin_app_id);
            $sql = "SELECT `tracker_voice`,`tracker_media_doctor_id`,`tracker_new_doctor_id`,`tracker_new_refresh_sec`,`tracker_media_refresh_sec`,`tracker_new_show_patient_name`,`tracker_media_show_patient_name`,`tracker_multiple_refresh_sec`,`tracker_multiple_show_patient_name`,tune_tracker_multiple, tune_tracker_media, tune_tracker_new FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $sqlRS = $connection->query($sql);
            $trackerDocData = @mysqli_fetch_assoc($sqlRS);


            $this->set(compact('app_data', 'trackerDocData', 'template_list', 'doctor_list'));
        } else {
            exit();
        }
    }

    public function update_doctor_room()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $rowID = base64_decode($this->request->data['rowID']);
            $room = $this->request->data['room'];
            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $sql = "UPDATE appointment_staffs SET room_number = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $room, $created, $rowID);
            if ($stmt->execute()) {
                Custom::delete_doctor_cache($rowID);
                $response['status'] = 1;
                $response['message'] = "Room Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update room";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function update_tracker_template()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $template_id = $this->request->data['rowID'];
            $rl = $this->request->data['rl'];
            $dc = $this->request->data['dc'];
            $show_time = $this->request->data['st'];
            $show_time = ($show_time == "true") ? "YES" : "NO";
            $show_patient_name = $this->request->data['spn'];
            $show_patient_name = ($show_patient_name == "true") ? "YES" : "NO";
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];
            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $sql = "UPDATE thinapps SET show_patient_name_on_tracker=?, show_tracker_time =?, tracker_template_id = ?, refresh_tracker_list_second=?, change_tracker_doctor_second=?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sssssss', $show_patient_name, $show_time, $template_id, $rl, $dc, $created, $thin_app_id);
            if ($stmt->execute()) {

                $response['status'] = 1;
                $response['message'] = "Template Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update template";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function get_patient_history($thinappID, $uhid, $appointment_id = 0)
    {

        $this->layout = false;
        if (!empty($thinappID) && !empty($uhid)) {
            $thinappID = base64_decode($thinappID);
            $uhid = base64_decode($uhid);
            $data = WebservicesFunction::get_patient_history($thinappID, $uhid);
            if ($data['status'] == 1) {
                $dataToSend = $data['data'];
                $patientDetail = $data['patientDetail'];
                if (!empty($appointment_id)) {
                    $appointment_id = base64_decode($appointment_id);
                    $patientDetail['notes'] = Custom::get_appointment_by_id($appointment_id)['notes'];
                }
            } else {
                $dataToSend = array();
                $patientDetail = array();
            }
        } else {
            $dataToSend = array();
            $patientDetail = array();
        }
        $this->set(array('dataToSend' => $dataToSend, 'patientDetail' => $patientDetail));
    }

    public function print_invoice($order_id, $invoice_type = "OPD")
    {
        $this->layout = false;
        $invoiceData = array();
        $due_paid_amount = " (SELECT pda.amount FROM patient_due_amounts AS pda WHERE pda.settlement_by_order_id = mpo.id AND pda.paid_via_patient ='YES' AND pda.payment_status = 'PAID' AND pda.status='ACTIVE' order by pda.id desc limit 1) AS due_paid_amount ";
        $total_due_amount = " (SELECT SUM(pda.amount) FROM patient_due_amounts AS pda WHERE  pda.medical_product_order_id = mpo.id and pda.paid_via_patient = 'NO' ) AS total_due_amount ";
        if ($invoice_type == "OPD") {
            $orderId = base64_decode($order_id);
            if ($orderId) {
                $query = "select mpo.appointment_customer_staff_service_id as appointment_id, mpq.expiry_date, mpq.batch, t.show_token_on_receipt, t.show_token_time_on_receipt, t.show_doctor_on_receipt, mpo.reffered_by_name, t.show_referrer_on_receipt, mpo.total_amount as total_paid, $due_paid_amount, $total_due_amount, acss.has_token, mpod.show_into_receipt, mpo.id as medical_product_id, mpo.payment_status, mpod.id, mpod.created, mpod.thinapp_id, mpod.tax_value, mpod.discount_amount, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender, IFNULL(hpt.name,'Cash') as payment_type_name, IFNULL(ac.uhid,c.uhid) as uhid, mpo.created as billing_date, acss.slot_time, IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name, IFNULL(mp.name,service) as service_name, mpod.product_price, mpod.total_amount, mpod.tax_type, mpod.quantity, acss.created as appointment_created ,acss.queue_number, t.name as app_name, t.logo, t.receipt_top_left_title, t.receipt_header_title, t.receipt_footer_title, department.name as department_name, aa.address, biller.name as created_by, app_staff.name as consult_with, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age from medical_product_orders as mpo join thinapps as t on t.id = mpo.thinapp_id  left join appointment_customer_staff_services as acss ON acss.id = mpo.appointment_customer_staff_service_id left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id  left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id left join appointment_staffs as app_staff on app_staff.id = acss.appointment_staff_id left join appointment_staffs as biller on biller.user_id =mpo.created_by_user_id and biller.status = 'ACTIVE' and biller.thinapp_id = mpo.thinapp_id and biller.staff_type IN('DOCTOR','RECEPTIONIST') left join medical_products as mp on mp.id = mpod.medical_product_id left join appointment_categories as department on department.id= app_staff.appointment_category_id left join appointment_addresses as aa on aa.id= acss.appointment_address_id WHERE mpo.id = $orderId and mpo.status = 'ACTIVE' GROUP BY mpod.id";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
                }
            }
        } else if ($invoice_type == "IPD") {
            $order_id = base64_decode($order_id);
            if ($order_id) {
                $query = "select mpq.expiry_date, mpq.batch, t.show_token_on_receipt, t.show_token_time_on_receipt, t.show_doctor_on_receipt, mpo.reffered_by_name, t.show_referrer_on_receipt, mpo.total_amount as total_paid,   (SELECT pda.amount FROM patient_due_amounts AS pda WHERE pda.settlement_by_order_id = mpo.id AND pda.paid_via_patient ='YES' AND pda.payment_status = 'PAID' AND pda.status='ACTIVE' order by pda.id desc limit 1) AS due_paid_amount ,  (SELECT SUM(pda.amount) FROM patient_due_amounts AS pda WHERE  pda.medical_product_order_id = mpo.id and pda.paid_via_patient = 'NO' ) AS total_due_amount , 'YES' AS show_into_receipt, hi.admit_date, hi.ipd_unique_id,  mpo.payment_status,  mpo.hospital_ipd_id, mpo.is_package, mpo.id as medical_product_id, mpod.id, mpod.created, mpod.thinapp_id, mpod.tax_value, mpod.discount_amount, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender,  IFNULL(hpt.name,'Cash') as payment_type_name, IFNULL(ac.uhid,c.uhid) as uhid, mpo.created as billing_date, IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name,  IFNULL(mp.name,service) as service_name, mpod.product_price, mpod.total_amount, mpod.tax_type, mpod.quantity, mpo.created AS created , t.name as app_name, t.logo, t.receipt_top_left_title, t.receipt_header_title, t.receipt_footer_title, (SELECT GROUP_CONCAT(department.name) FROM appointment_staffs AS doctor left JOIN appointment_categories AS department ON department.id = doctor.appointment_category_id WHERE doctor.id IN (SELECT (mpod.appointment_staff_id) FROM medical_product_order_details AS mpod WHERE mpod.medical_product_order_id = mpo.id )) as department_name, aa.address, biller.name as created_by, (SELECT GROUP_CONCAT(doctor.name) FROM appointment_staffs AS doctor left JOIN appointment_categories AS department ON department.id = doctor.appointment_category_id WHERE doctor.id IN (SELECT (mpod.appointment_staff_id) FROM medical_product_order_details AS mpod WHERE mpod.medical_product_order_id = mpo.id )) as consult_with, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age FROM medical_product_orders as mpo   LEFT  join appointment_customers as ac on ac.id = mpo.appointment_customer_id left join childrens as c on c.id = mpo.children_id left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id  left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id  left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id  left join appointment_staffs as app_staff on app_staff.id = mpo.appointment_staff_id left join hospital_ipd  as hi on hi.id = mpo.hospital_ipd_id left join appointment_staffs as biller on biller.user_id =mpo.created_by_user_id and biller.status = 'ACTIVE' and biller.thinapp_id = mpo.thinapp_id and biller.staff_type IN('DOCTOR','RECEPTIONIST') left join medical_products as mp on mp.id = mpod.medical_product_id left join appointment_categories as department on department.id= app_staff.appointment_category_id join thinapps as t on t.id = mpo.thinapp_id left join appointment_addresses as aa on aa.id= mpo.appointment_address_id where mpo.id = $order_id and mpo.status = 'ACTIVE' GROUP BY mpod.id";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
                }
            }
        } else if ($invoice_type == "IAD") {
            $order_id = base64_decode($order_id);
            if ($order_id) {
                $query = "select  mpq.expiry_date, mpq.batch, t.show_token_on_receipt, t.show_token_time_on_receipt, t.show_doctor_on_receipt, mpo.reffered_by_name, t.show_referrer_on_receipt, 'YES' AS show_into_receipt, hi.admit_date, hi.ipd_unique_id,  mpo.payment_status,  mpo.hospital_ipd_id, mpo.is_package, mpo.id as medical_product_id, mpod.id, mpod.created, mpod.thinapp_id, mpod.tax_value, mpod.discount_amount, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender,  IFNULL(hpt.name,'Cash') as payment_type_name, IFNULL(ac.uhid,c.uhid) as uhid, mpo.created as billing_date, IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name,  'Advance Deposit' as service_name,  mpo.total_amount, mpo.total_amount as product_price, mpod.tax_type, '1' as quantity, mpo.created AS created , t.name as app_name, t.logo, t.receipt_top_left_title, t.receipt_header_title, t.receipt_footer_title, department.name as department_name, aa.address, biller.name as created_by, app_staff.name as consult_with, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age FROM medical_product_orders as mpo   LEFT  join appointment_customers as ac on ac.id = mpo.appointment_customer_id left join childrens as c on c.id = mpo.children_id left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id  left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id left join appointment_staffs as app_staff on app_staff.id = mpo.appointment_staff_id left join hospital_ipd  as hi on hi.id = mpo.hospital_ipd_id left join appointment_staffs as biller on biller.user_id =mpo.created_by_user_id and biller.status = 'ACTIVE' and biller.thinapp_id = mpo.thinapp_id and biller.staff_type IN('DOCTOR','RECEPTIONIST') left join medical_products as mp on mp.id = mpod.medical_product_id left join  appointment_categories as department on department.id= app_staff.appointment_category_id join thinapps as t on t.id = mpo.thinapp_id left join appointment_addresses as aa on aa.id= mpo.appointment_address_id where mpo.id = $order_id and mpo.status = 'ACTIVE' GROUP BY mpod.id";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
                }
            }
            $invoice_type = 'IPD_ADVANCE_DEPOSIT';
        } else if ($invoice_type == "DUE") {
            $order_id = base64_decode($order_id);
            if ($order_id) {
                $query = "select mpq.expiry_date, mpq.batch, t.show_token_on_receipt, t.show_token_time_on_receipt, t.show_doctor_on_receipt, mpo.reffered_by_name, t.show_referrer_on_receipt, mpo.total_amount as total_paid,  $due_paid_amount, $total_due_amount, 'YES' AS show_into_receipt, hi.admit_date, hi.ipd_unique_id,  mpo.payment_status,  mpo.hospital_ipd_id, mpo.is_package, mpo.id as medical_product_id, mpod.id, mpod.created, mpod.thinapp_id, mpod.tax_value, mpod.discount_amount, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender,  IFNULL(hpt.name,'Cash') as payment_type_name, IFNULL(ac.uhid,c.uhid) as uhid, mpo.created as billing_date, IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name,  IFNULL(mp.name,service) as service_name, mpod.product_price, mpod.total_amount, mpod.tax_type, mpod.quantity, mpo.created AS created , t.name as app_name, t.logo, t.receipt_top_left_title, t.receipt_header_title, t.receipt_footer_title, (SELECT aa.address FROM appointment_addresses AS aa WHERE aa.thinapp_id = mpod.thinapp_id AND aa.status='ACTIVE' LIMIT 1) AS address, biller.name as created_by, app_staff.name as consult_with, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age FROM medical_product_orders as mpo   LEFT  join appointment_customers as ac on ac.id = mpo.appointment_customer_id left join childrens as c on c.id = mpo.children_id left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id  left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id  left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id left join appointment_staffs as app_staff on app_staff.id = mpo.appointment_staff_id left join hospital_ipd  as hi on hi.id = mpo.hospital_ipd_id left join appointment_staffs as biller on biller.user_id =mpo.created_by_user_id and biller.status = 'ACTIVE' and biller.thinapp_id = mpo.thinapp_id and biller.staff_type IN('DOCTOR','RECEPTIONIST') left join medical_products as mp on mp.id = mpod.medical_product_id  join thinapps as t on t.id = mpo.thinapp_id  where mpo.id = $order_id and mpo.status = 'ACTIVE' GROUP BY mpod.id";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
                }
            }
        } else {
            exit();
        }

        if (!empty($invoiceData)) {
            $this->set(compact('invoiceData', 'invoice_type'));
        } else {
            die('Invalid Request');
        }
    }

    public function print_invoice_non_opd_advance($orderID)
    {
        $this->layout = false;

        $orderID = base64_decode($orderID);
        if ($orderID) {


            $orderDetails = $this->MedicalProductOrder->find('first', array(
                'fields' => array('MedicalProductOrder.*', "(SELECT CONCAT(DATE_FORMAT(`MedicalProductOrder`.`created`,'%d%m%y'),COUNT(`id`)) FROM `medical_product_orders` AS `order` WHERE  DATE(`order`.`created`) = DATE(`MedicalProductOrder`.`created`) AND `order`.`id` <= `MedicalProductOrder`.`id` AND `order`.`thinapp_id` = `MedicalProductOrder`.`thinapp_id` AND `order`.`is_expense` = 'N') AS `unique_id`"),
                'conditions' => array('MedicalProductOrder.id' => $orderID), 'recursive' => 2,
            ));

            $hospitalDepositData = $this->HospitalDepositAmount->findById($orderDetails['MedicalProductOrder']['hospital_deposit_amount_id']);

            if (isset($orderDetails['Children']['id']) && $orderDetails['Children']['id'] != 0) {
                $age = Custom::dob_elapsed_string($orderDetails['Children']['dob'], false, false);
                $UHID = $orderDetails['Children']['uhid'];
            } else {
                $dob = $orderDetails['AppointmentCustomer']['dob'];
                if (!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00') {
                    $age = Custom::dob_elapsed_string($dob, false, false);
                } else {
                    $age = $orderDetails['AppointmentCustomer']['age'];
                }

                $UHID = $orderDetails['AppointmentCustomer']['uhid'];
            }

            if (!empty($orderDetails['MedicalProductOrder']['created_by_user_id'])) {
                $staffData = $this->AppointmentStaff->find(
                    'first',
                    array(
                        'fields' => array('AppointmentStaff.name'),
                        'conditions' => array('AppointmentStaff.user_id' => $orderDetails['MedicalProductOrder']['created_by_user_id'])
                    )
                );
                if (isset($staffData['AppointmentStaff']['name'])) {
                    $createdBy = $staffData['AppointmentStaff']['name'];
                } else {
                    $staffData = $this->User->find(
                        'first',
                        array(
                            'fields' => array('User.username'),
                            'conditions' => array('User.id' => $orderDetails['MedicalProductOrder']['created_by_user_id'])
                        )
                    );
                    $createdBy = isset($staffData['User']['username']) ? $staffData['User']['username'] : "";
                }
            } else {
                $createdBy = "";
            }


            $this->set(array('createdBy' => $createdBy, 'age' => $age, 'UHID' => $UHID, 'orderDetails' => $orderDetails, 'hospitalDepositData' => $hospitalDepositData));
        }
    }

    public function print_invoice_non_opd_settlement($orderID)
    {
        $this->layout = false;

        $orderID = base64_decode($orderID);
        if ($orderID) {

            $orderDetails = $this->MedicalProductOrder->find('first', array(
                'conditions' => array('MedicalProductOrder.id' => $orderID),
                'contain' => array('AppointmentCustomer', 'Children', 'AppointmentStaff', 'Thinapp', 'HospitalIpdSettlement'),
                'fields' => array("(SELECT CONCAT(DATE_FORMAT(`MedicalProductOrder`.`created`,'%d%m%y'),COUNT(`id`)) FROM `medical_product_orders` AS `order` WHERE  DATE(`order`.`created`) = DATE(`MedicalProductOrder`.`created`) AND `order`.`id` <= `MedicalProductOrder`.`id` AND `order`.`thinapp_id` = `MedicalProductOrder`.`thinapp_id` AND `order`.`is_expense` = 'N') AS `unique_id`", 'HospitalIpdSettlement.payment_status', 'HospitalIpd.ipd_unique_id', 'MedicalProductOrder.*', 'AppointmentCustomer.*', 'Children.*', 'AppointmentStaff.name', 'Thinapp.receipt_header_title', 'Thinapp.name', 'Thinapp.logo'),
                'joins' => array(
                    array(
                        'table' => 'hospital_ipd',
                        'alias' => 'HospitalIpd',
                        'type' => 'LEFT',
                        'conditions' => array('HospitalIpd.id = MedicalProductOrder.hospital_ipd_id')
                    )
                )

            ));

            $ipdUniqueId = @$orderDetails['HospitalIpd']['ipd_unique_id'];
            $createdBy = @$orderDetails['AppointmentStaff']['name'];

            if (isset($orderDetails['Children']['id']) && $orderDetails['Children']['id'] != 0) {
                $age = Custom::dob_elapsed_string($orderDetails['Children']['dob'], false, false);
                $UHID = $orderDetails['Children']['uhid'];
            } else {
                $dob = $orderDetails['AppointmentCustomer']['dob'];
                if (!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00') {
                    $age = Custom::dob_elapsed_string($dob, false, false);
                } else {
                    $age = $orderDetails['AppointmentCustomer']['age'];
                }

                $UHID = $orderDetails['AppointmentCustomer']['uhid'];
            }


            $this->set(array('ipdUniqueId' => $ipdUniqueId, 'createdBy' => $createdBy, 'age' => $age, 'UHID' => $UHID, 'orderDetails' => $orderDetails));
        }
    }

    public function print_invoice_non_opd($orderID)
    {
        $this->layout = false;

        $orderID = base64_decode($orderID);
        if ($orderID) {


            $orderDetails = $this->MedicalProductOrder->find('first', array(
                'fields' => array('MedicalProductOrder.*', "(SELECT CONCAT(DATE_FORMAT(`MedicalProductOrder`.`created`,'%d%m%y'),COUNT(`id`)) FROM `medical_product_orders` AS `order` WHERE  DATE(`order`.`created`) = DATE(`MedicalProductOrder`.`created`) AND `order`.`id` <= `MedicalProductOrder`.`id` AND `order`.`thinapp_id` = `MedicalProductOrder`.`thinapp_id` AND `order`.`is_expense` = 'N') AS `unique_id`"),
                'conditions' => array('MedicalProductOrder.id' => $orderID), 'recursive' => 2,
            ));
            if (isset($orderDetails['Children']['id']) && $orderDetails['Children']['id'] != 0) {
                $age = Custom::dob_elapsed_string($orderDetails['Children']['dob'], false, false);
                $UHID = $orderDetails['Children']['uhid'];
            } else {
                $dob = $orderDetails['AppointmentCustomer']['dob'];
                if (!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00') {
                    $age = Custom::dob_elapsed_string($dob, false, false);
                } else {
                    $age = $orderDetails['AppointmentCustomer']['age'];
                }

                $UHID = $orderDetails['AppointmentCustomer']['uhid'];
            }

            if (!empty($orderDetails['MedicalProductOrder']['created_by_user_id'])) {
                $staffData = $this->AppointmentStaff->find(
                    'first',
                    array(
                        'fields' => array('AppointmentStaff.name'),
                        'conditions' => array('AppointmentStaff.user_id' => $orderDetails['MedicalProductOrder']['created_by_user_id'])
                    )
                );
                if (isset($staffData['AppointmentStaff']['name'])) {
                    $createdBy = $staffData['AppointmentStaff']['name'];
                } else {
                    $staffData = $this->User->find(
                        'first',
                        array(
                            'fields' => array('User.username'),
                            'conditions' => array('User.id' => $orderDetails['MedicalProductOrder']['created_by_user_id'])
                        )
                    );
                    $createdBy = isset($staffData['User']['username']) ? $staffData['User']['username'] : "";
                }
            } else {
                $createdBy = "";
            }


            $deposit = array();
            $expense = array();
            $ipdUniqueId = '';
            if ($orderDetails['MedicalProductOrder']['hospital_ipd_id'] > 0) {
                $ipd_id = $orderDetails['MedicalProductOrder']['hospital_ipd_id'];
                $deposit = $this->MedicalProductOrder->find("first", array("fields" => array("SUM(`MedicalProductOrder`.`total_amount`) AS `total_deposit`"), "conditions" => array("`MedicalProductOrder`.`status`" => "ACTIVE", "`MedicalProductOrder`.`is_advance`" => "Y", "`MedicalProductOrder`.`is_opd`" => "N", "`MedicalProductOrder`.`hospital_ipd_id`" => $ipd_id)));
                $expense = $this->MedicalProductOrder->find("first", array("fields" => array("SUM(`MedicalProductOrder`.`total_amount`) AS `total_expense`"), "conditions" => array("`MedicalProductOrder`.`status`" => "ACTIVE", "`MedicalProductOrder`.`is_advance`" => "N", "`MedicalProductOrder`.`is_opd`" => "N", "`MedicalProductOrder`.`hospital_ipd_id`" => $ipd_id)));
                $ipdDataUnq = $this->HospitalIpd->find("first", array("fields" => array("ipd_unique_id"), "conditions" => array("`HospitalIpd`.`id`" => $ipd_id)));
                if (isset($ipdDataUnq['HospitalIpd']['ipd_unique_id'])) {
                    $ipdUniqueId = $ipdDataUnq['HospitalIpd']['ipd_unique_id'];
                }
            }
            $titleArr = array();
            if ($orderDetails['MedicalProductOrder']['lab_pharmacy_user_id'] > 0) {
                $title = $this->LabPharmacyUser->find('first', array('fields' => array('LabPharmacyUser.receipt_header_title', 'LabPharmacyUser.receipt_footer_title'), 'conditions' => array('LabPharmacyUser.id' => $orderDetails['MedicalProductOrder']['lab_pharmacy_user_id'])));

                if ($title['LabPharmacyUser']['receipt_header_title'] != '') {
                    $titleArr['receipt_header_title'] = $title['LabPharmacyUser']['receipt_header_title'];
                }
                if ($title['LabPharmacyUser']['receipt_footer_title'] != '') {
                    $titleArr['receipt_footer_title'] = $title['LabPharmacyUser']['receipt_footer_title'];
                }
            }


            $this->set(array('titleArr' => $titleArr, 'createdBy' => $createdBy, 'age' => $age, 'UHID' => $UHID, 'orderDetails' => $orderDetails));
        }
    }

    public function get_show_drive_file_modal()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $fileID = ($this->request->data['fileID']);
            $file = $this->DriveFile->findById($fileID, array('contain' => false));
            $this->set(compact('file'));
            $this->render('get_show_drive_file_modal', 'ajax');
        } else {
            exit();
        }
    }


    public function display_lab_pharmacy_tracker_new($thinappID, $labUserId)
    {

        $this->layout = false;

        if (!empty($thinappID)) {
            if (!empty($labUserId)) {
                $this->set(compact('thinappID', 'labUserId'));
            }
        }
    }

    public function load_lab_pharmacy_tracker_new()
    {

        $this->layout = false;
        $thin_app_id = ($this->request->data['t']);
        $labUserId = ($this->request->data['l']);
        $response = array();
        if (!empty($thin_app_id) && !empty($labUserId)) {
            $thin_app_id = base64_decode($thin_app_id);
            $labUserId = base64_decode($labUserId);
            $tracker_data = Custom::getLoadLabPharmacyTrackerNew($thin_app_id, $labUserId);
            if (!empty($tracker_data)) {

                $response = array(
                    "status" => 1,
                    "data" => $tracker_data
                );
            } else {
                $response = array(
                    "status" => 0
                );
            }
        } else {
            $response = array(
                "status" => 0
            );
        }
        echo json_encode($response);
        die;
    }

    public function display_lab_pharmacy_tracker_second($thinappID, $labUserId)
    {

        $this->layout = false;

        if (!empty($thinappID)) {
            if (!empty($labUserId)) {
                $this->set(compact('thinappID', 'labUserId'));
            }
        }
    }

    public function load_lab_pharmacy_tracker_second()
    {
        $this->layout = false;
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $labUserId = ($this->request->data['l']);
            $response = array();
            if (!empty($thin_app_id) && !empty($labUserId)) {
                $thin_app_id = base64_decode($thin_app_id);
                $labUserId = base64_decode($labUserId);
                $tracker_data = Custom::getLoadLabPharmacyTrackerNew($thin_app_id, $labUserId);
                if (!empty($tracker_data)) {

                    $response = array(
                        "status" => 1,
                        "data" => $tracker_data
                    );
                } else {
                    $response = array(
                        "status" => 0
                    );
                }
            } else {
                $response = array(
                    "status" => 0
                );
            }
            echo json_encode($response);
            die;
        }
    }

    public function track_your_appointment($uhid, $thinappID, $return = 0)
    {
        $this->layout = false;
        $showTracker = true;
        $mobile = isset($this->request->data['m']) ? $this->request->data['m'] : '';
        $doctor_id = isset($this->request->data['di']) ? base64_decode($this->request->data['di']) : '';
        if (!empty($thinappID) && (!empty($uhid) || !empty($mobile))) {
            $break_array = array();
            $thinappID = base64_decode($thinappID);
            $uhid = base64_decode($uhid);
            if ($mobile) {
                $mobile = Custom::create_mobile_number($mobile);
                $query = "SELECT ac.uhid from appointment_customer_staff_services AS acss JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id WHERE acss.thinapp_id = $thinappID AND ac.mobile = '$mobile' AND acss.appointment_staff_id = $doctor_id AND acss.`status` IN ('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = DATE(NOW()) and acss.is_paid_booking_convenience_fee IN ('NOT_APPLICABLE','YES') LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $uhid = mysqli_fetch_assoc($list_obj)['uhid'];
                }
            }
            $patientID = $this->AppointmentCustomer->find("first", array("fields" => array("AppointmentCustomer.id"), "conditions" => array("AppointmentCustomer.uhid" => $uhid, "AppointmentCustomer.thinapp_id" => $thinappID), "conatin" => array('Thinapps')));
            $patientID = empty($patientID["AppointmentCustomer"]["id"]) ? 0 : $patientID["AppointmentCustomer"]["id"];
            if (!$patientID) {
                $childID = $this->Children->find("first", array("fields" => array("Children.id"), "conditions" => array("Children.uhid" => $uhid, "Children.thinapp_id" => $thinappID), "conatin" => array('Thinapps')));
                $childID = empty($childID["Children"]["id"]) ? 0 : $childID["Children"]["id"];
            } else {
                $childID = 0;
            }
            //$currentDate = date('Y-m-d H:i:s');
            //$newTime = date("Y-m-d H:i:s",strtotime("+30 minutes", strtotime($currentDate)));
            $newTime = date("Y-m-d");

            $userAppointmentData = $this->AppointmentCustomerStaffService->find(
                "first",
                array(
                    "fields" => array(
                        "AppointmentCustomer.first_name",
                        "AppointmentCustomerStaffService.reason_of_appointment",
                        "Thinapp.category_name",
                        "Children.child_name",
                        "AppointmentStaff.name",
                        "AppointmentStaff.show_appointment_time",
                        "AppointmentStaff.show_appointment_token",
                        "AppointmentCustomerStaffService.appointment_datetime",
                        "AppointmentCustomerStaffService.has_token",
                        "AppointmentCustomerStaffService.sub_token",
                        "AppointmentCustomerStaffService.slot_time",
                        "AppointmentCustomerStaffService.queue_number",
                        "AppointmentCustomerStaffService.appointment_service_id",
                        "AppointmentCustomerStaffService.appointment_address_id",
                        "AppointmentCustomerStaffService.slot_duration",
                        "AppointmentCustomerStaffService.patient_queue_type",
                        "AppointmentCustomerStaffService.emergency_appointment",
                        "AppointmentCustomerStaffService.appointment_staff_id",
                        "AppointmentCustomerStaffService.patient_queue_type",
                        "AppointmentCustomerStaffService.referred_by as counter",
                    ),
                    "contain" => array(
                        "AppointmentStaff",
                        "AppointmentCustomer",
                        "Children",
                        "Thinapp"
                    ),
                    "conditions" => array(
                        "AppointmentCustomerStaffService.appointment_customer_id" => $patientID,
                        "DATE(AppointmentCustomerStaffService.appointment_datetime)" => $newTime,
                        "AppointmentCustomerStaffService.is_paid_booking_convenience_fee IN ('NOT_APPLICABLE','YES')",
                        "AppointmentCustomerStaffService.children_id" => $childID,
                        "AppointmentCustomerStaffService.thinapp_id" => $thinappID,
                        "AppointmentCustomerStaffService.delete_status" => "NONE",
                        "AppointmentCustomerStaffService.status NOT IN ('CANCELED','CLOSED','EXPIRED','REFUND')"
                    )
                )
            );

            if (!empty($userAppointmentData)) {
                $doctor_id_array = array($userAppointmentData['AppointmentStaff']['id']);
                $get_default_tracker_data = true;
                if ($thinappID == CK_BIRLA_APP_ID) {
                    if ($counter_list = Custom::get_billing_counter_list($thinappID)) {
                        if (in_array($userAppointmentData['AppointmentCustomerStaffService']['counter'], $counter_list)) {
                            $get_default_tracker_data = false;
                            $userAppointmentData['AppointmentStaff']['name'] = 'Billing';
                            $doctor_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_staff_id'];
                            $query = "SELECT acss.slot_time as time_slot, acss.patient_queue_type, acss.queue_number as token_number, acss.appointment_staff_id AS doctor_id  FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' AND acss.patient_queue_type ='BILLING_CHECKIN' and acss.thinapp_id = $thinappID and acss.appointment_staff_id = $doctor_id ORDER BY acss.send_to_lab_datetime ASC limit 1";
                            $connection = ConnectionUtil::getConnection();
                            $list = $connection->query($query);
                            $token_list = array();
                            if ($list->num_rows) {
                                $otherTokenData[0] = mysqli_fetch_assoc($list);
                            }
                        }
                    }
                }
                if ($get_default_tracker_data === true) {
                    $otherTokenData = Custom::getDoctorWebTrackerData($doctor_id_array, true, $thinappID);
                }


                $doctor_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_staff_id'];
                $service_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_service_id'];
                $address_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_address_id'];
                $token_number = $userAppointmentData['AppointmentCustomerStaffService']['queue_number'];
                $slot_duration = $userAppointmentData['AppointmentCustomerStaffService']['slot_duration'];
                $total_update_minutes = Custom::get_total_difference_minutes($service_id, $address_id, $doctor_id, $userAppointmentData['AppointmentCustomerStaffService']['appointment_datetime'], true);
                $waiting_time = Custom::get_waiting_time($thinappID, $doctor_id, $address_id, $service_id, $token_number, $slot_duration);
                if (!empty($total_update_minutes)) {
                    $selectedTime = date('H:i:s', strtotime($userAppointmentData['AppointmentCustomerStaffService']['appointment_datetime']));
                    $minutes_string = ($total_update_minutes >= 0) ? "+$total_update_minutes minutes" : "$total_update_minutes minutes";
                    $time = strtotime($minutes_string, strtotime($selectedTime));
                    $time = date('h:i A', $time);
                } else {
                    $time = $userAppointmentData['AppointmentCustomerStaffService']['slot_time'];
                }

                $tracker_date = date('Y-m-d', strtotime($userAppointmentData['AppointmentCustomerStaffService']['appointment_datetime']));
                if ($tracker_date != date('Y-m-d')) {
                    $showTracker = false;
                }
            } else {
                $showTracker = false;
            }

            if (!empty($userAppointmentData)) {
                $break_data = Custom::emergency_patient($doctor_id);
                if (!empty($break_data)) {
                    $break_array = json_decode($break_data, true);
                }
            }




            if ($return) {
                $this->autoRender = false;
                $return = $current = $self = array();
                $current['patient_name'] = 'N/A';
                $current['token_number'] = 'N/A';
                $current['time'] = 'N/A';
                if (!empty($userAppointmentData)) {
                    $self['patient_name'] = $userAppointmentData['AppointmentCustomer']['first_name'];
                    $self['token_number'] = Custom::create_queue_number($userAppointmentData['AppointmentCustomerStaffService']);
                    $self['time'] = $userAppointmentData['AppointmentCustomerStaffService']['slot_time'];
                }
                if (!empty($otherTokenData)) {
                    $current['patient_name'] = $otherTokenData[0]['patient_name'];
                    $current['token_number'] = Custom::create_queue_number($otherTokenData[0]);
                    $current['time'] = $otherTokenData[0]['time_slot'];
                }
                $time_string = "0";
                if ($waiting_time > 0) {
                    $time_string = "";
                    $hours = floor($waiting_time / 60);
                    if ($hours > 0) {
                        $time_string = ($hours > 1) ? $hours . " Hours " : $hours . " Hour ";
                    }
                    $minutes = ($waiting_time -   floor($waiting_time / 60) * 60);
                    if ($minutes > 0) {
                        $time_string .= ($minutes > 1) ? $minutes . " Minutes " : $minutes . " Minute ";
                    }
                }
                $this->set(compact('self', 'current', 'time_string'));
                $this->render('web_app_home_tracker');
            } else {
                $this->set(compact('thinappID', 'showTracker', 'break_array', 'userAppointmentData', 'otherTokenData', 'time', 'waiting_time'));
            }
        }
    }


    public function update_doctor_tracker_new()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $docIDArr = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $docID = json_encode($docIDArr);
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_new_doctor_id = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $docID, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function update_doctor_tracker_media()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $docIDArr = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];
            $docID = json_encode($docIDArr);



            $sql = "UPDATE thinapps SET tracker_media_doctor_id = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $docID, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function tracker_new_update_refresh_sec()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $refreshSec = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_new_refresh_sec = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $refreshSec, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function show_patient_name_tracker_new()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $val = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_new_show_patient_name = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $val, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function tracker_media_update_refresh_sec()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $refreshSec = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_media_refresh_sec = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $refreshSec, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function show_patient_name_tracker_media()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $val = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_media_show_patient_name = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $val, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function tracker_multiple_update_refresh_sec()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $refreshSec = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_multiple_refresh_sec = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $refreshSec, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function show_patient_name_tracker_multiple()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $val = $this->request->data['ID'];

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_multiple_show_patient_name = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $val, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }







    public function save_tracker_token_data()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $thinappID = base64_decode($this->request->data['t']);
            $token = $this->request->data['token'];
            $type = $this->request->data['type'];
            $doctor_ids = isset($this->request->data['doctor_ids']) ? $this->request->data['doctor_ids'] : "";

            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();

            $selectSql = "SELECT `id` FROM `tracker_firebase_token` WHERE `thinapp_id` = '" . $thinappID . "' AND `token` = '" . $token . "'  LIMIT 1";

            $selectRS = $connection->query($selectSql);
            if (!$selectRS->num_rows) {
                $idInsert = ($doctor_ids != "") ? "`doctor_ids` = '" . $doctor_ids . "'," : "";
                $insertSql = "INSERT INTO `tracker_firebase_token` SET " . $idInsert . "`thinapp_id` = '" . $thinappID . "', `token` = '" . $token . "', `tracker_type` = '" . $type . "', `created` = '$created', `modified`='" . $created . "'";

                if ($connection->query($insertSql)) {
                    $response['status'] = 1;
                    $response['message'] = "Update Successfully";
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Can't Update";
                }
            } else {

                /*if($doctor_ids != "")
                { */
                $updateSql = "UPDATE `tracker_firebase_token` SET `doctor_ids` = '" . $doctor_ids . "',  `tracker_type` = '" . $type . "' WHERE `thinapp_id` = '" . $thinappID . "' AND `token` = '" . $token . "'";

                $connection->query($updateSql);
                /* } */

                $response['status'] = 1;
                $response['message'] = "Success";
            }

            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function emergency_patient($doctor_id = null)
    {
        $this->autoRender = false;
        if (empty($doctor_id)) {
            $doctor_id = base64_decode($this->request->data['di']);
        }

        return Custom::emergency_patient($doctor_id);
    }




    public function display_tracker_opd_media($thinappID)
    {
        $this->layout = false;
        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctorIDs = implode(",", $doctorIDs);


            $thinappSql = "SELECT `show_sub_token_name_on_tracker`,`tracker_voice`,`tracker_media_doctor_id`,`tracker_media_refresh_sec`,`tracker_media_show_patient_name`,`tune_tracker_media` FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $thinappRS = $connection->query($thinappSql);
            $thinappData = mysqli_fetch_assoc($thinappRS);

            if (!empty($thinappData["tracker_new_doctor_id"])) {
                $DocIDArr = json_decode($thinappData["tracker_media_doctor_id"]);

                $doctorIDs = implode(',', $DocIDArr);
            }


            $mediaData = $this->MediaMessage->find('all', array('conditions' => array("thinapp_id" => $thin_app_id, "status" => "ACTIVE"), 'order' => array("sort_order" => "ASC"), 'contain' => false));
            $this->set(compact('thinappID', 'doctorIDs', 'mediaData', 'thinappData'));
        }
    }

    public function load_tracker_opd_media()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);

            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $response = array();

            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array, true);
                $tracker_data = @array_values($tracker_data);

                $speechDataFormated = array();
                $speechData = $this->SpeechMessageToPlay->find("all", array("fields" => array("message"), "conditions" => array("thinapp_id" => $thin_app_id, "status" => "PLAY")));
                $this->SpeechMessageToPlay->updateAll(array("SpeechMessageToPlay.status" => "'UNPLAY'"), array("thinapp_id" => $thin_app_id, "status" => "PLAY"));
                foreach ($speechData as $dataSpeech) {
                    $speechDataFormated[] =  $dataSpeech["SpeechMessageToPlay"]["message"];
                }

                if (!empty($tracker_data)) {
                    $response['status'] = 1;
                    $response['data'] = $tracker_data;
                    $response['speech_data'] = $speechDataFormated;
                } else {
                    $response['speech_data'] = $speechDataFormated;
                }
            } else {
                $response['status'] = 1;
            }

            $break_array = array();
            foreach ($doctor_id_array as $key => $doctor_id) {
                $break_data = Custom::emergency_patient($doctor_id);
                if (!empty($break_data)) {
                    $break_array[$doctor_id] = json_decode($break_data, true);
                }
            }
            $response['break_array'] = $break_array;

            echo json_encode($response, true);
            die;
        }
    }

    public function display_tracker_opd_media_two_column($thinappID)
    {
        $this->layout = false;
        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctorIDs = implode(",", $doctorIDs);


            $thinappSql = "SELECT `tracker_voice`,`tracker_media_doctor_id`,`tracker_media_refresh_sec`,`tracker_media_show_patient_name`,`tune_tracker_media` FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $thinappRS = $connection->query($thinappSql);
            $thinappData = mysqli_fetch_assoc($thinappRS);

            if (!empty($thinappData["tracker_new_doctor_id"])) {
                $DocIDArr = json_decode($thinappData["tracker_media_doctor_id"]);

                $doctorIDs = implode(',', $DocIDArr);
            }


            $mediaData = $this->MediaMessage->find('all', array('conditions' => array("thinapp_id" => $thin_app_id, "status" => "ACTIVE"), 'order' => array("sort_order" => "ASC"), 'contain' => false));
            $this->set(compact('thinappID', 'doctorIDs', 'mediaData', 'thinappData'));
        }
    }

    public function load_tracker_opd_media_two_column()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);

            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $response = array();

            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $hasOnlyPaid = ($thin_app_id == 27) ? false : true;
                $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array, $hasOnlyPaid);
                $tracker_data = @array_values($tracker_data);

                $speechDataFormated = array();
                $speechData = $this->SpeechMessageToPlay->find("all", array("fields" => array("message"), "conditions" => array("thinapp_id" => $thin_app_id, "status" => "PLAY")));
                $this->SpeechMessageToPlay->updateAll(array("SpeechMessageToPlay.status" => "'UNPLAY'"), array("thinapp_id" => $thin_app_id, "status" => "PLAY"));
                foreach ($speechData as $dataSpeech) {
                    $speechDataFormated[] =  $dataSpeech["SpeechMessageToPlay"]["message"];
                }

                if (!empty($tracker_data)) {
                    $response['status'] = 1;
                    $response['data'] = $tracker_data;
                    $response['speech_data'] = $speechDataFormated;
                } else {
                    $response['speech_data'] = $speechDataFormated;
                }
            } else {
                $response['status'] = 1;
            }

            $break_array = array();
            foreach ($doctor_id_array as $key => $doctor_id) {
                $break_data = Custom::emergency_patient($doctor_id);
                if (!empty($break_data)) {
                    $break_array[$doctor_id] = json_decode($break_data, true);
                }
            }
            $response['break_array'] = $break_array;

            echo json_encode($response, true);
            die;
        }
    }



    public function display_tracker_opd_new($thinappID)
    {
        $this->layout = false;

        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctorIDs = implode(",", $doctorIDs);


            $thinappSql = "SELECT `show_sub_token_name_on_tracker`, `tracker_voice`,`tracker_new_doctor_id`,`tracker_new_refresh_sec`,`tracker_new_show_patient_name`,`tune_tracker_new` FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $thinappRS = $connection->query($thinappSql);
            $thinappData1 = mysqli_fetch_assoc($thinappRS);

            if (!empty($thinappData["tracker_new_doctor_id"])) {
                $thinappData = json_decode($thinappData["tracker_new_doctor_id"]);

                $doctorIDs = (isset($thinappData[0])) ? $thinappData[0] : "";
                $doctorIDs .= (isset($thinappData[1])) ? "," . $thinappData[1] : "";
            }


            $this->set(compact('thinappID', 'doctorIDs', 'thinappData1'));
        }
    }

    public function load_tracker_opd_new()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);

            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $response = array();

            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array, true);
                $tracker_data = @array_values($tracker_data);

                $speechDataFormated = array();
                $speechData = $this->SpeechMessageToPlay->find("all", array("fields" => array("message"), "conditions" => array("thinapp_id" => $thin_app_id, "status" => "PLAY")));
                $this->SpeechMessageToPlay->updateAll(array("SpeechMessageToPlay.status" => "'UNPLAY'"), array("thinapp_id" => $thin_app_id, "status" => "PLAY"));
                foreach ($speechData as $dataSpeech) {
                    $speechDataFormated[] =  $dataSpeech["SpeechMessageToPlay"]["message"];
                }

                if (!empty($tracker_data)) {
                    $response['status'] = 1;
                    $response['data'] = $tracker_data;
                    $response['speech_data'] = $speechDataFormated;
                } else {
                    $response['speech_data'] = $speechDataFormated;
                }
            } else {
                $response['status'] = 1;
            }

            $break_array = array();
            foreach ($doctor_id_array as $key => $doctor_id) {
                $break_data = Custom::emergency_patient($doctor_id);
                if (!empty($break_data)) {
                    $break_array[$doctor_id] = json_decode($break_data, true);
                }
            }
            $response['break_array'] = $break_array;

            echo json_encode($response, true);
            die;
        }
    }







    public function display_tracker_opd_multiple($thinappID)
    {
        $this->layout = false;

        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctorIDs = implode(",", $doctorIDs);

            $connection = ConnectionUtil::getConnection();
            $thinappSql = "SELECT `show_sub_token_name_on_tracker`,`tracker_voice`,`tracker_multiple_refresh_sec`,`tracker_multiple_show_patient_name`,`tune_tracker_multiple` FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";

            $thinappRS = $connection->query($thinappSql);
            $thinappData = mysqli_fetch_assoc($thinappRS);



            $doctor_list = Custom::get_all_doctor_list($thin_app_id);

            $categoryList = "SELECT `appointment_categories`.`id`,`appointment_categories`.`name`,GROUP_CONCAT(`appointment_staffs`.`id`) AS `doctor_ids` FROM `appointment_categories` LEFT JOIN `appointment_staffs` ON (`appointment_staffs`.`appointment_category_id` = `appointment_categories`.`id`) WHERE `appointment_staffs`.`staff_type` = 'DOCTOR' AND `appointment_staffs`.`status` = 'ACTIVE' AND `appointment_categories`.`thinapp_id` = '" . $thin_app_id . "' GROUP BY `appointment_categories`.`id` ORDER BY `appointment_categories`.`name` ASC";

            $categoryList = $connection->query($categoryList);
            $categoryData = mysqli_fetch_all($categoryList, MYSQLI_ASSOC);




            $ivrMobile = '';
            $sql = "SELECT `ivr_number` FROM `doctors_ivr` WHERE `doctor_status` = 'Active' and `thinapp_id` = '" . $thin_app_id . "' LIMIT 1";
            $RS = $connection->query($sql);
            $mobileList = mysqli_fetch_assoc($RS);
            if (!empty($mobileList) && $thin_app_id != 704 && $thin_app_id != 777) {
                //$ivrMobile = $mobileList['ivr_number'];
            }

            $this->set(compact('ivrMobile', 'thinappID', 'doctorIDs', 'doctor_list', 'categoryData', 'thinappData'));
        }
    }

    public function load_tracker_opd_multiple()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);

            $doctor_id_array = explode(',', trim($this->request->data['doctor_id_string']));
            //pr($doctor_id_array); die;
            $response = array();

            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array, true);
                $tracker_data = @array_values($tracker_data);

                $speechDataFormated = array();
                $speechData = $this->SpeechMessageToPlay->find("all", array("fields" => array("message"), "conditions" => array("thinapp_id" => $thin_app_id, "status" => "PLAY")));
                $this->SpeechMessageToPlay->updateAll(array("SpeechMessageToPlay.status" => "'UNPLAY'"), array("thinapp_id" => $thin_app_id, "status" => "PLAY"));
                foreach ($speechData as $dataSpeech) {
                    $speechDataFormated[] =  $dataSpeech["SpeechMessageToPlay"]["message"];
                }

                if (!empty($tracker_data)) {
                    $response['status'] = 1;
                    $response['data'] = $tracker_data;
                    $response['speech_data'] = $speechDataFormated;
                } else {
                    $response['speech_data'] = $speechDataFormated;
                }
            } else {
                $response['status'] = 1;
            }

            $break_array = array();
            foreach ($doctor_id_array as $key => $doctor_id) {
                $break_data = Custom::emergency_patient($doctor_id);
                if (!empty($break_data)) {
                    $break_array[$doctor_id] = json_decode($break_data, true);
                }
            }
            $response['break_array'] = $break_array;

            echo json_encode($response, true);
            die;
        }
    }


    public function tracker_update_tune()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $val = $this->request->data['val'];
            $field = $this->request->data['field'];


            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET " . $field . " = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $val, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function update_announcement_message()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $val = $this->request->data['ann_message'];


            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];

            $sql = "UPDATE thinapps SET tracker_voice = ?, modified =? where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $val, $created, $thin_app_id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "Update Successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }




    public function get_tracker_doctor_id()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $response = array();
            $mobile = $this->request->data['mobile'];
            $thinapp_id = $this->request->data['thinapp_id'];
            $connection = ConnectionUtil::getConnection();


            $sql = "SELECT `id` FROM `appointment_staffs` WHERE `mobile` = '" . $mobile . "' AND `thinapp_id` = '" . $thinapp_id . "' AND `status` = 'ACTIVE' AND `staff_type` = 'DOCTOR' LIMIT 1";

            $RS = $connection->query($sql);

            if ($RS->num_rows) {

                $data = mysqli_fetch_assoc($RS);
                $response['status'] = 1;
                $response['doctor_id'] = base64_encode($data['id']);
            } else {
                $response['status'] = 0;
            }

            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }



    public function track_doctor_appointment($doctorID = null)
    {

        $this->layout = false;
        $showTracker = false;
        $dataToShow = array();
        if ($doctorID != null) {
            $doctorID = base64_decode($doctorID);
            $doctor_id_array = array($doctorID);

            $sql = "SELECT `thinapp_id` FROM `appointment_staffs` WHERE `id` = '" . $doctorID . "' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $RS = $connection->query($sql);
            if ($RS->num_rows) {
                $data = mysqli_fetch_assoc($RS);
                $thinappID = $data['thinapp_id'];
                $otherTokenData = Custom::getDoctorWebTrackerData($doctor_id_array, true, $thinappID);
                $currentToken = @$otherTokenData[0];


                if (isset($currentToken['next_appointment_datetime'])) {

                    $dataToShow['currentToken'] = $currentToken;
                    $currentTime = date('Y-m-d H:i:s');
                    $date = date('Y-m-d');

                    $nextAppointmentTime = $currentToken['next_appointment_datetime'];




                    /*$sql = "SELECT `appointment_datetime` FROM `appointment_customer_staff_services` WHERE `appointment_datetime` > '".$currentTime."' AND `appointment_customer_staff_services`.`appointment_staff_id` = '".$doctorID."' AND DATE(`appointment_datetime`) = '".$date."' AND `status` IN ('NEW','CONFIRM','RESCHEDULE') AND `payment_status` = 'SUCCESS' AND `skip_tracker` = 'NO' AND `emergency_appointment` = 'NO' AND `patient_queue_type` = 'NONE' ORDER BY `appointment_datetime` ASC LIMIT 1";
                    $RS = $connection->query($sql);
                    if($RS->num_rows) {
                        $dataAppointment = mysqli_fetch_assoc($RS);
                        $nextAppointmentTime = $dataAppointment['appointment_datetime'];
                    } */


                    $sql = "SELECT `appointment_services`.`service_slot_duration`,`appointment_customer_staff_services`.`appointment_datetime` FROM `appointment_customer_staff_services` LEFT JOIN `appointment_services` ON (`appointment_customer_staff_services`.`appointment_service_id` = `appointment_services`.`id`) WHERE `appointment_customer_staff_services`.`appointment_staff_id` = '" . $doctorID . "' AND DATE(`appointment_customer_staff_services`.`appointment_datetime`) = '" . $date . "' AND `appointment_customer_staff_services`.`status` IN ('NEW','CONFIRM','RESCHEDULE') AND `appointment_customer_staff_services`.`payment_status` = 'SUCCESS' AND `appointment_customer_staff_services`.`skip_tracker` = 'NO' AND `appointment_customer_staff_services`.`emergency_appointment` = 'NO' AND `appointment_customer_staff_services`.`patient_queue_type` = 'NONE' ORDER BY `appointment_customer_staff_services`.`appointment_datetime` ASC LIMIT 1";
                    $RS = $connection->query($sql);
                    if ($RS->num_rows) {
                        $dataAppointment = mysqli_fetch_assoc($RS);

                        $currentTimeStamp = strtotime(date('Y-m-d H:i:00'));
                        $addedTimeStamp = strtotime(date('Y-m-d H:i:s', strtotime('+' . $addTime, strtotime($dataAppointment['appointment_datetime']))));

                        if ($addedTimeStamp < $currentTimeStamp) {
                            $addTime = $dataAppointment['service_slot_duration'];
                            $dataAppointment['appointment_datetime'] = date('Y-m-d H:i:s', strtotime('+' . $addTime, strtotime($dataAppointment['appointment_datetime'])));
                        }



                        $nextAppointmentTime = $currentAppointmentTime = $dataAppointment['appointment_datetime'];
                    }






                    $datetime1 = new DateTime($currentTime);
                    $datetime2 = new DateTime($nextAppointmentTime);
                    $interval = $datetime1->diff($datetime2);
                    $timeDeff = $interval->format('%h') . " Hours " . $interval->format('%i') . " Minutes";

                    $isLate = false;
                    if (strtotime($currentTime) > strtotime($nextAppointmentTime)) {
                        $isLate = true;
                    }
                    $dataToShow['isLate'] = $isLate;
                    $dataToShow['timeDeff'] = $timeDeff;
                }
            }
        }

        $this->set(compact('doctorID', 'dataToShow'));
    }



    public function close_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $result = array('status' => 0, 'message' => 'Something went wrong!');
            $appointment_id = base64_decode($this->request->data['id']);
            $post = array();

            $sql = "SELECT `appointment_customer_staff_services`.`thinapp_id` AS `thinapp_id`,`appointment_staffs`.`id`,`appointment_staffs`.`user_id` AS `user_id` FROM `appointment_customer_staff_services` LEFT JOIN `appointment_staffs` ON (`appointment_customer_staff_services`.`appointment_staff_id` = `appointment_staffs`.`id`) WHERE `appointment_customer_staff_services`.`id` = '" . $appointment_id . "' LIMIT 1";



            $connection = ConnectionUtil::getConnection();
            $RS = $connection->query($sql);


            if ($RS->num_rows) {
                $appointmentData = mysqli_fetch_assoc($RS);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = ($appointmentData['user_id'] > 0) ? $appointmentData['user_id'] : $appointmentData['id'];
                $post['thin_app_id'] = $appointmentData['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
                if ($result['status'] == 1) {
                    $notification_array = $result['notification_array'];
                    unset($result['notification_array']);
                    Custom::sendResponse($result);
                    Custom::update_tracker_time_difference($appointment_id);
                    Custom::close_appointment_notification($notification_array);
                } else {
                    Custom::sendResponse($result);
                }
            } else {
                Custom::sendResponse($result);
            }
        } else {
            exit();
        }
    }


    public function pause_tracker_sms()
    {
        if ($this->request->is('ajax')) {
            $doctor_id = base64_decode($this->request->data['di']);
            $sms_list_file_name = "custom_sms_list_" . $doctor_id;
            $patient_data = json_decode(WebservicesFunction::readJson($sms_list_file_name, "tracker"), true);
            $break_file_name = "custom_active_sms_" . $doctor_id;
            $break_data = WebservicesFunction::readJson($break_file_name, "tracker");
            if (!empty($break_data)) {
                $break_data = json_decode($break_data, true);
            }

            $this->set(compact('patient_data', 'break_data'));
        }
    }


    public function save_pause_sms()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $sms_data = json_decode($this->request->data['sms_data'], true);
            $doctor_id = base64_decode($this->request->data['di']);
            if (!empty($sms_data)) {
                $doctor_data = Custom::get_doctor_by_id($doctor_id);
                foreach ($sms_data as $key => $value) {
                    if ($value['checked'] == true) {
                        $tracker_data['flag'] = "CUSTOM";
                        $tracker_data['patient_name'] = $value['msg'];
                        $tracker_data['doctor_name'] = $doctor_data['name'];
                        $tracker_data['room_number'] = $doctor_data['room_number'];
                        $break_file_name = "custom_active_sms_" . $doctor_id;
                        WebservicesFunction::createJson($break_file_name, json_encode($tracker_data), 'CREATE', 'tracker');
                    }
                }
                $sms_list_file_name = "custom_sms_list_" . $doctor_id;
                WebservicesFunction::createJson($sms_list_file_name, json_encode($sms_data), 'CREATE', 'tracker');
            } else {
                $delete_file[] = "custom_active_sms_" . $doctor_id;
                $delete_file[] = "custom_sms_list_" . $doctor_id;
                WebservicesFunction::deleteJson($delete_file, 'tracker');
            }


            return true;
        }
    }

    public function delete_pause_sms()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $doctor_id = base64_decode($this->request->data['di']);
            $all_sms = $this->request->data['all_sms'];
            $delete_file[] = "custom_active_sms_" . $doctor_id;
            if ($all_sms == "YES") {
                $delete_file[] = "custom_sms_list_" . $doctor_id;
            }
            WebservicesFunction::deleteJson($delete_file, 'tracker');
            return true;
        }
    }

    public function block_slot()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $doctor_id = ($this->request->data['di']);
            $address_id = ($this->request->data['ai']);
            $service_id = ($this->request->data['si']);
            $date = ($this->request->data['d']);
            //$message = ($this->request->data['m']);
            $action_from = ($this->request->data['af']);
            $post = array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] = $login['id'];
            $post['thin_app_id'] = $thin_app_id = $login['thinapp_id'];
            $post['mobile'] = $login['mobile'];
            $post['action_from'] = 'WEB';
            $post['message'] = ($action_from == "LEAVE") ? "Doctor is on leave today" : "Doctor has emergency today";
            $post['role_id'] = $login['role_id'];
            $post['slot_string'] = '';
            $post['block_by'] = "DATE";
            $post['address_id'] = $address_id;
            $post['doctor_id'] = $doctor_id;
            $post['service_id'] = $service_id;
            $post['date'] = $date;
            $appointment_user_role = Custom::hospital_get_user_role($login['mobile'], $thin_app_id, $login['role_id']);
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                $doctor_slots = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['appointment_setting_type'], $date, false, true, $appointment_user_role);
                if ($doctor_slots) {
                    $doctor_slots = array_column($doctor_slots, 'slot');
                    $post['slot_string'] = implode(',', $doctor_slots);
                }
            } else {
                $doctor_service = Custom::get_doctor_service_data($doctor_id);
                if ($doctor_service) {
                    $doctor_slots = Custom::load_doctor_slot_by_address($date, $doctor_id, $doctor_service['service_slot_duration'], $thin_app_id, $address_id, false, $appointment_user_role, true, true);
                    if ($doctor_slots) {
                        $doctor_slots = array_column($doctor_slots, 'slot');
                        $post['slot_string'] = implode(',', $doctor_slots);
                    }
                }
            }


            $response =  json_decode(WebservicesFunction::blocked_appointment_slot($post), true);
            $cancel_appointment = @$response['cancel_appointment'];
            unset($response['cancel_appointment']);
            Custom::sendResponse($response);
            if ($response['status'] == 1) {
                Custom::send_process_to_background();
                if (!empty($cancel_appointment)) {
                    foreach ($cancel_appointment as $key => $app_data) {
                        $current_timestamp = strtotime(date('Y-m-d H:i'));
                        $app_timestamp = strtotime(date('Y-m-d H:i', strtotime($app_data['appointment_datetime'])));
                        if ($app_timestamp > $current_timestamp) {
                            $post = array();
                            $post['app_key'] = MBROADCAST_APP_NAME;
                            $post['user_id'] = $login['id'];
                            $post['thin_app_id'] = $thin_app_id;
                            $post['appointment_id'] = $app_data['id'];
                            $post['message'] = $message;
                            $post['cancel_by'] = "DOCTOR";
                            $res = json_decode(WebservicesFunction::cancel_appointment($post, false, false, "BLOCKED_SLOT"), true);
                        }
                    }
                }
            }
        } else {
            exit();
        }
    }

    public function block_date_window()
    {
        if ($this->request->is('ajax')) {
            $doctor_id = base64_decode($this->request->data['di']);
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $blocked_dates = Custom::get_doctor_upcoming_blocked_dates($doctor_id, $address_id, $service_id);
            $this->set(compact('blocked_dates'));
        }
    }

    public function doctor_on_leave()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $doctor_id = base64_decode($this->request->data['di']);
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $blocked_date_array = $this->request->data['d'];
            $cancel_appointment = $result = array();
            $blocked_date_array = !empty($blocked_date_array) ? explode(",", $this->request->data['d']) : '';
            $already_blocked_dates = Custom::get_doctor_upcoming_blocked_dates($doctor_id, $address_id, $service_id);
            if (!empty($blocked_date_array)) {
                foreach ($blocked_date_array as $key => $date) {
                    $post = array();
                    $login = $this->Session->read('Auth.User.User');
                    $post['app_key'] = APP_KEY;
                    $post['user_id'] = $login['id'];
                    $post['thin_app_id'] = $thin_app_id = $login['thinapp_id'];
                    $post['mobile'] = $login['mobile'];
                    $post['action_from'] = 'WEB';
                    $post['message'] = $message = "Doctor is on leave today";
                    $post['role_id'] = $login['role_id'];
                    $post['slot_string'] = '';
                    $post['block_by'] = "DATE";
                    $post['address_id'] = $address_id;
                    $post['doctor_id'] = $doctor_id;
                    $post['service_id'] = $service_id;
                    $post['date'] = $date;
                    $appointment_user_role = Custom::hospital_get_user_role($login['mobile'], $thin_app_id, $login['role_id']);
                    if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                        $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                        $doctor_slots = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['appointment_setting_type'], $date, false, true, $appointment_user_role);
                        if ($doctor_slots) {
                            $doctor_slots = array_column($doctor_slots, 'slot');
                            $post['slot_string'] = implode(',', $doctor_slots);
                        }
                    } else {
                        $doctor_service = Custom::get_doctor_service_data($doctor_id);
                        if ($doctor_service) {
                            $doctor_slots = Custom::load_doctor_slot_by_address($date, $doctor_id, $doctor_service['service_slot_duration'], $thin_app_id, $address_id, false, $appointment_user_role, true, true);
                            if ($doctor_slots) {
                                $doctor_slots = array_column($doctor_slots, 'slot');
                                $post['slot_string'] = implode(',', $doctor_slots);
                            }
                        }
                    }
                    $response =  json_decode(WebservicesFunction::blocked_appointment_slot($post), true);
                    if ($response['status'] == 1) {
                        if (!empty($response['cancel_appointment'])) {
                            $cancel_appointment[] = $response['cancel_appointment'];
                        }
                        $result[] = true;
                    } else {
                        $result[] = false;
                    }
                }
            }

            $connection = ConnectionUtil::getConnection();
            if (!empty($already_blocked_dates)) {
                $connection->autocommit(false);
                foreach ($already_blocked_dates as $key => $date_value) {
                    if (!in_array($date_value['blocked_date'], $blocked_date_array)) {
                        $sql = "delete from  appointment_bloked_slots where id = ?";
                        $stmt_df = $connection->prepare($sql);
                        $stmt_df->bind_param('s', $date_value['id']);
                        $result[] = $stmt_df->execute();
                    }
                }
            }


            if (!empty($result) && !in_array(false, $result)) {
                $connection->commit();
                $response['status'] = 1;
                $response['message'] = "Setting save successfully";
                Custom::sendResponse($response);
                Custom::send_process_to_background();
                if (!empty($cancel_appointment)) {
                    foreach ($cancel_appointment as $key1 => $cancel) {
                        foreach ($cancel as $key => $app_data) {
                            $current_timestamp = strtotime(date('Y-m-d H:i'));
                            $app_timestamp = strtotime(date('Y-m-d H:i', strtotime($app_data['appointment_datetime'])));
                            if ($app_timestamp > $current_timestamp) {
                                $post = array();
                                $post['app_key'] = MBROADCAST_APP_NAME;
                                $post['user_id'] = $login['id'];
                                $post['thin_app_id'] = $thin_app_id;
                                $post['appointment_id'] = $app_data['id'];
                                $post['message'] = $message;
                                $post['cancel_by'] = "DOCTOR";
                                $res = json_decode(WebservicesFunction::cancel_appointment($post, false, false, "BLOCKED_SLOT"), true);
                            }
                        }
                    }
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Unable to save";
            }
        } else {
            exit();
        }
    }

    public function app_dialog($thinapp_id)
    {
        $this->layout = false;
        $file_name = "app_dialog_$thinapp_id";
        if (!$content = json_decode(WebservicesFunction::readJson($file_name, "dialog_message"), true)) {
            $content = "";
        }
        $this->set(compact('content'));
    }

    public function addSurveyData()
    {
        $this->layout = false;
        $appointment_id = base64_decode($this->request->data['ai']);
        $response = ($this->request->data['res']);
        $created = Custom::created();
        $query = "INSERT INTO corona_survey (appointment_id,response, created) values(?,?,?)";
        $connection = ConnectionUtil::getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sss', $appointment_id, $response, $created);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function corona_result($appointment_id)
    {
        $this->layout = false;

        $query = "select acss.thinapp_id, cs.created, cs.response, acss.consent_id  from corona_survey as cs left join appointment_customer_staff_services as acss on acss.id = cs.appointment_id where cs.appointment_id = $appointment_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $list_obj = $connection->query($query);
        $content = array();
        if ($list_obj->num_rows) {
            $response = mysqli_fetch_assoc($list_obj);
            $created = $response['created'];
            $thin_app_id = $response['thinapp_id'];
            $content = json_decode($response['response'], true);
            $consent_link = '';
            if (!empty($response['consent_id'])) {
                $consent_link  = (FOLDER_PATH . "consent/" . base64_encode($response['consent_id'] . "##" . $thin_app_id)) . "/" . base64_encode('DOCTOR');
            }
        }
        $this->set(compact('content', 'created', 'consent_link'));
    }

    public function pq($thin_app_id)
    {
        $thin_app_id = base64_decode($thin_app_id);
        $this->layout = false;
        if (!empty($thin_app_id)) {
            $app_data = Custom::getThinAppData($thin_app_id);
            $refresh_list_seconds = !empty($app_data['refresh_tracker_list_second']) ? $app_data['refresh_tracker_list_second'] * 1000 : 30000;
            if (!empty($app_data)) {
                $this->set(compact('refresh_list_seconds', 'app_data', 'thin_app_id'));
            }
        }
    }

    public function load_pq_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $tracker_data = array();
            if (!empty($thin_app_id)) {
                $thin_app_id = base64_decode($thin_app_id);
                $query = "SELECT acss.appointment_patient_name, staff.name AS doctor_name, psn.payment_sequence_number FROM appointment_customer_staff_services AS acss JOIN payment_sequence_number AS psn ON psn.appointment_id= acss.id JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id WHERE acss.status IN('NEW','CONFIRM','RESCHEDULE') AND psn.payment_queue_skipped = 'NO' AND acss.payment_status = 'PENDING' AND acss.thinapp_id = 134 AND DATE(acss.appointment_datetime) = DATE(NOW()) ORDER BY psn.payment_sequence_number asc limit 5";
                $connection = ConnectionUtil::getConnection();
                $list = $connection->query($query);
                if ($list->num_rows) {
                    $tracker_data = mysqli_fetch_all($list, MYSQLI_ASSOC);
                }
            }
            $this->set(compact('tracker_data'));
        } else {
            die('Ok');
        }
    }

    public function counter_tracker($thin_app_id)
    {
        $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
        $thin_app_data = Custom::get_thinapp_data($thin_app_id);
        $this->set(compact('thin_app_id', 'thin_app_data'));
    }

    public function load_counter_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $tracker_data = array();
            if (!empty($thin_app_id)) {
                $thin_app_id = base64_decode($thin_app_id);
                $tracker_data = Custom::load_counter_tracker_data($thin_app_id);
            }
            $this->set(compact('tracker_data'));
        } else {
            die('Ok');
        }
    }

    public function get_previous_token_list()
    {
        if ($this->request->is('ajax')) {
            $token = ($this->request->data['q']);
            $thin_app_id = base64_decode($this->request->data['t']);
            $doctor_id = base64_decode($this->request->data['di']);
            $tracker_data = array();
            if (!empty($thin_app_id)) {
                $query = "SELECT dc.booking_type, dc.counter, acss.queue_number, acss.appointment_staff_id AS doctor_id  FROM appointment_customer_staff_services AS acss JOIN doctor_counters AS dc on  dc.counter = acss.referred_by WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' AND acss.patient_queue_type ='BILLING_CHECKIN' and acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id ORDER BY acss.send_to_lab_datetime ASC";
                $connection = ConnectionUtil::getConnection();
                $list = $connection->query($query);
                $token_list = array();
                if ($list->num_rows) {
                    $list = mysqli_fetch_all($list, MYSQLI_ASSOC);
                    foreach ($list as $key => $value) {
                        $new_token = Custom::get_ck_birla_token($value['doctor_id'], $value['queue_number']);
                        $token_list[] = $new_token;
                        if ($token == $value['queue_number']) {
                            break;
                        }
                    }
                }
            }
            if (!empty($token_list)) {
                $response['status'] = 1;
                $response['list'] = $token_list;
            } else {
                $response['status'] = 0;
            }
            echo json_encode($response);
            die;
        } else {
            die('Ok');
        }
    }

    public function external_tracker($thinappID)
    {
        $this->layout = false;

        if (!empty($thinappID)) {
            $thin_app_id = base64_decode($thinappID);
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctorIDs = implode(",", $doctorIDs);

            $connection = ConnectionUtil::getConnection();
            $thinappSql = "SELECT `show_sub_token_name_on_tracker`,`tracker_voice`,`tracker_multiple_refresh_sec`,`tracker_multiple_show_patient_name`,`tune_tracker_multiple` FROM `thinapps` WHERE `id` = '" . $thin_app_id . "' LIMIT 1";

            $thinappRS = $connection->query($thinappSql);
            $thinappData = mysqli_fetch_assoc($thinappRS);



            $doctor_list = Custom::get_all_doctor_list($thin_app_id);

            $categoryList = "SELECT `appointment_categories`.`id`,`appointment_categories`.`name`,GROUP_CONCAT(`appointment_staffs`.`id`) AS `doctor_ids` FROM `appointment_categories` LEFT JOIN `appointment_staffs` ON (`appointment_staffs`.`appointment_category_id` = `appointment_categories`.`id`) WHERE `appointment_staffs`.`staff_type` = 'DOCTOR' AND `appointment_staffs`.`status` = 'ACTIVE' AND `appointment_categories`.`thinapp_id` = '" . $thin_app_id . "' GROUP BY `appointment_categories`.`id` ORDER BY `appointment_categories`.`name` ASC";

            $categoryList = $connection->query($categoryList);
            $categoryData = mysqli_fetch_all($categoryList, MYSQLI_ASSOC);




            $ivrMobile = '';
            $sql = "SELECT `ivr_number` FROM `doctors_ivr` WHERE `doctor_status` = 'Active' and `thinapp_id` = '" . $thin_app_id . "' LIMIT 1";
            $RS = $connection->query($sql);
            $mobileList = mysqli_fetch_assoc($RS);
            if (!empty($mobileList) && $thin_app_id != 704 && $thin_app_id != 777) {
                //$ivrMobile = $mobileList['ivr_number'];
            }

            $this->set(compact('ivrMobile', 'thinappID', 'doctorIDs', 'doctor_list', 'categoryData', 'thinappData'));
        }
    }

    public function test_printer()
    {
        $this->layout = false;
    }

    public function fortis()
    {
        $this->layout = false;
        $searchData = $this->request->query;
        $thin_app_id = ($searchData['t']);
        $patient_mobile = isset($searchData['pm']) ? base64_decode($searchData['pm']) : "";
        $ShowHeader = isset($searchData['sh']) ? ($searchData['sh']) : "yes";
        $patientToken = array();
        if (!empty($thin_app_id)) {
            $thin_app_id = base64_decode($thin_app_id);
            $thinapp_data = Custom::get_thinapp_data($thin_app_id);
            $tmp_list = Custom::get_all_app_doctor_id_name($thin_app_id);
            $doctor_array = $patientToken = array();

            if (!empty($patient_mobile)) {
                $date = date('Y-m-d');
                $query = "SELECT staff.name_label, acss.id as appointment_id, staff.show_token_into_digital_tud, staff.id as doctor_id, staff.department_category_id, acss.notes AS remark, acss.appointment_datetime, acss.queue_number,acss.sub_token, acss.appointment_patient_name AS patient_name, staff.name AS doctor_name  FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id =acss.appointment_staff_id JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id WHERE ac.thinapp_id = $thin_app_id AND ac.mobile = '$patient_mobile' AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = '$date'  ORDER BY acss.id DESC LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $patientToken =  mysqli_fetch_assoc($service_message_list);
                    foreach ($tmp_list as $doc_id => $doc_name) {
                        $doctor_data = Custom::get_doctor_by_id($doc_id, $thin_app_id);
                        if (empty($doctor_data['show_token_into_digital_tud']) || $doctor_data['show_token_into_digital_tud'] == $patientToken['doctor_id']) {
                            if (Custom::isCustomizedAppId($thin_app_id)) {
                                if ($doctor_data['department_category_id'] == '32') {
                                    $doctor_ids[] = $doc_id;
                                } else {
                                    $file_name = "fortis_tracker_" . $doc_id . "_" . date('Y-m-d');
                                    $tracker_data = json_decode(WebservicesFunction::readJson($file_name, "fortis"), true);
                                    if (!empty($tracker_data['current']) && is_numeric($tracker_data['current']['token_number'])) {
                                        $doctor_ids[] = $doc_id;
                                    }
                                }
                            } else {
                                $doctor_ids[] = $doc_id;
                            }
                        }
                    }
                }
            } else {
                foreach ($tmp_list as $doc_id => $doc_name) {
                    $doctor_data = Custom::get_doctor_by_id($doc_id, $thin_app_id);
                    if (Custom::isCustomizedAppId($thin_app_id)) {
                        if ($doctor_data['counter_booking_type'] == 'PAYMENT') {
                            $doctor_ids[] = $doc_id;
                        }
                    } else {
                        $doctor_ids[] = $doc_id;
                    }
                }
            }

            $doctor_ids = implode(",", $doctor_ids);

            $slide_list = array();
            if ($thin_app_id != EHCC_APP_ID) {
                $query = "select dc.category_name, staff.name as doctor_name, staff.sub_title, staff.description,staff.profile_photo from appointment_staffs as staff left join department_categories as dc  on dc.id = staff.department_category_id where staff.staff_type = 'DOCTOR' AND staff.`status`='ACTIVE' and staff.department_category_id != 32 and staff.thinapp_id = $thin_app_id  AND staff.description !='' order by CAST(staff.other_check_in_queue_after_number AS DECIMAL) asc ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $slide_list =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
                }
            }
        }
        $ehcc_app_id = EHCC_APP_ID;
        $is_custom_app = (Custom::isCustomizedAppId($thin_app_id)) ? 'YES' : 'NO';

        $billingTokenString = "";
        if (EHCC_APP_ID == $thin_app_id) {
            $billingTokenString  = Custom::getBillingCounterTokenString($thin_app_id);
        }

        $mediaData = $this->MediaMessage->find('all', array('conditions' => array("thinapp_id" => $thin_app_id, "status" => "ACTIVE"), 'order' => array("sort_order" => "ASC"), 'contain' => false));

        $this->set(compact('mediaData', 'billingTokenString', 'is_custom_app', 'ehcc_app_id', 'tmp_list', 'slide_list', 'doctor_ids', 'thin_app_id', 'thinapp_data', 'ShowHeader', 'patientToken'));
    }

    public function load_fortis_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $logo = ($this->request->data['logo']);
            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $result_array = array();
                $this->layout = 'ajax';
                foreach ($doctor_id_array as $key => $doctor_id) {
                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $result_array[$doctor_id]['doctor_name'] = $doctor_data['name'];
                    $result_array[$doctor_id]['profile_photo'] = !empty($doctor_data['profile_photo']) ? $doctor_data['profile_photo'] : $logo;
                    $result_array[$doctor_id]['data'] = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);

                    $doctor_name = $doctor_data['name'];
                    $current_token = $result_array[$doctor_id]['data']["current"]['token_number'];
                    $sub_token = $result_array[$doctor_id]['data']["current"]['sub_token'];
                    if ($sub_token == "NO" && Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')) {
                        /* create and get token voice file name */
                        $fileName = Custom::getVoiceFileName($current_token, $doctor_name, $thin_app_id);
                        if (!empty($fileName)) {
                            $voice_array[$doctor_id] = $fileName;
                        }
                        $folder_path = LOCAL_PATH . "app/webroot/cache/tracker_play_again_voice/$doctor_id";
                        $files = glob($folder_path . '/*');
                        if (!empty($files)) {
                            foreach ($files as $file) {
                                if (is_file($file)) {
                                    // $againPlayVoice[] =  str_replace('"',"",file_get_contents($file));
                                    unlink($file);
                                }
                            }
                        }
                    }
                }
                if (!empty($againPlayVoice)) {
                    $againPlayVoice = implode(",", $againPlayVoice);
                }




                $this->set(compact('againPlayVoice', 'thin_app_id', 'result_array', "voice_array"));
                $this->render('load_fortis_tracker', 'ajax');
            }
        }
    }


    public function live()
    {
        $this->layout = false;
        $searchData = $this->request->query;
        $thin_app_id = ($searchData['t']);
        $patient_mobile = isset($searchData['pm']) ? base64_decode($searchData['pm']) : "";
        $ShowHeader = isset($searchData['sh']) ? ($searchData['sh']) : "yes";
        $patientToken = array();
        if (!empty($thin_app_id)) {
            $thin_app_id = base64_decode($thin_app_id);
            $thinapp_data = Custom::get_thinapp_data($thin_app_id);
            $tmp_list = Custom::get_all_app_doctor_id_name($thin_app_id);
            $doctor_array = $patientToken = array();

            if (!empty($patient_mobile)) {
                $date = date('Y-m-d');
                $query = "SELECT staff.id as doctor_id, staff.department_category_id, acss.notes AS remark, acss.appointment_datetime, acss.queue_number,acss.sub_token, acss.appointment_patient_name AS patient_name, staff.name AS doctor_name  FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id =acss.appointment_staff_id JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id WHERE ac.thinapp_id = $thin_app_id AND ac.mobile = '$patient_mobile' AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = '$date'  ORDER BY acss.id DESC LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $patientToken =  mysqli_fetch_assoc($service_message_list);
                }
            }


            foreach ($tmp_list as $doc_id => $doc_name) {
                if (empty($patientToken) || $patientToken['doctor_id'] == $doc_id) {
                    $doctor_data = Custom::get_doctor_by_id($doc_id, $thin_app_id);
                    if (Custom::isCustomizedAppId($thin_app_id)) {
                        if ($doctor_data['counter_booking_type'] == 'PAYMENT') {
                            $doctor_ids[] = $doc_id;
                        }
                    } else {
                        $doctor_ids[] = $doc_id;
                    }
                }
            }
            $doctor_ids = implode(",", $doctor_ids);


            $slide_list = array();
            if ($thin_app_id != EHCC_APP_ID) {
                $query = "select dc.category_name, staff.name as doctor_name, staff.sub_title, staff.description,staff.profile_photo from appointment_staffs as staff left join department_categories as dc  on dc.id = staff.department_category_id where staff.staff_type = 'DOCTOR' AND staff.`status`='ACTIVE' and staff.department_category_id != 32 and staff.thinapp_id = $thin_app_id AND staff.description !='' order by CAST(staff.other_check_in_queue_after_number AS DECIMAL) asc";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $slide_list =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
                }
            }
        }

        $ehcc_app_id = EHCC_APP_ID;
        $is_custom_app = (Custom::isCustomizedAppId($thin_app_id)) ? 'YES' : 'NO';
        $billingTokenString = "";
        if (EHCC_APP_ID == $thin_app_id) {
            $billingTokenString  = Custom::getBillingCounterTokenString($thin_app_id);
        }

        $this->set(compact('billingTokenString', 'is_custom_app', 'ehcc_app_id', 'tmp_list', 'slide_list', 'doctor_ids', 'thin_app_id', 'thinapp_data', 'ShowHeader', 'patientToken'));
    }

    public function load_live_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $logo = ($this->request->data['logo']);
            $doctor_id_array = array();
            $againPlayVoice = array();
            if (!empty($thin_app_id)) {
                $thin_app_id = base64_decode($thin_app_id);
                $result_array = $voice_array = array();
                $this->layout = 'ajax';
                $connection = ConnectionUtil::getConnection();
                $query = "select  staff.room_number,staff.id,staff.name, IF(dar.id IS NOT NULL,'Open','Closed') as counter_status from appointment_staffs as staff left join doctor_associates_receptionists as dar on dar.doctor_id = staff.id where staff.`status`='ACTIVE' and staff.staff_type='DOCTOR' and staff.thinapp_id = $thin_app_id   and staff.id != staff.show_token_into_digital_tud group by staff.id";
                $data = $connection->query($query);
                $list = $doctor_id_array = array();
                if ($data->num_rows) {
                    foreach (mysqli_fetch_all($data, MYSQLI_ASSOC) as $key => $val) {
                        $doctor_id_array[$val['id']] = $val['counter_status'];
                    }
                }
                foreach ($doctor_id_array as $doctor_id => $counterStatus) {
                    $service_name = "";
                    $room_number = "";
                    $service_id = 0;
                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $allow_add = true;
                    if (!empty($doctor_data["show_token_into_digital_tud"])) {
                        $doctor_service_data = Custom::get_doctor_by_id($doctor_data["show_token_into_digital_tud"], $thin_app_id);
                        if (!empty($doctor_service_data["show_token_into_digital_tud"])) {
                            $service_name = $doctor_service_data['name'];
                            $room_number = $doctor_service_data['room_number'];
                            $service_id = $doctor_service_data['id'];
                            $id_array = array('2142', '2147', '2148', '2149', '2150', '2151', '2152', '2153', '2154');
                            $allow_add = in_array($doctor_data["show_token_into_digital_tud"], $id_array) ? true : false;
                        }
                    }


                    if ($allow_add) {
                        $result_array[$service_name][$doctor_id]['service_id'] = $service_id;
                        $result_array[$service_name]['room_number'] = $room_number;
                        $result_array[$service_name][$doctor_id]['doctor_name'] = $doctor_data['name'];
                        $result_array[$service_name][$doctor_id]['profile_photo'] = !empty($doctor_data['profile_photo']) ? $doctor_data['profile_photo'] : $logo;
                        $result_array[$service_name][$doctor_id]['data'] = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
                        $doctor_name = $doctor_data['name'];
                        if ($counterStatus == 'Closed') {
                            //$result_array[$service_name][$doctor_id]['data']["current"]['token_number'] ='Closed';
                        }
                        $current_token = $result_array[$service_name][$doctor_id]['data']["current"]['token_number'];
                        $sub_token = $result_array[$service_name][$doctor_id]['data']["current"]['sub_token'];
                        if ($sub_token == "NO" && Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')) {
                            /* create and get token voice file name */
                            $fileName = Custom::getVoiceFileName($current_token, $doctor_name, $thin_app_id);
                            if (!empty($fileName)) {
                                $voice_array[$doctor_id] = $fileName;
                            }
                        }
                    }
                }
                if (!empty($againPlayVoice)) {
                    $againPlayVoice = implode(",", $againPlayVoice);
                }
                $this->set(compact('againPlayVoice', 'thin_app_id', 'result_array', "voice_array"));
                $this->render('load_live_tracker', 'ajax');
            }
        }
    }


    public function load_ehcc_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $logo = ($this->request->data['logo']);
            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $patient_tracker = isset($this->request->data['pt']) ? 'yes' : 'no';
            $againPlayVoice = array();
            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $result_array = $voice_array = array();
                $this->layout = 'ajax';
                $connection = ConnectionUtil::getConnection();
                $condition = " and staff.`status`='ACTIVE' and staff.staff_type='DOCTOR' and staff.counter_booking_type='PAYMENT' ";
                if ($patient_tracker == 'yes') {
                    $doctor_id_string = "'" . implode("','", $doctor_id_array) . "'";
                    $condition = " and staff.id IN ($doctor_id_string) ";
                }
                $query = "select staff.room_number,staff.id,staff.name, IF(dar.id IS NOT NULL,'Open','Closed') as counter_status from appointment_staffs as staff left join doctor_associates_receptionists as dar on dar.doctor_id = staff.id where  staff.thinapp_id = $thin_app_id $condition group by staff.id";

                $data = $connection->query($query);
                $list = $doctor_id_array = array();
                if ($data->num_rows) {
                    foreach (mysqli_fetch_all($data, MYSQLI_ASSOC) as $key => $val) {
                        $doctor_id_array[$val['id']] = $val['counter_status'];
                    }
                }
                foreach ($doctor_id_array as $doctor_id => $counterStatus) {

                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $service_name = $doctor_data['room_number'];
                    $result_array[$service_name][$doctor_id]['doctor_name'] = $doctor_data['name'];
                    $result_array[$service_name][$doctor_id]['profile_photo'] = !empty($doctor_data['profile_photo']) ? $doctor_data['profile_photo'] : $logo;
                    $result_array[$service_name][$doctor_id]['data'] = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
                    $doctor_name = $doctor_data['name'];
                    if ($counterStatus == 'Closed') {
                        // $result_array[$service_name][$doctor_id]['data']["current"]['token_number'] ='Closed';
                    }
                    $current_token = $result_array[$service_name][$doctor_id]['data']["current"]['token_number'];
                    //$result_array[$service_name][$doctor_id]['data']["current"]['token_number'] =$current_token;
                    $sub_token = $result_array[$service_name][$doctor_id]['data']["current"]['sub_token'];
                    if ($sub_token == "NO" && Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')) {
                        /* create and get token voice file name */
                        $fileName = Custom::getVoiceFileName($current_token, $doctor_name, $thin_app_id);
                        if (!empty($fileName)) {
                            $voice_array[$doctor_id] = $fileName;
                        }
                    }
                }
                if (!empty($againPlayVoice)) {
                    $againPlayVoice = implode(",", $againPlayVoice);
                }
                $this->set(compact('patient_tracker', 'againPlayVoice', 'thin_app_id', 'result_array', "voice_array"));


                $this->render('load_ehcc_tracker', 'ajax');
            }
        }
    }


    public function fortis_patient_tracker($appointment_id)
    {
        $this->layout = false;
        $appointmentData = array();
        $thin_app_id = $doctor_id = 0;
        $live_token = "-";
        $appointment_id = base64_decode($appointment_id);
        $query = "SELECT acss.thinapp_id, staff.id as doctor_id, acss.appointment_datetime, acss.queue_number,acss.sub_token,acss.emergency_appointment, acss.appointment_patient_name AS patient_name, staff.name AS doctor_name, IF(staff.profile_photo!='',staff.profile_photo,t.logo) as profile_photo FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id =acss.appointment_staff_id join thinapps as t on t.id = acss.thinapp_id WHERE acss.id =  $appointment_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $appointmentData =  mysqli_fetch_assoc($service_message_list);
            $thin_app_id = $appointmentData['thinapp_id'];
            $doctor_id = $appointmentData['doctor_id'];
        }
        $this->set(compact('appointmentData', 'thin_app_id', 'live_token', 'doctor_id'));
    }

    public function get_upcoming_tokens_list($doctor_id)
    {
        $this->layout = false;
        $this->autoRender = false;
        $doctor_data = Custom::get_doctor_by_id($doctor_id);
        $thin_app_id = $doctor_data['thinapp_id'];
        $tracker_data = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
        $token_number = @$tracker_data['current']['token_number'];
        if ($token_number > 0) {
            $final_string = "";
            $query = "SELECT acss.queue_number,acss.appointment_patient_name AS patient_name FROM appointment_customer_staff_services AS acss where acss.status IN ('NEW','CONFIRMED') and acss.skip_tracker ='NO' AND acss.appointment_staff_id=$doctor_id and DATE(acss.appointment_datetime) = DATE(NOW()) and CAST(acss.queue_number as UNSIGNED) > '$token_number' order by CAST(acss.queue_number as UNSIGNED) asc limit 5";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
                foreach ($list as $key => $val) {
                    $patient_name = $val['patient_name'];
                    $queue_number = $val['queue_number'];
                    if (strpos(strtolower($patient_name), "patient") !== false) {
                        $patient_name = "";
                    }
                    $final_string .= "<li><label>$patient_name<span style='float: right;'>$queue_number</span></label></li>";
                }
                echo $final_string;
                die;
            }
        }
        echo "";
        die;
    }


    public function fortis_get_current_token()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = base64_decode($this->request->data['ti']);
            $doctor_id = base64_decode($this->request->data['di']);
            $currentData = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
            echo json_encode(!empty($currentData['current']) ? $currentData['current'] : array('token_number' => '-'));
        }
        die;
    }

    public function dailyReportList($thin_app_id, $date)
    {
        $this->layout = false;
        $dataList = array();
        $thin_app_id = base64_decode($thin_app_id);
        $query = "SELECT acss.appointment_patient_name, IFNULL(ac.mobile,c.mobile) AS mobile,  acss.queue_number, bcfd.amount, acss.`status`, acss.appointment_datetime  FROM appointment_customer_staff_services AS acss LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id JOIN booking_convenience_fee_details AS bcfd ON acss.id  = bcfd.appointment_customer_staff_service_id WHERE  DATE(acss.appointment_datetime) = '$date' and acss.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $dataList =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
        }
        $app_data = Custom::get_thinapp_data($thin_app_id);
        $this->set(compact('dataList', 'date', 'app_data'));
    }

    public function links()
    {
        $this->layout = false;
        $query = "select t.id,t.name,t.slug from thinapps as t join app_enable_functionalities as aef on t.id = aef.thinapp_id and aef.app_functionality_type_id = 62 where t.status='ACTIVE' and t.name !='' order by t.name asc";
        $connection = ConnectionUtil::getConnection();
        $data_list = $connection->query($query);
        $list = array();
        if ($data_list->num_rows) {
            $list =  mysqli_fetch_all($data_list, MYSQLI_ASSOC);
        }
        $this->set(compact('list'));
    }

    public function getBillingCounterTokenList()
    {
        $response = array();
        if ($this->request->is('ajax')) {
            $thin_app_id = base64_decode($this->request->data['t']);
            $connection = ConnectionUtil::getConnection();
            $final_array = array();
            $query = "select staff.name as counter_name,acss.reminder_add_by_id,acss.queue_number from appointment_customer_staff_services as acss join appointment_staffs as staff on staff.id = acss.reminder_add_by_id AND staff.counter_booking_type='BILLING' where acss.thinapp_id = $thin_app_id and DATE(acss.appointment_datetime) = DATE(NOW()) and acss.`status` IN('NEW','CONFIRM','RESCHEDULE') order by acss.modified  ";
            $list = $connection->query($query);
            if ($list->num_rows) {
                $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
                $tokenList = array_column($final_array, 'queue_number');
                $tokenList = implode(", ", $tokenList);
                /*foreach ($data as $key => $val) {
                    $final_array[$val['counter_name']][] = $val;
                }*/
                $response['status'] = 1;
                $response['data'] = $tokenList;
            } else {
                $response['status'] = 0;
                $response['message'] = 'No list found';
            }
        }
        Custom::sendResponse($response);
        die;
    }

    public function qms_media_tracker()
    {
        $this->layout = false;
        $searchData = $this->request->query;
        $thin_app_id = ($searchData['t']);
        $patient_mobile = isset($searchData['pm']) ? base64_decode($searchData['pm']) : "";
        $ShowHeader = isset($searchData['sh']) ? ($searchData['sh']) : "yes";
        $patientToken = array();
        if (!empty($thin_app_id)) {
            $thin_app_id = base64_decode($thin_app_id);
            $thinapp_data = Custom::get_thinapp_data($thin_app_id);
            $tmp_list = Custom::get_all_app_doctor_id_name($thin_app_id);
            $doctor_array = $patientToken = array();

            if (!empty($patient_mobile)) {
                $date = date('Y-m-d');
                $query = "SELECT staff.name_label, acss.id as appointment_id, staff.show_token_into_digital_tud, staff.id as doctor_id, staff.department_category_id, acss.notes AS remark, acss.appointment_datetime, acss.queue_number,acss.sub_token, acss.appointment_patient_name AS patient_name, staff.name AS doctor_name  FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id =acss.appointment_staff_id JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id WHERE ac.thinapp_id = $thin_app_id AND ac.mobile = '$patient_mobile' AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = '$date'  ORDER BY acss.id DESC LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $patientToken =  mysqli_fetch_assoc($service_message_list);
                    foreach ($tmp_list as $doc_id => $doc_name) {
                        $doctor_data = Custom::get_doctor_by_id($doc_id, $thin_app_id);
                        if (empty($doctor_data['show_token_into_digital_tud']) || $doctor_data['show_token_into_digital_tud'] == $patientToken['doctor_id']) {
                            if (Custom::isCustomizedAppId($thin_app_id)) {
                                if ($doctor_data['department_category_id'] == '32') {
                                    $doctor_ids[] = $doc_id;
                                } else {
                                    $file_name = "fortis_tracker_" . $doc_id . "_" . date('Y-m-d');
                                    $tracker_data = json_decode(WebservicesFunction::readJson($file_name, "fortis"), true);
                                    if (!empty($tracker_data['current']) && is_numeric($tracker_data['current']['token_number'])) {
                                        $doctor_ids[] = $doc_id;
                                    }
                                }
                            } else {
                                $doctor_ids[] = $doc_id;
                            }
                        }
                    }
                }
            } else {
                foreach ($tmp_list as $doc_id => $doc_name) {
                    $doctor_data = Custom::get_doctor_by_id($doc_id, $thin_app_id);
                    if (Custom::isCustomizedAppId($thin_app_id)) {
                        if ($doctor_data['counter_booking_type'] == 'PAYMENT') {
                            $doctor_ids[] = $doc_id;
                        }
                    } else {
                        $doctor_ids[] = $doc_id;
                    }
                }
            }

            $doctor_ids = implode(",", $doctor_ids);

            $slide_list = array();
            if ($thin_app_id != EHCC_APP_ID) {
                $query = "select dc.category_name, staff.name as doctor_name, staff.sub_title, staff.description,staff.profile_photo from appointment_staffs as staff left join department_categories as dc  on dc.id = staff.department_category_id where staff.staff_type = 'DOCTOR' AND staff.`status`='ACTIVE' and staff.department_category_id != 32 and staff.thinapp_id = $thin_app_id  AND staff.description !='' order by CAST(staff.other_check_in_queue_after_number AS DECIMAL) asc ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $slide_list =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
                }
            }
        }
        $ehcc_app_id = EHCC_APP_ID;
        $is_custom_app = (Custom::isCustomizedAppId($thin_app_id)) ? 'YES' : 'NO';

        $billingTokenString = "";
        if (EHCC_APP_ID == $thin_app_id) {
            $billingTokenString  = Custom::getBillingCounterTokenString($thin_app_id);
        }

        $mediaData = $this->MediaMessage->find('all', array('conditions' => array("thinapp_id" => $thin_app_id, "status" => "ACTIVE"), 'order' => array("sort_order" => "ASC"), 'contain' => false));

        $this->set(compact('mediaData', 'billingTokenString', 'is_custom_app', 'ehcc_app_id', 'tmp_list', 'slide_list', 'doctor_ids', 'thin_app_id', 'thinapp_data', 'ShowHeader', 'patientToken'));
    }

    public function load_qms_media_tracker()
    {
        if ($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['t']);
            $logo = ($this->request->data['logo']);
            $doctor_id_array = explode(',', $this->request->data['doctor_id_string']);
            $patient_tracker = isset($this->request->data['pt']) ? 'yes' : 'no';
            $againPlayVoice = array();
            if (!empty($thin_app_id) && !empty($doctor_id_array)) {
                $thin_app_id = base64_decode($thin_app_id);
                $result_array = $voice_array = array();
                $this->layout = 'ajax';
                $connection = ConnectionUtil::getConnection();
                $condition = " and staff.`status`='ACTIVE' and staff.staff_type='DOCTOR' and staff.counter_booking_type='PAYMENT' ";
                if ($patient_tracker == 'yes') {
                    $doctor_id_string = "'" . implode("','", $doctor_id_array) . "'";
                    $condition = " and staff.id IN ($doctor_id_string) ";
                }
                $query = "select staff.room_number,staff.id,staff.name, IF(dar.id IS NOT NULL,'Open','Closed') as counter_status from appointment_staffs as staff left join doctor_associates_receptionists as dar on dar.doctor_id = staff.id where  staff.thinapp_id = $thin_app_id $condition group by staff.id";

                $data = $connection->query($query);
                $list = $doctor_id_array = array();
                if ($data->num_rows) {
                    foreach (mysqli_fetch_all($data, MYSQLI_ASSOC) as $key => $val) {
                        $doctor_id_array[$val['id']] = $val['counter_status'];
                    }
                }
                foreach ($doctor_id_array as $doctor_id => $counterStatus) {

                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $service_name = $doctor_data['room_number'];
                    $result_array[$service_name][$doctor_id]['doctor_name'] = $doctor_data['name'];
                    $result_array[$service_name][$doctor_id]['profile_photo'] = !empty($doctor_data['profile_photo']) ? $doctor_data['profile_photo'] : $logo;
                    $result_array[$service_name][$doctor_id]['data'] = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
                    $doctor_name = $doctor_data['name'];
                    if ($counterStatus == 'Closed') {
                        // $result_array[$service_name][$doctor_id]['data']["current"]['token_number'] ='Closed';
                    }
                    $current_token = $result_array[$service_name][$doctor_id]['data']["current"]['token_number'];
                    //$result_array[$service_name][$doctor_id]['data']["current"]['token_number'] =$current_token;
                    $sub_token = $result_array[$service_name][$doctor_id]['data']["current"]['sub_token'];
                    if ($sub_token == "NO" && Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')) {
                        /* create and get token voice file name */
                        $fileName = Custom::getVoiceFileName($current_token, $doctor_name, $thin_app_id);
                        if (!empty($fileName)) {
                            $voice_array[$doctor_id] = $fileName;
                        }
                    }
                }
                if (!empty($againPlayVoice)) {
                    $againPlayVoice = implode(",", $againPlayVoice);
                }
                $this->set(compact('patient_tracker', 'againPlayVoice', 'thin_app_id', 'result_array', "voice_array"));


                $this->render('load_qms_media_tracker', 'ajax');
            }
        }
    }
}
