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
class FranchiseController extends AppController
{

    /**
     * This controller does not use a model
     *
     * @var array
     */

    function beforeFilter()
    {
        $login = $this->Session->read('Auth.User.SmartClinicMediator');
        $this->layout = 'mediator_layout';
        parent::beforeFilter();
        $action = $this->request->params['action'];

        $this->Auth->allow('login','send_otp','verify','logout');



    }
    public function login()
    {
        $this->layout = "franchise_layout";
        if ($this->Session->read('Auth.User.SmartClinicMediator')) {
                $this->redirect(array('controller' => 'mediator', 'action' => 'dashboard'));
        }
    }

    public function send_otp()
    {

        if ($this->request->is('ajax')) {

            $mobile = isset($this->request->data['m']) ? Custom::create_mobile_number($this->request->data['m']) : '';
            $type = isset($this->request->data['t']) ? strtoupper($this->request->data['t']) : '';
            $response = array();
            $otp_send = false;
            $mediator_id = 0;
            if (empty($mobile)) {
                $response['status'] = 0;
                $response['message'] = "Please enter valid mobile number";
            }else if ($type !='OTP' && $type !='PASSWORD' ) {
                $response['status'] = 0;
                $response['message'] = "Invalid login option";
            }else {
                $verification_code = Custom::getRandomString(4);
                $option = array(
                    'username' => $mobile,
                    'mobile' => $mobile,
                    'verification' => $verification_code,
                    'thinapp_id' => 134
                );
                $user_data = $this->SmartClinicMediator->find('first', array('contain' => false, 'conditions' => array('SmartClinicMediator.mobile' => "$mobile")));
                if(!empty($user_data)){

                    if($user_data['SmartClinicMediator']['status']=='ACTIVE'){
                        $mediator_id = @$user_data['SmartClinicMediator']['id'];
                        if($type=="OTP"){
                            if ($this->SmartClinicMediator->updateAll(array('SmartClinicMediator.verification_code' => "$verification_code"), array('SmartClinicMediator.id' => $mediator_id))) {
                                if (Custom::send_otp($option)) {
                                    $otp_send = true;
                                }else{
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry OTP not sent.";
                                }
                            }
                        }
                    }else{
                        $response['status'] = 0;
                        $response['message'] = "This account has been temporary disabled";
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Invalid Account";
                }

            }
            $this->set(compact('otp_send','response','mediator_id','type'));
        } else {
            exit();
        }

    }


    public function verify()
    {
        $this->autoRender = false;
        $response = array();
        if ($this->request->is(array('Put', 'Post'))) {

            $mediator_id = isset($this->request->data['i']) ? base64_decode($this->request->data['i']): '';
            $otp = isset($this->request->data['c']) ? $this->request->data['c']: '';
            $password = isset($this->request->data['p']) ? $this->request->data['p']: '';
            $type = isset($this->request->data['t']) ? $this->request->data['t']: '';
            $response = array();
            if (empty($mediator_id)) {
                $response['status'] = 0;
                $response['message'] = "Invalid mediator id";
            }else if ($type !='OTP' && $type !='PASSWORD' ) {
                $response['status'] = 0;
                $response['message'] = "Invalid login option";
            }else if (empty($otp) && $type=="OTP") {
                $response['status'] = 0;
                $response['message'] = "Please enter OTP";
            }else if (empty($password) && $type=="PASSWORD") {
                $response['status'] = 0;
                $response['message'] = "Please enter password";
            }else{

                if($type=="OTP"){
                    $condition = array('SmartClinicMediator.id' => $mediator_id,'SmartClinicMediator.verification_code'=>$otp);
                }else{
                    $condition = array('SmartClinicMediator.id' => $mediator_id,'SmartClinicMediator.password'=>md5($password));
                }
                $mediator_data = $this->SmartClinicMediator->find('first', array('contain' => false, 'conditions' => $condition));
                if (!empty($mediator_data)) {
                    if ($this->Auth->login($mediator_data)) {
                        $response['status']=1;
                        $response['message'] = "Verify Successfully";
                    }
                } else {
                    $response['status']=0;
                    if($type=="OTP"){
                        $response['message'] = "Please enter valid OTP";
                    }else{
                        $response['message'] = "Incorrect Password";
                    }
                }
            }

            echo json_encode($response);

        }


    }
    public function dashboard()
    {
        $this->layout = "franchise_layout";
        $login = $this->Session->read('Auth.User.SmartClinicMediator');
        $mediator_id = $login['id'];

        $connection = ConnectionUtil::getConnection();
        $total = $today_total = $week_total =$month_total= $today_total_app_list = 0;
        $today_total_app_list = $mediator_app_list = array();

        $select = "IFNULL(SUM(IF(bcfd.primary_owner_id = $mediator_id, bcfd.primary_owner_share_fee,0)+ IF(bcfd.secondary_owner_id= $mediator_id ,bcfd.secondary_owner_share_fee,0)+ IF(bcfd.primary_mediator_id = $mediator_id, bcfd.primary_mediator_share_fee,0)+ IF(bcfd.secondary_mediator_id = $mediator_id, bcfd.secondary_mediator_share_fee,0)),'0.00') AS total";
        $base_condition = " ( bcfd.primary_owner_id = $mediator_id OR bcfd.secondary_owner_id = $mediator_id OR bcfd.primary_mediator_id = $mediator_id OR bcfd.secondary_mediator_id = $mediator_id) AND bcfd.`status` = 'ACTIVE'";
        /* GET TOTAL STATS */
        $condition = "";
        $query = "SELECT $select FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where  $base_condition $condition";

        $list = $connection->query($query);
        if ($list->num_rows) {
            $total = Custom::splitAfterDecimal(mysqli_fetch_assoc($list)['total']);
        }


        /* GET TODAY STATS */
        $condition = " and DATE(bcfd.created)= DATE(now()) ";
        $query = "SELECT $select FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where  $base_condition $condition";

        $list = $connection->query($query);
        if ($list->num_rows) {
            $today_total = Custom::splitAfterDecimal(mysqli_fetch_assoc($list)['total']);
        }

        /* GET WEEK STATS */
        $condition = " and WEEK(bcfd.created)= WEEK(NOW()) ";
        $query = "SELECT $select FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where  $base_condition $condition";

        $list = $connection->query($query);
        if ($list->num_rows) {
            $week_total = Custom::splitAfterDecimal(mysqli_fetch_assoc($list)['total']);
        }

        /* GET MONTH STATS */
        $condition = " and MONTH(bcfd.created)= MONTH(NOW()) ";
        $query = "SELECT $select FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where  $base_condition $condition";

        $list = $connection->query($query);
        if ($list->num_rows) {
            $month_total = Custom::splitAfterDecimal(mysqli_fetch_assoc($list)['total']);
        }

        /* GET TODAY STATS WITH APP LIST */
        $query = "SELECT t.name AS app_name, SUM(IF(bcfd.primary_owner_id = $mediator_id, bcfd.primary_owner_share_fee,0)+ IF(bcfd.secondary_owner_id = $mediator_id ,bcfd.secondary_owner_share_fee,0)+ IF(bcfd.primary_mediator_id = $mediator_id, bcfd.primary_mediator_share_fee,0)+ IF(bcfd.secondary_mediator_id = $mediator_id, bcfd.secondary_mediator_share_fee,0)) AS total FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where $base_condition and DATE(bcfd.created) = DATE(NOW()) GROUP BY bcfd.thinapp_id ";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $today_total_app_list = mysqli_fetch_all($list,MYSQL_ASSOC);
        }

        /* GET TODAY STATS WITH APP LIST */
        $query = "SELECT scma.*, t.name AS app_name FROM smart_clinic_mediator_associate AS scma JOIN thinapps AS t ON t.id = scma.thinapp_id WHERE (scma.primary_owner_id = $mediator_id OR scma.secondary_owner_id = $mediator_id OR scma.primary_mediator_id = $mediator_id OR scma.secondary_mediator_id = $mediator_id ) and scma.status='ACTIVE' ";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $list = mysqli_fetch_all($list,MYSQL_ASSOC);

            foreach ($list as $key =>$value){
                $thin_app_name = $value['app_name'];
                if(!empty($value['primary_owner_id'])){
                    $mediator_app_list[$thin_app_name][] = array('role'=>Custom::getFranchiseRoleLabel('primary_owner_id'),'percentage'=>$value['primary_owner_share_percentage']);
                }
                if(!empty($value['primary_mediator_id'])){
                    $mediator_app_list[$thin_app_name][] = array('role'=>Custom::getFranchiseRoleLabel('primary_mediator_id'),'percentage'=>$value['primary_mediator_share_percentage']);
                }
                if(!empty($value['secondary_owner_id'])){
                    $mediator_app_list[$thin_app_name][] = array('role'=>Custom::getFranchiseRoleLabel('secondary_owner_id'),'percentage'=>$value['secondary_owner_share_percentage']);
                }
                if(!empty($value['secondary_mediator_id'])){
                    $mediator_app_list[$thin_app_name][] = array('role'=>Custom::getFranchiseRoleLabel('secondary_mediator_id'),'percentage'=>$value['secondary_mediator_share_percentage']);
                }

            }




        }

        $this->set(compact('today_total','total','week_total','month_total','today_total_app_list','mediator_app_list'));
    }





    public function search_report()
    {
        $reqData = $this->request->data['Search'];
        $pram = array();
        if (!empty($reqData['from_date'])) {
            $pram['df'] = $reqData['from_date'];
        }
        if (!empty($reqData['to_date'])) {
            $pram['dt'] = $reqData['to_date'];
        }

        if (!empty($reqData['thinapp_id'])) {
            $pram['t'] = $reqData['thinapp_id'];
        }
        if (!empty($reqData['settled'])) {
            $pram['s'] = $reqData['settled'];
        }
         if (!empty($reqData['role'])) {
            $pram['r'] = $reqData['role'];
        }

        if (!empty($reqData['search_by'])) {
            $pram['sb'] = $reqData['search_by'];
        }
         if (!empty($reqData['month'])) {
            $pram['m'] = $reqData['month'];
        }


        $this->redirect(
            array(
                "controller" => "franchise",
                "action" => "report",
                "?" => $pram,
            )
        );
    }

    public function report()
    {
        $this->layout = "franchise_layout";
        $login = $this->Session->read('Auth.User.SmartClinicMediator');
        $mediator_id = $login['id'];




        $condition = " bcfd.status='ACTIVE'";



        $searchData = $this->request->query;

        if (isset($searchData['r']) && !empty($searchData['r'])) {
            $this->request->data['Search']['role'] = $role = $searchData['r'];
            $condition .= " and bcfd.$role = $mediator_id";
        }else{
            $condition .= " and (bcfd.primary_owner_id = $mediator_id OR bcfd.secondary_owner_id = $mediator_id OR bcfd.primary_mediator_id = $mediator_id OR bcfd.secondary_mediator_id = $mediator_id )";
        }



        if (isset($searchData['s']) && !empty($searchData['s'])) {

            $this->request->data['Search']['settled'] = $settled = $searchData['s'];
            $condition .= " and bcfd.is_settled = '$settled'";
        }

        if (isset($searchData['t']) && !empty($searchData['t'])) {
            $this->request->data['Search']['thinapp_id'] = $thin_app1_id = $searchData['t'];
            $condition .= " and bcfd.thinapp_id = $thin_app1_id";
        }


        $search_by='DATE';
        if (isset($searchData['sb']) && $searchData['sb']=='m') {
            $this->request->data['Search']['search_by'] = $searchData['sb'];
            if (isset($searchData['m']) && !empty($searchData['m'])) {
                $this->request->data['Search']['month'] = $month = $searchData['m'];
                 $condition .= " and MONTH(bcfd.created) = $month";
                 $search_by='MONTH';
            }
            $query = "SELECT t.name as app_name, CONCAT(MONTHNAME(bcfd.created),'-',DATE_FORMAT(bcfd.created,'%Y')) AS label, SUM(IF(bcfd.primary_owner_id=$mediator_id,bcfd.primary_owner_share_fee,0)) AS primary_owner_share_fee, SUM(IF(bcfd.secondary_owner_id=$mediator_id,bcfd.secondary_owner_share_fee,0)) AS secondary_owner_share_fee, SUM(IF(bcfd.primary_mediator_id=$mediator_id,bcfd.primary_mediator_share_fee,0)) AS primary_mediator_share_fee, SUM(IF(bcfd.secondary_owner_id=$mediator_id,bcfd.secondary_mediator_share_fee,0)) AS secondary_mediator_share_fee, bcfd.is_settled  FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id  WHERE $condition group by bcfd.thinapp_id,label    ";

        }else{
            if (isset($searchData['df']) && !empty($searchData['df']) && isset($searchData['dt']) && !empty($searchData['dt'])) {
                $this->request->data['Search']['from_date'] = $searchData['df'];
                $this->request->data['Search']['to_date'] = $searchData['dt'];
                $date_from = DateTime::createFromFormat('d/m/Y', $searchData['df'])->format('Y-m-d');
                $date_to = DateTime::createFromFormat('d/m/Y', $searchData['dt'])->format('Y-m-d');
                $condition .= " and DATE(bcfd.created) BETWEEN '$date_from' and '$date_to' ";
            }else{
                $this->request->data['Search']['from_date'] = date('d/m/Y');
                $this->request->data['Search']['to_date'] = date('d/m/Y');
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d');
                $condition .= " and DATE(bcfd.created) BETWEEN '$date_from' and '$date_to' ";
            }
            $query = "SELECT DATE_FORMAT(bcfd.created,'%d/%m/%Y %H:%i') as label, bcfd.is_settled, t.name as app_name,  (IF(bcfd.primary_owner_id=$mediator_id,bcfd.primary_owner_share_fee,0)) AS primary_owner_share_fee, (IF(bcfd.secondary_owner_id=$mediator_id,bcfd.secondary_owner_share_fee,0)) AS secondary_owner_share_fee, (IF(bcfd.primary_mediator_id=$mediator_id,bcfd.primary_mediator_share_fee,0)) AS primary_mediator_share_fee, (IF(bcfd.secondary_owner_id=$mediator_id,bcfd.secondary_mediator_share_fee,0)) AS secondary_mediator_share_fee FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id  WHERE $condition ";

        }
       $connection = ConnectionUtil::getConnection();
        $data_list =array();
         $list = $connection->query($query);
        if ($list->num_rows) {
            $data_list = mysqli_fetch_all($list,MYSQL_ASSOC);
        }

        $this->set(compact('data_list','search_by'));
    }


    public function update_password()
    {
        $this->autoRender = false;
        $response = array();
        if ($this->request->is(array('Put', 'Post'))) {

            $login = $this->Session->read('Auth.User.SmartClinicMediator');
            $mediator_id = $login['id'];
            $new_password = isset($this->request->data['n']) ? $this->request->data['n']: '';
            $confirm_password = isset($this->request->data['c']) ? $this->request->data['c']: '';
            $response = array();
            if (empty($mediator_id)) {
                $response['status'] = 0;
                $response['message'] = "Invalid mediator id";
            }else if (empty($new_password)) {
                $response['status'] = 0;
                $response['message'] = "Please enter new password";
            }else if (empty($confirm_password)) {
                $response['status'] = 0;
                $response['message'] = "Please enter confirm password";
            }else if ($confirm_password != $new_password) {
                $response['status'] = 0;
                $response['message'] = "Confirm password and new password does not match";
            }else{

                $condition = array('SmartClinicMediator.id' => $mediator_id);
                $mediator_data = $this->SmartClinicMediator->find('first', array('contain' => false, 'conditions' => $condition));
                if (!empty($mediator_data)) {
                    $new_password = md5($new_password);
                    if ($this->SmartClinicMediator->updateAll(array('SmartClinicMediator.password' => "'$new_password'"), array('SmartClinicMediator.id' => $mediator_id))) {
                        $response['status']=1;
                        $response['message'] = "Password Update Successfully";
                    }else{
                        $response['status']=0;
                        $response['message'] = "Sorry password could not update";
                    }
                } else {
                    $response['status']=0;
                    $response['message'] = "Invalid user";
                }
            }

            echo json_encode($response);

        }


    }

    public function logout()
    {
        $this->layout = 'app_admin_home';
        $this->Auth->logout();
        $this->redirect("/franchise");
    }


}