<?php
App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");
//header("Access-Control-Allow-Origin: https://mngz.in:1703");





class DTIController extends AppController {
	
	public $uses = array();
	public $components = array('Custom');
	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = 'home';
		$this->Auth->allow('login','index');
	}
    public function index($slug){
	    $this->layout =false;
	    echo $slug;die;
    }
    public function  checkLogin(){
	    $this->autoRender=false;
        $staff_id =base64_decode($this->request->data['si']);
        $password =($this->request->data['p']);
        $convert =($this->request->data['convert']);
        if($convert=='yes'){
            $password =md5($this->request->data['p']);
        }

        $allow_login = Custom::check_doctor_active_login_status($staff_id, $password);
        if ($allow_login) {
            $response['status'] = 1;
            $response['message'] = "SUCCESS";
            $data = Custom::get_doctor_by_id($staff_id);;
            $tmp['id']= $data['id'];
            $tmp['name']= $data['name'];
            $tmp['mobile']= $data['mobile'];
            $tmp['password']= $data['password'];
            $response['data'] = $tmp;

        }else{
            $response['status'] = 0;
            $response['message'] = "FAIL";
        }
        echo json_encode($response);die;
    }
    public function send_otp(){
        $this->autoRender = false;
        if($this->request->is(array('Post','Put'))) {
            $phone = Custom::create_mobile_number($this->request->data['m']);
            if($phone){
                $verification_code = Custom::getRandomString(6);
                $verification_code = '123456';
                $option = array(
                    'username' => 'New User',
                    'mobile' => $phone,
                    'verification' => $verification_code,
                    'thinapp_id' => 134
                );
                if(true || Custom::send_otp($option)){
                    return base64_encode($verification_code);
                }

            }
        }

        return false;
    }


}
