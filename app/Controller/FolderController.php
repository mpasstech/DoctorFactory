<?php
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");


class FolderController extends AppController {

	//public $name = 'Supsp';
	public $helpers = array('Custom');
	public $uses = array('DriveFile','DriveFolder','DriveShare','User','Consent','DoctorRefferal','DoctorRefferalHistory','DoctorRefferalUser','LabPharmacyUser');

	/*****************************************
	function :- index
	 *****************************************/
	 
	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = false;
		$this->Auth->allow(array('load_records','upload_file','add_file_notify','record','send_otp','verify_share','index','update_lab_request','folder_Share_static','lab_request','verify_lab_request','consent','verify','refral_user_static','rerefer','get_refer_detail'));

    }

     public function index($param=null,$doctor_id=null){

        $send_param = $param;
        $param = $this->Custom->decodeVariable($param);
        /*
         * param 3 are coming for share folder case
         * param 1 are coming for copy link or share link for folder
         * */
        $direct_load = 'NO';
        $data = explode("##",$param);
        $share_with_mobile ='';
        $dialog = 'ALERT';
        $title = "Warning";
        $driveFolder = array();
        $drive_folder_id = $drive_file_id =  0;
        $thin_app_id = 0;
        $parameterCount = count($data);
        if(count($data) ==3){
            $type = $data[1];
            $drive_folder_id = ($type=='FOLDER')?$data[0]:0;
            $drive_file_id = ($type=='FILE')?$data[0]:0;
            $share_with_mobile = $data[2];
        }else{
            $type = $data[1];
            $drive_folder_id = ($type=='FOLDER')?$data[0]:0;
            $drive_file_id = ($type=='FILE')?$data[0]:0;
        }
        if(( !empty($drive_folder_id) || !empty($drive_file_id))){
            if($parameterCount == 3 &&( $type=='FOLDER' || $type = 'FILE')){
                $query = "SELECT ds.id, ds.thinapp_id, IFNULL(IFNULL(ac.first_name,c.child_name),df.folder_name) as folder_name FROM drive_folders AS df   join drive_shares AS ds ON df.id = ds.drive_folder_id AND ds.share_with_mobile ='$share_with_mobile' and ds.status = 'SHARED' left join appointment_customers as ac on ac.id = df.appointment_customer_id left join childrens as c on c.id = df.children_id WHERE  df.id = $drive_folder_id" ;
                if($type == 'FILE') {
                    $query = "SELECT ds.id, ds.thinapp_id, IFNULL(IFNULL(ac.first_name,c.child_name),folder.folder_name) as folder_name FROM drive_files AS d_file JOIN drive_folders AS folder ON folder.id = d_file.drive_folder_id join drive_shares AS ds ON d_file.id = ds.drive_file_id AND ds.share_with_mobile ='$share_with_mobile' and ds.status = 'SHARED' left join appointment_customers as ac on ac.id = folder.appointment_customer_id left join childrens as c on c.id = folder.children_id WHERE  d_file.id = $drive_file_id";
                }
                $connection = ConnectionUtil::getConnection();
                $list = $connection->query($query);
                if($list->num_rows){
                    $folderData = mysqli_fetch_assoc($list);
                    $drive_share_id = ($folderData['id']);
                    $folder_name = $folderData['folder_name'];
                    $thin_app_id = $folderData['thinapp_id'];
                    $dialog = 'OTP';
                    $title = "Generate OTP";
                    $message = 'OTP will be send to mobile number \n *******'.substr($share_with_mobile,-3);
                    $drive_share_id = base64_encode($folderData['id']);

                }else{
                    $message = "Sorry, You have no longer access to see this record";
                }
            }else if($parameterCount == 2){

                $query = "SELECT df.is_locked, df.thinapp_id, IFNULL(IFNULL(ac.first_name,c.child_name),df.folder_name) as folder_name FROM drive_folders AS df left join appointment_customers as ac on ac.id = df.appointment_customer_id left join childrens as c on c.id = df.children_id WHERE  df.id = $drive_folder_id and df.status = 'ACTIVE'" ;
                if($type == 'FILE') {
                    $query = "SELECT 'NO' as is_locked, folder.thinapp_id, IFNULL(IFNULL(ac.first_name,c.child_name),folder.folder_name) as folder_name FROM drive_files AS d_file JOIN drive_folders AS folder ON folder.id = d_file.drive_folder_id left join appointment_customers as ac on ac.id = folder.appointment_customer_id left join childrens as c on c.id = folder.children_id WHERE  d_file.id = $drive_file_id AND d_file.status='ACTIVE'";
                }
                $connection = ConnectionUtil::getConnection();
                $list = $connection->query($query);
                if($list->num_rows){
                    $folderData = mysqli_fetch_assoc($list);
                    $is_locked = $folderData['is_locked'];
                    $folder_name = $folderData['folder_name'];
                    $thin_app_id = $folderData['thinapp_id'];
                    if($is_locked =='YES'){
                        $dialog = 'OTP';
                        $title = "Password Verification";
                        $message = 'Please enter folder password for show medical record';
                    }else{
                        $direct_load = 'YES';
                    }
                }else{
                    $message = "Sorry, You have no longer access to see this record";
                }

            }else{
                $message = "Sorry, You have no longer access to see this record";
            }
        }else{
            $message = "Sorry, You have no longer access to see this record";
        }
        $share_with_mobile = base64_encode($share_with_mobile);
     	$web_app_url=false;
        if(!empty($doctor_id)){
            $doctor_id = base64_decode($doctor_id);
            $web_app_url = Custom::create_web_app_url($doctor_id,$thin_app_id);
        }
        $thin_app_id = base64_encode($thin_app_id);
        $this->set(compact('web_app_url','drive_folder_id','share_with_mobile','title','message','dialog','send_param','folder_name','drive_share_id','direct_load','share_with_mobile','thin_app_id'));

    }

    public function send_otp(){

        $share_with_mobile = base64_decode($this->request->data['m']);
        $drive_share_id = base64_decode($this->request->data['ds']);
        $thin_app_id = base64_decode($this->request->data['t']);
        $verification_code = Custom::getRandomString(6);
        $option = array(
            'username' => $share_with_mobile,
            'mobile' => $share_with_mobile,
            'verification' => $verification_code,
            'thinapp_id' => $thin_app_id
        );
        $otp_sent = Custom::send_otp($option);
        $verification_code = md5($verification_code);
        $created = Custom::created();
        $response =array();
        if(($this->DriveShare->updateAll(array('DriveShare.otp_datetime'=>"'".$created."'",'DriveShare.otp'=>"'".$verification_code."'"),array('DriveShare.id'=>$drive_share_id))) && $otp_sent){
            $response['status'] = 1;
            $response['message'] = "OTP Send Successfully";
        }else{
            $response['status'] = 1;
            $response['message'] =  "Sorry, we are unable to send OTP.";;
        }
        echo  json_encode($response);die;

    }

    public function verify_share(){

        $param = $this->Custom->decodeVariable($this->request->data['param']);
        $otp = md5($this->request->data['otp']);
        $drive_share_id = base64_decode($this->request->data['ds']);
        $data = explode("##",$param);
        $response =array();
        $share_with_mobile ='';
        $parameterCount = count($data);
        if(count($data) ==3){
            $type = $data[1];
            $drive_folder_id = ($type=='FOLDER')?$data[0]:0;
            $drive_file_id = ($type=='FILE')?$data[0]:0;
            $share_with_mobile = $data[2];
        }else{
            $type = $data[1];
            $drive_folder_id = ($type=='FOLDER')?$data[0]:0;
            $drive_file_id = ($type=='FILE')?$data[0]:0;
        }
        if(!empty($drive_share_id) && $parameterCount==3){
            $query = "SELECT id from drive_shares where id = $drive_share_id and otp = '$otp'";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if($list->num_rows) {
                if($type=='FOLDER' || $type = 'FILE'){

                    $select = "d_files.file_type, d_files.file_path, d_files.file_name,d_files.created,u.username,d_files.listing_type, d_files.memo_text";
                    $query = "SELECT $select  FROM drive_shares AS ds JOIN drive_folders AS df ON df.id = ds.drive_folder_id  JOIN drive_files AS d_files ON d_files.drive_folder_id = df.id AND d_files.status='ACTIVE' LEFT JOIN users AS u ON u.mobile = ds.share_from_mobile AND ds.thinapp_id = u.thinapp_id WHERE df.id = $drive_folder_id  and ds.status = 'SHARED' group by d_files.id order by d_files.id desc";
                    if($type=='FILE'){
                        $query = "SELECT $select  FROM drive_shares AS ds  JOIN drive_files AS d_files ON d_files.id = ds.drive_file_id AND d_files.status='ACTIVE' join drive_folders as df on df.id = d_files.drive_folder_id LEFT JOIN users AS u ON u.mobile = ds.share_from_mobile AND ds.thinapp_id = u.thinapp_id WHERE ds.drive_file_id = $drive_file_id and ds.share_with_mobile = '$share_with_mobile' and ds.status = 'SHARED'  group by d_files.id order by d_files.id desc LIMIT 1";
                    }
                    $list_data=array();
                    $connection = ConnectionUtil::getConnection();
                    $list = $connection->query($query);
                    if($list->num_rows) {
                        $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
                    }
                    $this->set(compact('list_data'));
                    $html =  $this->render('verify_share', 'ajax')->body();;
                    $response['status'] = 1;
                    $response['html'] = $html;
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry, You have no longer access to see this record.";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Your have enter invalid OTP.";
            }
        }else if($parameterCount==2){

            $is_locked = 'NO';
            $pin = false;
            $otp = @$this->request->data['otp'];
            if($type == 'FOLDER') {
                $query = "SELECT df.pin, df.is_locked FROM drive_folders AS df WHERE  df.id = $drive_folder_id and df.status = 'ACTIVE'" ;
                $connection = ConnectionUtil::getConnection();
                $list = $connection->query($query);
                if($list->num_rows) {
                    $folder = mysqli_fetch_assoc($list);
                    $is_locked = $folder['is_locked'];
                    $pin = $folder['pin'];
                }
            }
            if($is_locked=='NO' || ($is_locked=='YES' && ( $otp == $pin)  && !empty($otp) )) {
                if($type=='FOLDER' || $type = 'FILE'){
                    $condition = " df.id = $drive_folder_id AND df.status = 'ACTIVE'";
                    if($type=='FILE'){
                        $condition = " d_files.id = $drive_file_id and d_files.status = 'ACTIVE'";
                    }
                    $list_data=array();
                    $query = "SELECT d_files.file_type, d_files.file_path, d_files.file_name,d_files.created,u.username,d_files.listing_type, d_files.memo_text  FROM drive_folders AS df  JOIN drive_files AS d_files ON d_files.drive_folder_id = df.id AND d_files.status='ACTIVE' LEFT JOIN users AS u ON u.id = d_files.user_id WHERE  $condition group by d_files.id order by d_files.id desc";
                    $connection = ConnectionUtil::getConnection();
                    $list = $connection->query($query);
                    if($list->num_rows) {
                        $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
                        $this->set(compact('list_data'));
                        $html =  $this->render('verify_share', 'ajax')->body();;
                        $response['status'] = 1;
                        $response['html'] = $html;
                    }else{
                        $response['status'] = 0;
                        $response['message'] = "Sorry, You have no longer access to see this record.";
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry, You have no longer access to see this record.";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Please enter valid folder password.";
            }
        }else{
            $response['status'] = 0;
            $response['message'] = "Invalid Url";
        }



        echo  json_encode($response); die;

    }

	public function load_records(){

	    $folder_id = isset($this->request->data['fi'])?$this->request->data['fi']:0;

	    $mobile = Custom::create_mobile_number(base64_decode($this->request->data['m']));
	    $offset = ($this->request->data['offset']);

	    $condition = "(ac.mobile='$mobile' OR c.mobile='$mobile' ) and ds.status = 'SHARED' ";
	    if(!empty($folder_id)){
            $condition = "df.id = $folder_id";
        }

	    $limit = 15;
	    $offset = $limit * $offset;
        if ($this->request->is(array('ajax'))) {
            $select = "t.name AS app_name, IFNULL(ac.first_name,c.child_name) AS patient_name, d_files.file_type, d_files.file_path, d_files.file_name,d_files.created,u.username,d_files.listing_type, d_files.memo_text";
            $query = "SELECT $select  FROM drive_shares AS ds JOIN drive_folders AS df ON df.id = ds.drive_folder_id  JOIN drive_files AS d_files ON d_files.drive_folder_id = df.id AND d_files.status='ACTIVE' LEFT JOIN users AS u ON u.mobile = ds.share_from_mobile AND ds.thinapp_id = u.thinapp_id JOIN thinapps AS t ON t.id= df.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id = df.appointment_customer_id LEFT JOIN childrens AS c ON c.id= df.children_id WHERE $condition  group by d_files.id order by d_files.id DESC LIMIT $offset, $limit";
            $list_data=array();
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if($list->num_rows) {
                $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
            }
            $this->set(compact('list_data'));
            $this->render('load_records', 'ajax');
        }else{
            die;
        }
    }

    public function folder_Share_static($folderID=null){
        $folderID = $this->Custom->encodeVariable($folderID);
        $driveShare = $this->DriveShare->find("all",
            array(
                'fields' => array('DriveShare.share_with_mobile','DriveShare.share_from_mobile',),
                'conditions' => array( 'DriveShare.drive_folder_id' => $folderID, ),
                'contain' => false,
                'order' => array('DriveShare.id ASC'),
            )
        );
        $this->set(compact('driveShare'));
    }

    public function file_share_static($fileID=null){
        $fileID = $this->Custom->encodeVariable($fileID);
        $driveShare = $this->DriveShare->find("all",
            array(
                'fields' => array('DriveShare.share_with_mobile','DriveShare.share_from_mobile',),
                'conditions' => array( 'DriveShare.drive_file_id' => $fileID, ),
                'contain' => false,
                'order' => array('DriveShare.id ASC'),
            )
        );
        $this->set(compact('driveShare'));
    }

    public function consent($consent_param=null,$user_type=null){
        if($this->request->is('get')){
            $consent_data =array();
            $user_type = !empty($user_type)?base64_decode($user_type):'';
            $consent_param = explode("##",base64_decode($consent_param));
            if(count($consent_param) == 2){
                $consent_id = $consent_param[0];
                if($user_type!='DOCTOR'){
                    $consent_data = $this->Consent->find("first",array(
                            'conditions' => array( 'Consent.id' => $consent_id),
                            'contain' => false,
                        ));
                    if(!empty($consent_data)){
                        $consent_data = $consent_data['Consent'];
                        $verification_code = Custom::getRandomString(4);
                        $option = array(
                            'username' => $consent_data['receiver_mobile'],
                            'mobile' => $consent_data['receiver_mobile'],
                            'verification' => $verification_code,
                            'thinapp_id' => $consent_data['thinapp_id']
                        );
                        $otp_sent = Custom::send_otp($option);
                        if(($this->Consent->updateAll(array('Consent.verify_otp'=>$verification_code),array('Consent.id'=>$consent_id))) && $otp_sent){
                            $verify_data = 'SHOW_DIV';
                        }else{
                            $verify_data = 'OTP_FAIL';
                        }

                        $verify_data = 'SHOW_DIV';

                    }else{
                        $verify_data = 'BAD_REQUEST';
                    }
                }else{
                    $consent_data = $this->Consent->find("first",array(
                            'conditions' => array( 'Consent.id' => $consent_id),
                            'contain' => array('Thinapp'),
                        ));
                    $app_name = $consent_data['Thinapp']['name'];
                    $consent_data = $consent_data['Consent'];
                    $consent_data['app_name'] = $app_name;
                }

            }else{
                $verify_data = 'BAD_REQUEST';
            }
            $this->set(compact('consent_data','verify_data','user_type'));
        }else{
            exit();
        }

    }

    public function verify(){
        $this->autoRender = false;
        //$consent_id = base64_decode($consent_id);
        $data = $this->request->data;
        $otp= $data['otp'];
        $consent_data = $this->Consent->find("first",array(
                'conditions' => array( 'Consent.id' => base64_decode($data['data_id'])),
                'contain' => array('Thinapp'),
            )
        );
        $app_name = $consent_data['Thinapp']['name'];
        $consent_data = $consent_data['Consent'];
        $consent_data['app_name'] = $app_name;

        if(!empty($consent_data) && $otp == $consent_data['verify_otp'] ){

            if($consent_data['receiver_view_status'] == "SEEN"){
                $response['status'] = 1;
                $response['message'] = "OTP verify successfully";
                $view = new View($this,false);
                $view->layout=false; // if you want to disable layout
                $view->set (compact(array('consent_data','verify_data'))); // set your variables for view here
                $response['html'] = $view->render('verify');
            }else{
                $time = date('Y-m-d H:i');
                $user_data = Custom::get_user_by_mobile($consent_data['thinapp_id'],$consent_data['receiver_mobile']);
                $user_id = !empty($user_data)?$user_data['id']:0;
                $role_id = !empty($user_data)?$user_data['role_id']:1;
                $post=array();
                $login = $this->Session->read('Auth.User.User');
                $post['app_key'] = APP_KEY;
                $post['thin_app_id'] =$consent_data['thinapp_id'];
                $post['user_id'] = $user_id;
                $post['role_id'] = $role_id;
                $post['mobile'] = $consent_data['receiver_mobile'];
                $post['consent_id'] = $consent_data['id'];
                $post['action_type'] = "SEEN";
                $result = json_decode(WebServicesFunction_2_3::update_consent_action($post,true),true);
                if($result['status'] == 1){
                    $response['status'] = 1;
                    $response['message'] = "OTP verify successfully";
                    $view = new View($this,false);
                    $view->layout=false; // if you want to disable layout
                    $view->set (compact(array('consent_data','verify_data'))); // set your variables for view here
                    $response['html'] = $view->render('verify');
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry unable to process";
                }
            }
        }else{
            $response['status'] = 2;
            $response['message'] = "Invalid OTP";
        }
        echo json_encode($response);
    }

	public function refral_user_static($userID=null){
		$userID = base64_decode($userID);
		$mobileToSearch = array();
		$userData = $this->User->find("first",
			array(
				'fields' => array('User.id','User.mobile','User.thinapp_id','User.username'),
				'conditions' => array( 
				'User.id' => $userID,
				//'User.role_id' => 5,
				),
				'contain' => false 
				)
		);
		
		$refrealUserData = $this->DoctorRefferalUser->find("first",
			array(
				'fields' => array('DoctorRefferalUser.refferal_points'),
				'conditions' => array( 'DoctorRefferalUser.user_id' => $userID ),
				'contain' => false 
				)
		);
		
		$refrealPoints = isset($refrealUserData['DoctorRefferalUser']['refferal_points'])?$refrealUserData['DoctorRefferalUser']['refferal_points']:0;
		$parentMobile = $userData['User']['mobile'];
		$parentUsername = $userData['User']['username'];
		
		$parentData = array('mobile'=>$parentMobile,'username'=>$parentUsername,'refreal_points'=>$refrealPoints);
		
		
		$mobileToSearch = array($parentMobile);
		$dataToShow = array();
		for($x=0; $x < 1; $x--)
		{
			
			$data = $this->DoctorRefferal->find('all', array(
				'fields'=>array('DoctorRefferal.*'),
				'conditions'=>array('DoctorRefferal.mobile' => $mobileToSearch,),
				'order' => 'DoctorRefferal.reffered_name ASC',
				'contain'=>false
			));
			
			//pr($data);
			if(sizeof($data) > 0)
			{
				$mobileToSearch = array();
			
				foreach($data as $dataToChunk)
				{
					$dataToShow[] = $dataToChunk['DoctorRefferal'];
					$mobileToSearch[] = $dataToChunk['DoctorRefferal']['reffered_mobile'];
				}
			}
			else
			{
				break;
			}
			
		}
		
		//pr($dataToShow); die;
		$this->set(compact('dataToShow','parentData'));
	}
	
	
	public function rerefer(){
		$this->autoRender = false;
        $data = $this->request->data;
        $rowID= $data['rowID'];
		$time = date('Y-m-d H:i:s');
		
			$data = $this->DoctorRefferal->find('first', array(
				'conditions'=>array('DoctorRefferal.id' => $rowID,),
				'contain'=>false
			));
			
			$data['DoctorRefferal']['status'] = 'REREFERRED';
			$data['DoctorRefferal']['rereferred_datetime'] = $time;
			
		$this->DoctorRefferal->saveAll($data);
		die;
		
	}
	public function get_refer_detail(){
		$this->autoRender = false;
        $data = $this->request->data;
        $rowID= $data['rowID'];
		
			$data = $this->DoctorRefferal->find('first', array(
				'conditions'=>array('DoctorRefferal.id' => $rowID,),
				'contain'=>false
			));
			
			echo $data['DoctorRefferal']['comment'];
		die;
	}


    public function lab_request($id=null){

        if($this->request->is('get')){
            $consent_data =array();
                $id = base64_decode($id);
                $consent_data = $this->LabPharmacyUser->find("first",array(
                        'conditions' => array( 'LabPharmacyUser.id' => $id),
                        'contain' => false,
                    )
                );
                if(!empty($consent_data)){
                    $consent_data = $consent_data['LabPharmacyUser'];
                    $verification_code = Custom::getRandomString(4);
                    $option = array(
                        'username' => $consent_data['name'],
                        'mobile' => $consent_data['mobile'],
                        'verification' => $verification_code,
                        'thinapp_id' => $consent_data['thinapp_id']
                    );
                    $otp_sent = Custom::send_otp($option);
                    if(($this->LabPharmacyUser->updateAll(array('LabPharmacyUser.verification_code'=>$verification_code),array('LabPharmacyUser.id'=>$id))) && $otp_sent){
                        $verify_data = 'SHOW_DIV';
                    }else{
                        $verify_data = 'OTP_FAIL';
                    }

                    $verify_data = 'SHOW_DIV';

                }else{
                    $verify_data = 'BAD_REQUEST';
                }

            $this->set(compact('consent_data','verify_data'));
        }else{
            exit();
        }

    }

    public function verify_lab_request(){
        $this->autoRender = false;
        $data = $this->request->data;
        $id = base64_decode($data['data_id']);
        $otp= $data['otp'];
        $consent_data = $this->LabPharmacyUser->find("first",array(
                'conditions' => array( 'LabPharmacyUser.id' => $id),
                'contain' => array('Thinapp'),
            )
        );
        $app_name = $consent_data['Thinapp']['name'];
        $logo = $consent_data['Thinapp']['logo'];
        $consent_data = $consent_data['LabPharmacyUser'];
        $consent_data['app_name'] = $app_name;
        $consent_data['app_logo'] = $logo;

        if(!empty($consent_data) && $otp == $consent_data['verification_code'] ){

            $connection = ConnectionUtil::getConnection();
            $status = 'YES';
            $sql = "UPDATE lab_pharmacy_users set is_verify =?, modified = ?  where id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $status,  $created, $id);
            if ($stmt->execute()) {
                $response['status'] = 1;
                $response['message'] = "OTP verify successfully";
                $view = new View($this,false);
                $view->layout=false; // if you want to disable layout
                $view->set (compact(array('consent_data','verify_data'))); // set your variables for view here
                $response['html'] = $view->render('verify_lab_request');
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry unable to process";
            }
        }else{
            $response['status'] = 2;
            $response['message'] = "Invalid OTP";
        }
        echo json_encode($response);
    }
	
	public function record($param=null){


        $param = $this->Custom->decodeVariable($param);
        $data = explode("##",$param);
        $response =array();
        $mobile =$foldar_name ='';
        $parameterCount = count($data);
        if(count($data) ==3){
            $type = $data[1];
            $mobile = $data[2];
            $drive_folder_id = ($type=='FOLDER')?$data[0]:0;
            $folder_data = Custom::get_folder_by_id($drive_folder_id);
            if(!empty($folder_data)){
                $thin_app_id = $folder_data['thinapp_id'];
                $folder_name = $folder_data['folder_name'];
            }else{
                die('Invalid URL');
            }

        }
        if($parameterCount==3){
            $select = "df.thinapp_id, df.folder_name, d_files.file_type, d_files.file_path, d_files.file_name,d_files.created,u.username,d_files.listing_type, d_files.memo_text";
             $query = "SELECT $select  FROM  drive_folders AS df  JOIN drive_files AS d_files ON d_files.drive_folder_id = df.id AND d_files.status='ACTIVE' LEFT JOIN users AS u ON u.id = d_files.user_id WHERE df.id = $drive_folder_id group by d_files.id order by d_files.id desc";
           
            $list_data=array();
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if($list->num_rows) {
                $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
            }
            $this->set(compact('list_data','drive_folder_id','thin_app_id','mobile','folder_name'));
            //$this -> render('verify_share');

        }else{
            $response['status'] = 0;
            $response['message'] = "Invalid Url";
        }
        //   echo  json_encode($response); die;

    }
    public function upload_file()
    {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->request->is(array('ajax'))) {
            $upload_file = $this->Custom->uploadFileToAws($_FILES['file']);
            if ($upload_file) {
                $file_name = $_FILES['file']['name'];
                $file_size = $_FILES['file']['size'];
                $file_size = sprintf("%4.2f", $file_size / 1048576);
                $drive_folder_id = base64_decode($_REQUEST['d']);
                $category_id = $_REQUEST['data']['cat_id'];

                $mobile = base64_decode($_REQUEST['m']);
                $thin_app_id = base64_decode($_REQUEST['t']);
                $userData = Custom::get_user_by_mobile($thin_app_id,$mobile);
                if(!empty($userData)){
                    $user_id = $userData['id'];
                    $role_id = $userData['role_id'];
                }else{
                    $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
                    $role_id = 5;
                }
                $data['thin_app_id'] = $thin_app_id;
                $data['user_id'] = $user_id;
                $data['app_key'] = APP_KEY;
                $data['mobile'] = $mobile;
                $data['role_id'] = $role_id;

                $data['file_array'][0]['file_type'] = Custom::getFileType($file_name);
                $data['file_array'][0]['file_name'] = $file_name;
                $data['file_array'][0]['file_path'] = $upload_file;
                $data['file_array'][0]['file_size'] = $file_size;

                $data['listing_type'] = "OTHER";
                $data['memo_text'] = "";
                $data['memo_label'] = "";
                $data['caption'] = "";
                $data['drive_folder_id'] = $drive_folder_id;
                $data['category_id'] = $category_id;
                return WebservicesFunction::add_file($data, true);
            }
        }
        exit();

    }

    public function add_file_notify()
    {
        Custom::send_process_to_background();
        if ($this->request->is(array('ajax'))) {
            $mobile = base64_decode($this->request->data['m']);
            $thin_app_id = base64_decode($this->request->data['t']);
            $userData = Custom::get_user_by_mobile($thin_app_id,$mobile);
            if(!empty($userData)){
                $user_id = $userData['id'];
            }else{
                $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
            }
            $drive_folder_id = base64_decode($this->request->data['f_id']);
            $folder_data = Custom::get_folder_data($drive_folder_id);
            if (!empty($folder_data)) {
                $user_id_list = Custom::get_folder_shared_user_mobile_and_id($drive_folder_id);
                $show_ipd = Custom::check_user_permission($thin_app_id,'SHOW_IPD_CATEGORY_TO_PATIENT');
                if($show_ipd == "NO" ||$show_ipd===false){
                    if(!empty($user_id_list)){
                        $tmp=array();
                        foreach($user_id_list as $key => $value){
                            if($value['is_doctor'] =="YES"){
                                $tmp[]= $value;
                            }
                        }
                        $user_id_list = $tmp;
                    }
                }
                if ($user_id_list) {
                    $username = $mobile;
                    $user_data = Custom::get_user_by_id($user_id);
                    if (!empty($user_data)) {
                        $username = $user_data['username'];
                    }
                    $message = "Hi, New file  added to folder " . $folder_data['folder_name'] . " by " . $username;
                    $option = array(
                        'thinapp_id' => $thin_app_id,
                        'channel_id' => 0,
                        'role' => "USER",
                        'flag' => 'FILE_ADD',
                        'title' => "New file added to folder " . $folder_data['folder_name'],
                        'message' => mb_strimwidth($message, 0, 80, '...'),
                        'description' => mb_strimwidth($message, 0, 100, '...'),
                        'chat_reference' => '',
                        'module_type' => 'DOCUMENT',
                        'module_type_id' => $drive_folder_id,
                        'firebase_reference' => ""
                    );

                    $user_ids = array_column($user_id_list, "share_to_user_id");
                    $user_ids = Custom::search_remove($user_id, $user_ids);
                    Custom::send_notification_by_user_id($option, $user_ids, $thin_app_id);

                    $message = "Hi, New file  added to folder " . $folder_data['folder_name'] . " by " . $username;
                    $mobile_numbers = array_column($user_id_list, "share_with_mobile");
                    $mobile_numbers = Custom::search_remove($mobile, $mobile_numbers);
                    Custom::sendFileShareMessage("FOLDER", $drive_folder_id, $mobile_numbers, $message, $thin_app_id, $user_id);
                }
            }
        }
        exit();
    }


}
