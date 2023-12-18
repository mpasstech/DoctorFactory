<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
include(WWW_ROOT . "webservice" . DS . "ConnectionUtil.php");
include(WWW_ROOT . "webservice" . DS . "WebservicesFunction.php");
include(WWW_ROOT . "webservice" . DS . "WebServicesFunction_2_3.php");


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TelemedicineController extends AppController
{

    /**
     * This controller does not use a model
     *
     * @var array
     */

    function beforeFilter()
    {

        $login = $this->Session->read('Auth.User');

        $this->layout = 'app_admin_home';
        parent::beforeFilter();



    }

    public function search_telemedicine_report(){
        $reqData = $this->request->data['Search'];
        $pram = array();

        if (!empty($reqData['from_date'])) {
            $pram['f'] = $reqData['from_date'];
        }
        if (!empty($reqData['to_date'])) {
            $pram['t'] = $reqData['to_date'];
        }
        if (!empty($reqData['service_type'])) {
            $pram['st'] = $reqData['service_type'];
        }
        if (!empty($reqData['status'])) {
            $pram['s'] = $reqData['status'];
        }


        $this->redirect(
            array(
                "controller" => "telemedicine",
                "action" => "telemedicine_report",
                "?" => $pram,
            )
        );
    }

    public function telemedicine_report(){

        $this->layout = "app_admin_home";

        $login = $this->Session->read('Auth.User.User');
        $thin_app_id = $login['thinapp_id'];

        $login1 = $this->Session->read('Auth.User');
        $condition = " tl.thinapp_id = $thin_app_id AND tl.`status` = 'ACTIVE' AND tl.is_refund = 'NO'";
        $searchData = $this->request->query;

        if (isset($searchData['f']) && !empty($searchData['f']) && !empty($searchData['t']) && isset($searchData['t'])) {
            $this->request->data['Search']['from_date'] = $searchData['f'];
            $from_date = DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
            $this->request->data['Search']['to_date'] = $searchData['t'];
            $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
            $condition .= " and  DATE(tl.created) BETWEEN '$from_date' and '$to_date'";

        } else {
            $today = date("d/m/Y");
            $search_today = date("Y-m-d");
            $this->request->data['Search']['to_date'] = $this->request->data['Search']['from_date'] = $today;
            $condition .= " and  DATE(tl.created) BETWEEN '$search_today' and '$search_today'";
        }

        if (isset($searchData['st'])) {
            $this->request->data['Search']['service_type'] = $searchData['st'];
            $condition .= " and  tl.telemedicine_service_type = '".$searchData['st']."'";
        }

        if (isset($searchData['s'])) {
            $this->request->data['Search']['status'] = $searchData['s'];

            if($searchData['s'] == 'completed'){
                $condition .= " and  tcl.connect_status  = '".$searchData['s']."'";
            }else{
                $condition .= " and  tcl.connect_status  != 'completed'";
            }
            
        }

        $allData=array();
        $query = "SELECT tl.created, tl.telemedicine_service_type, staff.name AS doctor_name, tcl.connect_status, tl.id, IF(tl.consult_for_name='',u.username,tl.consult_for_name) AS name, IF(tl.consult_for_mobile='',u.mobile,tl.consult_for_mobile) as mobile,tcl.duration,tcl.start_time,tcl.end_time,tcl.doctor_share as amount, tcl.call_charges, tl.is_paid  FROM telemedicine_leads AS tl left JOIN appointment_staffs AS staff ON staff.id = tl.appointment_staff_id  left JOIN telemedicine_call_logs AS tcl ON tl.id = tcl.telemedicine_lead_id  AND tcl.`status`='ACTIVE' left JOIN users AS u ON u.id = tl.user_id WHERE $condition order by tl.id desc";

        $connection = ConnectionUtil::getConnection();
         $list = $connection->query($query);
        if ($list->num_rows) {
            $allData  = mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        $reportTitle = $login1['Thinapp']['name'].' ('.date('d/m/Y').')';
        $this->set(array('allData'=>$allData,'reportTitle'=>$reportTitle));
    }



    public function setting(){

        $this->layout = "app_admin_home";
        $question_list = array();
        $login = $this->Session->read('Auth.User.User');
        $thin_app_id = $login['thinapp_id'];


        if ($this->request->is('post')) {

            echo "<pre>";
            print_r($this->request->data);die;

        }

        $query = "select * from telemedicine_chat_questions where thinapp_id =  $thin_app_id and status = 'ACTIVE' order by order_number asc";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $question_list  = mysqli_fetch_all($list,MYSQL_ASSOC);
        }

        $this->set(compact('question_list'));
    }


}