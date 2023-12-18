<?php
App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");
include (APP.DS."Vendor".DS."fpdf".DS."code128.php");


class SuppController extends AppController {

	//public $name = 'Supsp';
	public $helpers = array();
	public $uses = array('Leads','User','AppQueries','AppEnquiryReply','UploadApk','AppEnquiry','Thinapp','CoinRedeem','Gullak','AppSmsStatic','AppSmsRecharge','Gift','RedeemGift','Membership','PaymentTransactions','Payments','Quest','QuestLike','QuestShare','QuestReply','QuestReplyThank','QuestCategory','Channel','SellItem','SellImage','SellWishlist','SellItemCategory','AppUserStatic','CmsDocHealthTipSubCategory','CmsDocDashboard','Contest','ContestMaximumTimeStatus','ContestMultipleChoiceAnswer','ContestMultipleChoiceQuestion','ContestTextResponse','ContestWinner','DoctorRefferal','DoctorRefferalHistory','DoctorRefferalUser','AppEnableFunctionality','AppFunctionalityType');
	
	public $components = array('Custom');
        /*****************************************
        function :- index 
        *****************************************/
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'support_admin_home';

		$this->Auth->allow('verify', 'resend_code');

        /*this code check customer has payment for its app or not*/

        if ($this->Auth->loggedIn()) {
            $user = $this->Session->read('Auth.User');
            $action = $this->request->params['action'];
            $not_validate = array('thinapp_list','logout','load_subscription','load_permission','change_app_permission','view_thinapp','get_edit_thinapp','change_lead_status','update_publish_status','change_thinapp_status','change_thinapp_version','search_thinapp_list');
            $action = str_replace('admin_', '', $action);
            $user_data = Custom::get_user_by_id($user['id']);
            if($user['password'] == $user_data['password']){
                if($action != 'logout' &&  $user['mobile'] != MAIN_SUPPORT_ADMIN_MOBILE && $user['role_id'] == 4  ){
                    if($user_data['status'] != $user['status'] || $user_data['password'] != $user['password']){
                        $this->redirect(array('controller' => 'supp', 'action' => 'logout','admin' => true));
                    }else {
                        if (!in_array($action, $not_validate)) {
                            $this->redirect(array('controller' => 'supp', 'action' => 'thinapp_list', 'admin' => true));
                        }
                    }
                }
            }else{
                $this->Auth->logout();
                $this->redirect("/admin");

            }
        }




        /* check payment end*/


	}

	public function admin_reply_enquiry_message()
	{
		 $this->autoRender = false;
		 $background =array();
        if($this->request->is('ajax')){
            $response = array();
            $login = $this->Session->read('Auth.User');
            $this->request->data['AppEnquiryReply']['user_id'] = $login['id'];
			$this->request->data['AppEnquiryReply']['app_enquiry_id'] = $this->request->data['AppEnquiryReply']['rowID'];
			$this->request->data['AppEnquiryReply']['message'] = $this->request->data['AppEnquiryReply']['reply'];
            if($this->AppEnquiryReply->save($this->request->data))
            {

				$EnquiryData = $this->AppEnquiry->find("first",
					array(
						"conditions" => array("AppEnquiry.id" => $this->request->data['AppEnquiryReply']['app_enquiry_id']),
						"fields" => array('AppEnquiry.phone','AppEnquiry.email'),
						"contain" => false,
					)
				);

				$mobile = $EnquiryData['AppEnquiry']['phone'];
				$message = $this->request->data['AppEnquiryReply']['message'];
				$background['sms']['mobile'] = $mobile;
				$background['sms']['message'] = $message;

				$subject = "MBroadcast replied to your query";
				$to = $EnquiryData['AppEnquiry']['email'];
				$body = $this->request->data['AppEnquiryReply']['message'];
				$background['mail']['to'] = $to;
				$background['mail']['subject'] = $subject;
				$background['mail']['body'] = $body;
                $response['status'] = 1;
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = "something went wrong";
            }

        }else{
            $response['status'] = 0;
            $response['message'] = "something went wrong";

        }

        $response = json_encode($response, true);
        echo $response;
		Custom::send_process_to_background();
		if(!empty($background)){
			if(isset($background['sms'])){
				$mobile = $background['sms']['mobile'];
				$message = $background['sms']['message'];
				Custom::send_single_sms($mobile,$message,1);
			}
			if(isset($background['mail'])){
				$to = $background['mail']['to'];
				$subject = $background['mail']['subject'];
				$body = $background['mail']['body'];
				$this->Custom->sendSimpleEmail($to,$subject,$body);
			}
		}
        exit();
	}

	public function admin_accept_inquiry()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$rowID = $this->request->data['rowID'];
			$rowData = $this->AppEnquiry->find("first",
				array(
					"conditions" => array("AppEnquiry.id" => $rowID),
					"fields" => array('AppEnquiry.id','AppEnquiry.supp_admin_id','AppEnquiry.status',),
					"contain" => false,
					)
			);
			if($rowData['AppEnquiry']['status'] == 'OPEN')
			{
				$rowData['AppEnquiry']['supp_admin_id'] = $login['id'];
				$rowData['AppEnquiry']['status'] = 'INPROGRESS';
				$this->AppEnquiry->id = $rowID;
				if ($this->AppEnquiry->save($rowData))
					{
						$response['status'] = 1;
						$response['rowID'] = $rowID;
						$response['message'] = 'Accepted By '.$login['username'];
						$response['message'] .= '<button type="button" id="closeThis" enquiry-id="'.$rowID.'" class="btn btn-primary btn-xs">Close</button>';
						$response['icon'] = '<a row-id="'.$rowID.'" title="Reply By Email" reply="reply" class="fa fa-reply" href="javascript:void(0)"></a>';
					}
				else
					{
						$response['status'] = 0;
						$response['message'] = "Sorry, could not accept.";
					}
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Request already accepted by another user.";
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_close_inquiry(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$rowID = $this->request->data['rowID'];

				if ( $this->AppEnquiry->updateAll( array('AppEnquiry.status' => "'COMPLETE'"),array('AppEnquiry.id' => $rowID) ) )
				{
					$response['status'] = 1;
					$response['rowID'] = $rowID;
					$response['message'] = 'Closed By '.$login['username'];
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = "Sorry, could not accept.";
				}


			$response = json_encode($response, true);
			echo $response;
			exit();
		}

	}
	
	public function admin_view_inquiry()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$enquiryData = $this->AppEnquiry->find("first",
				array(
					"conditions" => array("AppEnquiry.id" => $rowID),
					"contain" => false,
				)
			);
			$replyData = $this->AppEnquiryReply->find("all",
				array(
					"conditions" => array("AppEnquiryReply.app_enquiry_id" => $rowID),
					"fields" => array('AppEnquiryReply.id','AppEnquiryReply.user_id','AppEnquiryReply.message','Support.username',),
					"contain" => array('Support'),
				)
			);
			$response = "<tr>
			<td width='20%'>Message:<td>
			<td>".$enquiryData['AppEnquiry']['message']."<td>
			</tr>
			<tr>
			<th colspan='4'align='center'>Replies</th>
			</tr>
			<td width='20%'>#<td>
			<td>Message<td>
			</tr>";

			if(sizeof($replyData) > 0)
			{
				$n = 1;
				foreach($replyData as $reply)
				{
					$response .= "<tr>
					<td>".$n++."</td>
					<td colspan='3'>".$reply['AppEnquiryReply']['message']."</td>
					</tr>";
				}
			}
			else
			{
					$response .= "<tr>
					<td colspan='4'>No reply found</td>
					</tr>";
			}

				echo $response; die;
		}
	}

	public function admin_view_thinapp()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$thinappData = $this->Thinapp->find("first",
				array(
					"conditions" => array("Thinapp.id" => $rowID),
					"fields" => array('Thinapp.*','User.username'),
				)
			);
            $address = $thinappData['Thinapp']['address'];
			$list = Custom::get_address_list_drp($rowID);
			if(!empty($list)){
                $address = "<ol style='float:left;padding:0;width:100%'>";
                foreach ($list as $key =>$add){
                    $address .= "<li>".$add."</li>";
                }
                $address .= "</ol>";
            }


			$response = "<tr>
			<td width='20%'>Name:<td>
			<td>".$thinappData['Thinapp']['name']."<td>
			</tr>
			<tr>
			<td width='20%'>App ID:<td>
			<td>".$thinappData['Thinapp']['app_id']."<td>
			</tr>
			<tr>
			<td width='20%'>Firebase Server Key:<td>
			<td>".$thinappData['Thinapp']['firebase_server_key']."<td>
			</tr>
			<tr>
			<td width='20%'>Username:<td>
			<td>".$thinappData['User']['username']."<td>
			</tr>
			<tr>
			<td width='20%'>Email:<td>
			<td>".$thinappData['Thinapp']['email']."<td>
			</tr>
			<tr>
			<td width='20%'>Phone:<td>
			<td>".$thinappData['Thinapp']['phone']."<td>
			</tr>
			<tr>
			<td width='20%'>Address:<td>
			<td>".$address."<td>
			</tr>
			<tr>
			<td width='20%'>Start Date:<td>
			<td>".date("d-M-Y",strtotime($thinappData['Thinapp']['start_date']))."<td>
			</tr>
			<td width='20%'>End Date:<td>
			<td>".date("d-M-Y",strtotime($thinappData['Thinapp']['end_date']))."<td>
			</tr>
			<td width='20%'>Status:<td>
			<td>".$thinappData['Thinapp']['status']."<td>
			</tr>
			<td width='20%'>Created:<td>
			<td>".date("d-M-Y",strtotime($thinappData['Thinapp']['created']))."<td>
			</tr>
			<td width='20%'>Modified:<td>
			<td>".date("d-M-Y",strtotime($thinappData['Thinapp']['modified']))."<td>
			</tr>";

			echo $response; die;
		}
	}


	public function admin_change_thinapp_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$thinappData = $this->Thinapp->find("first",
				array(
					"conditions" => array("Thinapp.id" => $rowID),
					"contain" => false,
				)
			);

			$statusToChange = ($thinappData['Thinapp']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';


			$thinappData['Thinapp']['status'] = $statusToChange;

			if($this->Thinapp->updateAll(array("Thinapp.status"=>"'".$statusToChange."'"),array("Thinapp.id"=>$thinappData['Thinapp']['id'])))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
				
				
							
						$thin_app_id = $rowID;
						$statusArr = $this->Thinapp->find('first', array(
							"conditions" => array(
								"Thinapp.id" => $thin_app_id
							),
							'fields' => array("Thinapp.status"),
							'contain' => false
						));
						
						
									/* work for app functionlary*/
									$app_fun_type_data = $this->AppFunctionalityType->find('list',array(
										"conditions"=>array(
											"AppFunctionalityType.status"=>'Y',
										),
										'contain'=>false,
										'fields'=>array('AppFunctionalityType.id','AppFunctionalityType.label_key')
									));


									/* work for user functionlary*/
									$fun_type_data = $this->UserFunctionalityType->find('list',array(
										"conditions"=>array(
											"UserFunctionalityType.status"=>'Y',
										),
										'contain'=>false,
										'fields'=>array('UserFunctionalityType.id','UserFunctionalityType.label_key')
									));


										/* work for app functionlary*/
										$app_enable_fun = $this->AppEnableFunctionality->find('list',array(
											"conditions"=>array(
												"AppEnableFunctionality.thinapp_id"=>$thin_app_id,
											),
											'contain'=>false,
											'fields'=>array('AppEnableFunctionality.app_functionality_type_id')
										));

										/* work for user functionlary*/
										$enable_fun = $this->UserEnabledFunPermission->find('list',array(
											"conditions"=>array(
												"UserEnabledFunPermission.thinapp_id"=>$thin_app_id,
											),
											'contain'=>false,
											'fields'=>array('UserEnabledFunPermission.user_functionality_type_id','UserEnabledFunPermission.permission')
										));

										$features = array();
										/* work for app functionlary*/
										foreach($app_fun_type_data as $key => $value){
											/* MUST CHANGE ***********************************CHANGE THIS YES PARAMETER TO NO */
											$features[$value]= (in_array($key,$app_enable_fun))?'YES':"NO";
										}

										/* work for user functionlary*/
										foreach($fun_type_data as $key => $value){
											/* MUST CHANGE ***********************************CHANGE THIS YES PARAMETER TO NO */
											$features[$value]= (array_key_exists($key,$enable_fun))?$enable_fun[$key]:"NO";
										}
										$catchArray = array();
										$catchArray['status'] = 1;
											if (!empty($statusArr)) {
													$catchArray['data']['status'] = $statusArr['Thinapp']['status'];														
											}
										$catchArray['data']['features'] = $features;
										$catchArray['message'] = "App features list found";
										$fileName = 'get_app_enabled_functionality_'.$thin_app_id;
                                        WebservicesFunction::deleteJson(array($fileName),'permission');


						
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_change_thinapp_version()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$version = $this->request->data['version'];
			if($this->Thinapp->updateAll(array("Thinapp.version_name"=>"'$version'"),array("Thinapp.id"=>$rowID))){
				WebservicesFunction::deleteJson(array("app_version_".$rowID),'version');
				$response['status'] = 1;
				$response['message'] = "Update Successfully.";
			}else{
				$response['status'] = 0;
				$response['message'] = "Sorry could not changed version";
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

    public function admin_change_thinapp_allowed_doctor_count()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $rowID = $this->request->data['rowID'];
            $version = $this->request->data['version'];
            if($this->Thinapp->updateAll(array("Thinapp.allowed_doctor_count"=>"'$version'"),array("Thinapp.id"=>$rowID))){
                WebservicesFunction::deleteJson(array("app_version_".$rowID),'version');
                $response['status'] = 1;
                $response['message'] = "Update Successfully.";
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry could not changed version";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }



	public function admin_update_sub_alert_status()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $thin_app_id = base64_decode($this->request->data['tid']);
            $alert_status = $this->request->data['alert_status'];
            $subscription_price = $this->request->data['subscription_price'];
            $cron_alert = $this->request->data['cron_alert'];
            $condition = array(
                "Thinapp.subscription_alert_type"=>"'$alert_status'",
                "Thinapp.subscription_price"=>"'$subscription_price'",
                "Thinapp.subscription_alert_via_cron"=>"'$cron_alert'"
            );
            if($this->Thinapp->updateAll($condition,array("Thinapp.id"=>$thin_app_id))){
                $response['status'] = 1;
                $response['message'] = "Setting Update Successfully.";
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry setting could not update";
            }
            echo json_encode($response);

            exit();
        }
    }

	public function admin_update_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$thin_app_id = base64_decode($this->request->data['tid']);
			$start_date = $this->request->data['start_date'];
			$end_date = $this->request->data['end_date'];
			$payment_date = $this->request->data['payment_date'];
			$remark = $this->request->data['remark'];
			$download = $this->request->data['download'];
			$is_published = $this->request->data['published'];
			$send_sms = $this->request->data['send_sms'];
			$amount = $this->request->data['amount'];
			$amount = empty($amount)?0:$amount;
			$start_date = date('Y-m-d',strtotime($start_date));
            $end_date = date('Y-m-d',strtotime($end_date));
            if(!empty($payment_date)){
                $payment_date = date('Y-m-d',strtotime($payment_date));
            }
            $app_data= Custom::getThinAppData($thin_app_id);
            $cron_alert = 'ACTIVE';
            $alert_type = 'WEEKLY';
            if(strtotime($end_date) > strtotime($start_date)){
                if($is_published=="NO"){
                    $condition = array(
                        "Thinapp.start_date"=>"'$start_date'",
                        "Thinapp.subscription_interval"=>"'12 MONTHS'",
                        "Thinapp.end_date"=>"'$end_date'",
                        "Thinapp.free_subscription_count"=>"'$download'",
                        "Thinapp.subscription_start_date"=>"'$start_date'",
                        "Thinapp.subscription_price"=>"'$amount'",
                        "Thinapp.send_subscription_sms_before"=>"15",
                        "Thinapp.is_published"=>"'YES'",
                        "Thinapp.subscription_alert_via_cron"=>"'$cron_alert'",
                        "Thinapp.subscription_alert_type"=>"'$alert_type'"
                    );
                }else{
                    $condition = array(
                        "Thinapp.subscription_interval"=>"'12 MONTHS'",
                        "Thinapp.end_date"=>"'$end_date'",
                        "Thinapp.free_subscription_count"=>"'$download'",
                        "Thinapp.subscription_start_date"=>"'$start_date'",
                        "Thinapp.send_subscription_sms_before"=>"15",
                    	"Thinapp.subscription_price"=>"'$amount'",
                        "Thinapp.subscription_alert_via_cron"=>"'$cron_alert'",
                        "Thinapp.subscription_alert_type"=>"'$alert_type'"
                    );
                }
                if($this->Thinapp->updateAll($condition,array("Thinapp.id"=>$thin_app_id))){
                    $connection = ConnectionUtil::getConnection();
                    $created = Custom::created();
                    $sql = "INSERT INTO thinapp_subscription_history (amount, thinapp_id, start_date, end_date, payment_date,remark,created) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sssssss', $amount,$thin_app_id, $start_date, $end_date, $payment_date, $remark, $created);
                    $result = $stmt->execute();
                    $start_date = date('d-F-Y',strtotime($start_date));
                    $end_date = date('d-F-Y',strtotime($end_date));
                    $app_name =$app_data['name'];
                    if($amount > 0){
                        $message = "We, MEngage Technologies Private Limited hereby thank you and acknowledge the subscription for application of payment of INR $amount Rs/- for the serving period of $start_date to $end_date";
                    }else{
                        $message = "We, MEngage Technologies Private Limited hereby thank you and acknowledge the subscription for application of payment of INR $amount Rs/- for the serving period of $start_date to $end_date";
                    }

                    if($send_sms){
                        $message = Custom::send_single_sms($app_data['phone'],$message,134,false,false);
                    }
                    $response['status'] = 1;
                    $response['message'] = "Update Successfully.";
                    $response['data']['end_date'] = date('d-M-Y',strtotime($end_date));
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry could not changed version";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Subscription end date must be grater than start date";
            }
			$response = json_encode($response);
			echo $response;


			exit();
		}
	}

	public function admin_change_lead_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$status = $this->request->data['status'];
			if($this->Leads->updateAll(array("Leads.status"=>"'$status'"),array("Leads.customer_id"=>$rowID))){

				$response['status'] = 1;
				$response['message'] = "Update Successfully.";
			}else{
				$response['status'] = 0;
				$response['message'] = "Sorry could not changed version";
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

    public function admin_change_category()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $thin_app_id = $this->request->data['rowID'];
            $status = $this->request->data['status'];
            if($this->Thinapp->updateAll(array("Thinapp.category_name"=>"'$status'"),array("Thinapp.id"=>$thin_app_id))){

                $response['status'] = 1;
                $response['message'] = "Update Successfully.";
                WebservicesFunction::deleteJson(array('get_app_enabled_functionality_' .$thin_app_id),'permission');
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry could not changed category";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


	public function admin_get_edit_thinapp()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$thinappData = $this->Thinapp->find("first",
				array(
					"conditions" => array("Thinapp.id" => $rowID),
					"contain" => false,
				)
			);

			if(!empty($thinappData))
			{
				$response['status'] = 1;
				$thinappData['Thinapp']['phone'] = str_replace('+91','',$thinappData['Thinapp']['phone']);
				$response['data'] = $thinappData['Thinapp'];
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_search_inquiry_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "inquiry_list",
				"?" => $pram,
			)
		);
	}

	public function admin_search_skype_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "skype_list",
				"?" => $pram,
			)
		);
	}





	public function admin_search_lead_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "support_list",
				"?" => $pram,
			)
		);
	}

	public function admin_inquiry_list()
	{
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["AppEnquiry.name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["AppEnquiry.email LIKE"] = '%'.$searchData['e'].'%';
		}
		$conditions["AppEnquiry.enquiry_type"] = 'ENQUIRY';



		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppEnquiry.*','Support.username'),
			'contain'=>array('Support'),
			'order' => 'id DESC',
			'limit' => 10,
		);
		$data = $this->paginate('AppEnquiry');
	//	pr($data);die;
		$this->set('data',$data);


	}

	public function admin_skype_list()
	{
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["AppEnquiry.name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["AppEnquiry.email LIKE"] = '%'.$searchData['e'].'%';
		}
		$conditions["AppEnquiry.enquiry_type"] = 'SKYPE';



		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppEnquiry.*','Support.username'),
			'contain'=>array('Support'),
			'order' => 'id DESC',
			'limit' => 10
		);
		$data = $this->paginate('AppEnquiry');
		$this->set('data',$data);

	}


	public function admin_search_contact_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "contact_list",
				"?" => $pram,
			)
		);
	}

	public function admin_contact_list()
	{
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["AppEnquiry.name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["AppEnquiry.email LIKE"] = '%'.$searchData['e'].'%';
		}
		$conditions["AppEnquiry.enquiry_type"] = 'CONTACT';



		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppEnquiry.*','Support.username'),
			'contain'=>array('Support'),
			'order' => 'id DESC',
			'limit' => 10
		);
		$data = $this->paginate('AppEnquiry');
		$this->set('data',$data);

	}

	public function admin_search_thinapp_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "thinapp_list",
				"?" => $pram,
			)
		);
	}

	public function admin_thinapp_list()
	{
		$login = $this->Session->read('Auth.User');
        //$conditions["Thinapp.category_name"] = array('DOCTOR','HOSPITAL','TEMPLE','CLINIC','OTHER');
		$searchData = $this->request->query;

		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["Thinapp.name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["Thinapp.email LIKE"] = '%'.$searchData['e'].'%';
		}



		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('Thinapp.*','User.username','Leads.status','Leads.customer_id','User.verification_code'),
			'contain'=>array('User','Leads'),
			'order' => 'Thinapp.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('Thinapp');

		$this->set('data',$data);

	}

	public function admin_dashboard() {

		$login = $this->Session->read('Auth');
		$this->loadModel('Leads');
		$new_leads = $this->Leads->find('count');
		
		$this->set('new_leads',$new_leads);
		$new_user = $this->Leads->find('count', array('conditions' => array( 'Leads.created BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()')));
		$this->set('new_lead',$new_user);

	}

	public function admin_accept_lead()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$rowID = $this->request->data['rowID'];
			$rowData = $this->Leads->find("first",
				array(
					"conditions" => array("Leads.customer_id" => $rowID),
					"fields" => array('Leads.user_id','Leads.customer_id','Leads.support_admin_id','Leads.status','Leads.app_payment','Leads.app_id'),
					"contain" => false,
				)
			);
			if($rowData['Leads']['status'] == 'NEW')
			{
				//$rowData['Leads']['support_admin_id'] = $login['id'];
				//$rowData['Leads']['status'] = 'INPROCESS';
				//$this->Leads->customer_id = $rowID;



				if ($this->Leads->updateAll(
					array('Leads.support_admin_id' => $login['id'],'Leads.status'=>"'INPROCESS'"),
					array('Leads.customer_id' => $rowID)))
				{
					$response['status'] = 1;
					$response['rowID'] = $rowID;
					$response['message'] = '<button type="button" id="closeThis" lead-id="'.$rowID.'" class="btn btn-primary btn-xs">'.$login['username'].' &nbsp; <i class="fa fa-power-off"></i></button>';

					$response['icon'] = '<div style="display:flex;">';
					$response['icon'] .= '<a title="Reply to customer" row-id ='.$rowID.' class="action_icon btn btn-primary" href="'.$this->webroot.'admin/supp/message/'.$rowID.'"><i class="fa fa-reply"></i></a> &nbsp; <button type="button" title="View lead" row-id ="'.$rowID.'" class="action_icon btn btn-primary view_lead" href="javascript:void(0);"><i class="fa fa-eye"></i></button> &nbsp; <a  title="Pay with customer cheque" id="addPayment" row-id="'.$rowID.'" class="action_icon btn btn-primary add_thin_app" href="javascript:void(0);"><i class="fa fa-money"></i></a>';
					$response['thinAppButton'] = '';
					if($rowData['Leads']['app_payment'] == 1 && $rowData['Leads']['app_id'] == 0)
					{
						$response['icon'] .= ' &nbsp; <a  title="Add thin app" id="addThinApp" lead-id="'.$rowData['Leads']['user_id'].'" row-id="'.$rowData['Leads']['customer_id'].'" class="action_icon btn btn-primary add_thin_app" href="javascript:void(0);"><i class="fa fa-android"></i></a>';
					}
					//$response['icon'] =html_entity_decode($response['icon']);
					$response['icon'] .= '</div>';
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = "Sorry, could not accept.";
				}
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Request already accepted by another user.";
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}

	}

	public function admin_close_lead(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$rowID = $this->request->data['rowID'];
			$rowData = $this->Leads->find("first",
				array(
					"conditions" => array("Leads.customer_id" => $rowID),
					"fields" => array('Leads.customer_id','Leads.support_admin_id','Leads.status',),
					"contain" => false,
				)
			);

			$rowData['Leads']['status'] = 'DONE';
			$this->Leads->customer_id = $rowID;
			if ($this->Leads->save($rowData))
			{
				$response['status'] = 1;
				$response['rowID'] = $rowID;
				$response['message'] = '<lable title="This lead closed by "'.$login['username'].' class="label label-success"> '.$login['username'] .'&nbsp; <i class="fa fa-check"></i></lable>';

			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Sorry, could not accept.";
			}


			$response = json_encode($response, true);
			echo $response;
			exit();
		}

	}

	public function admin_support_list()
	{
		//pr($this->Session->read('Auth.User')); die;
		$conditions = array();
		$order = array();


		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["Leads.org_name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["Leads.cust_email LIKE"] = '%'.$searchData['e'].'%';
		}
		$conditions["User.is_verified"] = 'Y';

		$appQueries = $this->Leads->hasMany['AppQueries'];
		unset($this->Leads->hasMany['AppQueries']);
		$this->paginate = array(
			'conditions' => $conditions,
			'contain'=>array('Support','User'),
			'fields'=>array('Leads.*','Support.username','User.is_verified'),
			'order' =>  array('Leads.customer_id'=>'DESC'),
			'limit'=>WEB_PAGINATION_LIMIT
		);
		$users = $this->paginate('Leads');

		//pr($users); die;
		$this->Leads->hasMany['AppQueries'] = $appQueries;
		$membership = $this->Membership->find('list',array('conditions'=>array('Membership.status'=>'ACTIVE')));
		$this->set('Leads',$users); //pr($users);
		$this->set('membership',$membership);

	}

	public function admin_message($leadID = null)
	{
		$login = $this->Session->read('Auth.User');
		$leadID = base64_decode($leadID);
		$leads = $this->Leads->find("first", array(
			"conditions" => array(
				"Leads.support_admin_id" => $login['id'],
				"Leads.customer_id" => $leadID,
			),
            "contain" => false
		));

		$this->set(compact('quires'));
		if(!empty($leads)){

			if ($this->request->is(array('post', 'put'))) {



				if(isset($this->request->data['AppQuires']['file']['tmp_name']) && !empty($this->request->data['AppQuires']['file']['tmp_name']))
				{
					$file = $this->request->data['AppQuires']['file'];
					$fileTmp = $file['tmp_name'];
					$exploadname = explode('.', $file['name']);
					$ext = end($exploadname);
					$fileName = uniqid().'.'.$ext;
					//echo strtoupper($ext); die;
					if(strtoupper($ext)=="APK" )
					{
						$uploadPath = WWW_ROOT . "uploads".DS.'apk'.DS;
					}else{
						$uploadPath = WWW_ROOT . "uploads".DS.'message'.DS;
					}

					if(move_uploaded_file($fileTmp,$uploadPath.$fileName))
					{
						if(strtoupper($ext)=="APK" )
						{
							$login = $this->Session->read('Auth.User');
							$this->UploadApk->create();
							$inData = array();
							$inData['customer_lead_id']=$leads['Leads']['customer_id'];
							$inData['support_admin_id']=$login['id'];
							$inData['name']=$fileName;

							$lastVersion = $this->UploadApk->find("first", array(
								"fields"=>array(
									"max(UploadApk.version) as lastVersion",
								),
								"conditions" => array(
										"UploadApk.customer_lead_id" => $leads['Leads']['customer_id'],
									),
								"contain"=>false
								));
							if(isset($lastVersion[0]['lastVersion']))
							{
								$version = ($lastVersion[0]['lastVersion'] + 0.1);
							}
							else
							{
								$version = 0.1;
							}

							$inData['version']=$version;
							$inData['status']='INPROCESS';
							$upData = $this->UploadApk->save($inData);
							$post['upload_apk_id'] = $upData['UploadApk']['id'];
						}else{
							$post['attachment'] = $fileName;
						}

					}
				}

				$post['customer_lead_id']=    $leads['Leads']['customer_id'];
				$post['message']=    $this->request->data['AppQuires']['message'];
				$post['sender_id']=  $login['id'];
				$post['support_admin_id']=  $login['id'];
				$post['app_id']=    $leads['Leads']['app_id'];
				$post['reciver_id']=  $leads['Leads']['user_id'];
				if($this->AppQueries->save($post)){
					$this->Session->setFlash(__('Your message post successfully.'), 'default', array(), 'success');
					unset($this->request->data);
				}else{
					$this->Session->setFlash(__('Sorry your message could not be post.'), 'default', array(), 'errror');
				}


			}


			$Queries = $this->AppQueries->find("all", array(
				"conditions" => array(
					"AppQueries.customer_lead_id" => $leads['Leads']['customer_id'],
				),
				"fields"=>array(
					"AppQueries.*","Sender.username","Sender.id","UploadApk.*",
				),
				"contain"=>array("Sender","UploadApk"),
				"order"=> "AppQueries.id DESC"
			));

			$quires = $Queries;

			$this->set(compact('quires'));


		}else{

			$this->Session->setFlash(__("Can't find lead."), 'default', array(), 'info');
			$this->redirect(array('controller' => 'supp', 'action' => 'dashboard'));
		}

	}

	public function admin_view_lead()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$rowData = $this->Leads->find("first",
				array(
					"conditions" => array("Leads.customer_id" => $rowID),
					"contain" => false,
				)
			);

			if(!empty($rowData))
			{
				$response['status'] = '1';
				$html = "<tr><td>Mobile:</td><td>".$rowData['Leads']['mobile']."</td></tr>";
				$html .= "<tr><td>Org Name:</td><td>".$rowData['Leads']['org_name']."</td></tr>";
				$html .= "<tr><td>Email:</td><td>".$rowData['Leads']['cust_email']."</td></tr>";
		 //       $html .= "<tr><td>Org Type:</td><td>".$rowData['Leads']['org_type']."</td></tr>";
				$html .= "<tr><td>Comments:</td><td>".$rowData['Leads']['comments']."</td></tr>";
				$html .= "<tr><td>Created:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Leads']['created']))."</td></tr>";
				$response['html'] = $html;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}




	}

	public function admin_add_thinapp()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {

			$response = array();
			$datasource = $this->Thinapp->getDataSource();
			try {
				$datasource->begin();

				$login = $this->Session->read('Auth.User');
				$dataToSave = $this->request->data;
				$dataToSave['app_id'] = str_replace(" ","_",strtoupper($dataToSave['name']));
				$dataToSave['firebase_server_key'] = FIREBASE_SERVER_KEY;;
				$dataToSave['end_date'] = date('Y-m-d', strtotime('+1 years',strtotime($dataToSave['start_date'])));
				$dataToSave['phone'] = $dataToSave['phone'];
				$customerID = $dataToSave['customer_id'];
				if ($dataInserted = $this->Thinapp->save($dataToSave))
				{
					$last_inser_id = $this->Thinapp->getLastInsertId();
					$number = $this->Leads->find('first',array(
						'conditions'=>array(
							"Leads.customer_id"=>$customerID
						),
						"contain"=>false,
						'fields'=>array("Leads.mobile","Leads.user_id")
					));
					if(!empty($number)){

						$result_lead = $this->Leads->updateAll(array("app_id"=>$last_inser_id),array("Leads.customer_id"=>$customerID));
						$result_user = $this->User->updateAll(array(
							"User.thinapp_id"=>$last_inser_id
						),array("User.id"=>$dataToSave['user_id']) );
						$user_id =$number['Leads']['user_id'];
						$inData = array();
						$inData['thinapp_id'] = $last_inser_id;
						$inData['user_id'] = $user_id;
						$inData['total_promotional_sms'] = TOTAL_PROMOTIONAL_SMS;
						$inData['total_transactional_sms'] = TOTAL_TRANSACTIONAL_SMS;
						$result_gullak = $this->Custom->updateCoins('REGISTER',$user_id,$user_id,0,$last_inser_id,0);
						if($this->AppSmsStatic->save($inData) && $result_gullak && $result_lead && $result_user ){
							$response['status'] = 1;
							$datasource->commit();
						}
					}else{
						$response['status'] = 0;
					}
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = "Sorry, could not save.";
				}
				$response = json_encode($response, true);
				echo $response;
				exit();

			} catch(Exception $e) {


				//echo $e->getMessage();die;
				$datasource->rollback();
				$response['status'] = 0;
				$response['message'] = "Sorry, could not save.";
				$response = json_encode($response, true);
				echo $response;
				exit();
			}
		}

	}

	public function admin_edit_thinapp(){
						$this->autoRender = false;
						if($this->request->is('ajax')) {
							$response = array();
							$this->request->data['phone'] = '+91'.$this->request->data['phone'];
							if($this->Thinapp->save($this->request->data))
							{
								$response['status'] = 1;
							}
							else
							{
								$response['status'] = 0;
								$response['message'] = 'Sorry, Could not edit.';
							}
							$response = json_encode($response, true);
							echo $response;
							exit();
						}
	}


	public function admin_add_payment(){
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$response = array();
			$login = $this->Session->read('Auth.User');
			$transaction = array();
			$user = $this->Leads->find("first", array("conditions" => array(
				"Leads.customer_id" => $this->request->data['customer_id']
			)));
			$transaction['amount'] = $this->request->data['amount'];
			$transaction['transaction_id'] = $this->request->data['cheque_number'];
			$transaction['app_id'] = $user['Leads']['customer_id'];
			$transaction['amount'] = $this->request->data['amount'];
			$transaction['membership_id'] =$this->request->data['membership_id'];
			$transaction['ip_address'] = '';
			$transaction['support_admin_id'] = $login['id'];
			$transaction['payment_mode'] = 'CHEQUE';
			$this->PaymentTransactions->save($transaction);
			$last_id = $this->PaymentTransactions->getLastInsertId();
			$datasource = $this->Payments->getDataSource();
			try {

				$datasource->begin();

				$this->Payments->updateAll(array("membership_status"=>"'"."INACTIVE"."'"),array("membership_status"=>"ACTIVE",'Payments.user_id'=>$user['Leads']['user_id']));
				$postData['transaction_id'] =$transaction['transaction_id'];
				$postData['membership_id'] =$transaction['membership_id'];
				$postData['amount'] =$transaction['amount'];
				$postData['user_id'] =$user['Leads']['user_id'];
				$postData['app_id'] =$user['Leads']['customer_id'];
				$start = date('Y-m-d H:i:s');
				$postData['membership_start'] = $start;
				$one_year = strtotime(date('Y-m-d H:i:s', strtotime('+1 years',strtotime($start))));
				$one_min = date("Y-m-d H:i:s", strtotime("-1 minutes", $one_year));
				$postData['membership_expire'] =$one_min;

				if($this->Payments->save($postData)){
					$transaction['payment_status'] = 'SUCCESS';
					$transaction['payment_id'] = $this->Payments->getLastInsertId();
					$this->PaymentTransactions->id = $last_id;
					$pay_tar = $this->PaymentTransactions->save($transaction);
					/* this is for customer lead */
					$pay_lead = $this->Leads->updateAll(array("app_payment"=>1),array("customer_id"=>$user['Leads']['customer_id']));
					if($pay_lead && $pay_tar){
						$response['status'] =1;
						$datasource->commit();
						
					}else{
						$datasource->rollback();
						$response['status'] =0;
						$response['message'] = 'Sorry,failed to update payment';
						$transaction['payment_status'] = 'FAIL';
						$this->PaymentTransactions->id = $last_id;
						$this->PaymentTransactions->save($transaction);
					}

				}else{
					$response['status'] =0;
					$response['message'] = 'Sorry,failed to update payment';
					$this->PaymentTransactions->id = $last_id;
					$this->PaymentTransactions->save($transaction);
					}


			} catch(Exception $e) {



				$datasource->rollback();
				$response['status'] =0;
				$response['message'] = 'Sorry,failed to update payment';
				$this->PaymentTransactions->id = $last_id;
				$this->PaymentTransactions->save($transaction);

			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		 }
	}


	public function admin_search_redeem_list(){

		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['username']))
		{
			$pram['u'] = $reqData['username'];
		}
		if(!empty($reqData['appName']))
		{
			$pram['a'] = $reqData['appName'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "redeem_request_list",
				"?" => $pram,
			)
		);

	}

	public function admin_redeem_request_list(){
		$login = $this->Session->read('Auth.User');
		$conditions = array();

		$searchData = $this->request->query;
		if(isset($searchData['u']) && !empty($searchData['u']))
		{
			$this->request->data['Search']['username'] = $searchData['u'];
			$conditions["RedeemBy.username LIKE"] = '%'.$searchData['u'].'%';
		}
		if(isset($searchData['a']) && !empty($searchData['a']))
		{
			$this->request->data['Search']['appName'] = $searchData['a'];
			$conditions["Thinapp.name LIKE"] = '%'.$searchData['a'].'%';
		}

		$conditions["RedeemBy.thinapp_id"] = $login['thinapp_id'];

		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('CoinRedeem.*','RedeemBy.username','RedeemBy.id','RedeemBy.username','RedeemBy.username','RedeemBy.mobile','SupportAdmin.id','SupportAdmin.username','Gullak.id','Gullak.total_coins','Thinapp.name'),
			'contain'=>array('Gullak','SupportAdmin','RedeemBy','Thinapp'),
			'order' => 'CoinRedeem.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('CoinRedeem');
	//	pr($data); die;
		$this->set('data',$data);
	}

	public function admin_view_redeem_request()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$redeemID = $this->request->data['redeemID'];
			$redeemData = $this->CoinRedeem->find("first",
				array(
					'fields'=>array('CoinRedeem.*','RedeemBy.username','RedeemBy.id','RedeemBy.username','RedeemBy.username','RedeemBy.mobile','SupportAdmin.id','SupportAdmin.username','Gullak.id','Gullak.total_coins','Thinapp.name'),
					'contain'=>array('Gullak','SupportAdmin','RedeemBy','Thinapp'),
					'conditions'=>array('CoinRedeem.id'=>$redeemID),
				)
			);

			$response = "<tr>
			<td width='20%'>Username:<td>
			<td>".$redeemData['RedeemBy']['username']."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Mobile:<td>
			<td>".$redeemData['RedeemBy']['mobile']."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Thinapp:<td>
			<td>".$redeemData['Thinapp']['name']."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Gullak:<td>
			<td>".$redeemData['Gullak']['total_coins']."&nbsp;<i class='fa fa-money'></i><td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Coin To Redeem:<td>
			<td>".$redeemData['CoinRedeem']['coins']."&nbsp;<i class='fa fa-money'></i><td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Amount:<td>
			<td>".$redeemData['CoinRedeem']['amount']."&nbsp;<i class='fa fa-inr'></i><td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Coin Rate:<td>
			<td>".$redeemData['CoinRedeem']['coin_rate']."/100<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Status:<td>
			<td>".$redeemData['CoinRedeem']['status']."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Created:<td>
			<td>".date("d-M-Y H:i:s",strtotime($redeemData['CoinRedeem']['created']))."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Status:<td>
			<td>".$redeemData['CoinRedeem']['status']."<td>
			</tr>";
			$response .= "<tr>
			<td width='20%'>Support Admin:<td>
			<td>".$redeemData['SupportAdmin']['username']."<td>
			</tr>";
			echo $response; die;
		}
	}

	public function admin_accept_redeem_request()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$redeemID = $this->request->data['redeemID'];
			$rowData = $this->CoinRedeem->find("first",
				array(
					"conditions" => array("CoinRedeem.id" => $redeemID),
					"contain" => false,
				)
			);
			if($rowData['CoinRedeem']['status'] == 'OPEN')
			{
				$rowData['CoinRedeem']['supp_admin_id'] = $login['id'];
				$rowData['CoinRedeem']['status'] = 'INPROGRESS';
				$this->CoinRedeem->id = $redeemID;
				if ($this->CoinRedeem->save($rowData))
				{
					$response['status'] = 1;
					$response['message'] = 'Accepted By '.$login['username'];
					$response['message'] .= '<button type="button" id="closeThis" redeem-id="'.$redeemID.'" class="btn btn-primary btn-xs">Close</button>';
					$response['message'] .= '<button type="button" id="cancelThis" redeem-id="'.$redeemID.'" class="btn btn-primary btn-xs">Cancel</button>';
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = "Sorry, could not accept.";
				}
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Request already accepted by another user.";
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_close_redeem_request()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$redeemID = $this->request->data['redeemID'];
			$rowData = $this->CoinRedeem->find("first",
				array(
					"conditions" => array("CoinRedeem.id" => $redeemID),
					"contain" => array('Gullak'),
				)
			);
			if($rowData['CoinRedeem']['status'] == 'INPROGRESS')
			{
				if($rowData['Gullak']['total_coins'] >= $rowData['CoinRedeem']['coins'] )
				{
					$rowData['CoinRedeem']['supp_admin_id'] = $login['id'];
					$rowData['CoinRedeem']['status'] = 'CLOSE';
					$this->CoinRedeem->id = $redeemID;
					if ($this->CoinRedeem->save($rowData))
					{
						$response['status'] = 1;
						$response['message'] = 'Closed By '.$login['username'];
					}
					else
					{
						$response['status'] = 0;
						$response['message'] = "Sorry, could not close.";
					}
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = "Sorry, User have not enough credit.";
				}

			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Sorry, could not close.";
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_cancel_redeem_request()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$login = $this->Session->read('Auth.User');
			$redeemID = $this->request->data['redeemID'];
			$rowData = $this->CoinRedeem->find("first",
				array(
					"conditions" => array("CoinRedeem.id" => $redeemID),
					"contain" => array('Gullak'),
				)
			);
			if($rowData['CoinRedeem']['status'] == 'INPROGRESS')
			{
				$rowData['Gullak']['total_coins'] = ($rowData['Gullak']['total_coins']+$rowData['CoinRedeem']['coins']);

					$rowData['CoinRedeem']['supp_admin_id'] = $login['id'];
					$rowData['CoinRedeem']['status'] = 'CANCELLED';
					$this->CoinRedeem->id = $redeemID;
					if ($this->CoinRedeem->saveAll($rowData))
					{
						$response['status'] = 1;
						$response['message'] = 'Cancelled By '.$login['username'];
					}
					else
					{
						$response['status'] = 0;
						$response['message'] = "Sorry, could not close.";
					}
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = "Sorry, could not cancel.";
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}






	public function admin_search_user_sms_list(){

		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['username']))
		{
			$pram['u'] = $reqData['username'];
		}
		if(!empty($reqData['appName']))
		{
			$pram['a'] = $reqData['appName'];
		}
		if(!empty($reqData['smsCount']))
		{
			$pram['s'] = $reqData['smsCount'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "user_sms_list",
				"?" => $pram,
			)
		);

	}

	public function admin_user_sms_list()
	{
		 $conditions = array();
		$searchData = $this->request->query;
		if(isset($searchData['u']) && !empty($searchData['u']))
		{
			$this->request->data['Search']['username'] = $searchData['u'];
			$conditions["User.username LIKE"] = '%'.$searchData['u'].'%';
		}
		if(isset($searchData['a']) && !empty($searchData['a']))
		{
			$this->request->data['Search']['appName'] = $searchData['a'];
			$conditions["Thinapp.name LIKE"] = '%'.$searchData['a'].'%';
		}
		if(isset($searchData['s']) && !empty($searchData['s']))
		{
			$this->request->data['Search']['smsCount'] = $searchData['s'];
			$conditions['AND']= array("(`AppSmsStatic`.`total_transactional_sms` ".$searchData['s']." )");

		}

		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppSmsStatic.*','User.username','Thinapp.id','Thinapp.name'),
			'contain'=>array('Thinapp','User'),
			'order' => 'AppSmsStatic.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('AppSmsStatic');

		$this->set('data',$data);
	}

	public function admin_search_sms_transition(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['username']))
		{
			$pram['u'] = $reqData['username'];
		}
		if(!empty($reqData['appName']))
		{
			$pram['a'] = $reqData['appName'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "sms_transition",
				"?" => $pram,
			)
		);
	}

	public function admin_sms_transition(){

		$conditions = array();
		$searchData = $this->request->query;
		if(isset($searchData['u']) && !empty($searchData['u']))
		{
			$this->request->data['Search']['username'] = $searchData['u'];
			$conditions["User.username LIKE"] = '%'.$searchData['u'].'%';
		}
		if(isset($searchData['a']) && !empty($searchData['a']))
		{
			$this->request->data['Search']['appName'] = $searchData['a'];
			$conditions["Thinapp.name LIKE"] = '%'.$searchData['a'].'%';
		}

		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppSmsRecharge.*','User.username','Thinapp.id','Thinapp.name','SupportAdmin.username'),
			'contain'=>array('Thinapp','User','SupportAdmin'),
			'order' => 'AppSmsRecharge.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('AppSmsRecharge');
		$thinAppList = $this->Thinapp->find('list',array('order'=>array('Thinapp.name'=>'asc')));
		$this->set(compact('data','thinAppList'));
	}

	public function admin_add_sms(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$login = $this->Session->read('Auth.User');
			$dataToSave = $this->request->data['AppSmsRecharge'];
			$post['app_key'] = APP_KEY;
			$post['user_id'] =$login['id'];
			$post['thin_app_id'] =$dataToSave['thinapp_id'];
			$post['mobile'] =$login['mobile'];
			$post['total_price'] =$dataToSave['total_price'];
			$post['total_sms'] =$dataToSave['total_sms'];
			$post['total_storage'] =@$dataToSave['total_storage'];
			$post['support_admin_id'] =$login['id'];
			$post['recharge_by'] ='SUPPORT_ADMIN';
			$post['transaction_status'] ='NO_TRANSACTION';
			$post['recharge_for'] =$dataToSave['recharge_for'];
			$post['transaction_id'] =0;
			return WebServicesFunction_2_3::recharge_sms($post);
		}

	}





	public function admin_search_gift_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['gift_name']))
		{
			$pram['g'] = $reqData['gift_name'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "gift_list",
				"?" => $pram,
			)
		);
	}

	public function admin_gift_list()
	{
		$conditions = array();
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		if(isset($searchData['g']) && !empty($searchData['g']))
		{
			$this->request->data['Search']['gift_name'] = $searchData['g'];
			$conditions["Gift.gift_name LIKE"] = '%'.$searchData['g'].'%';
		}

		$conditions["Gift.thinapp_id"] = $login['thinapp_id'];

		$this->paginate = array(
			"conditions" => $conditions,
			'contain'=>false,
			'order' => 'Gift.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('Gift');
	//	pr($data); die;
		$this->set('data',$data);
	}

	public function admin_change_gift_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->Gift->find("first",
				array(
					"conditions" => array("Gift.id" => $rowID),
					"contain" => false,
				)
			);

			$statusToChange = ($giftData['Gift']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';


			$giftData['Gift']['status'] = $statusToChange;

			if($this->Gift->save($giftData['Gift']))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_gift(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->Gift->find("first",
				array(
					"conditions" => array("Gift.id" => $rowID),
					"contain" => false,
				)
			);


			$response = "<tr>
				<td width='100%' colspan='2' style='text-align: center;'>
				<img src='".$giftData['Gift']['image']."' style='width: 200px;'>
				</td>
			</tr>
			<tr>
				<td width='20%'>Gift Name:</td>
				<td>".$giftData['Gift']['gift_name']."</td>
			</tr>
			<tr>
				<td width='20%'>Description:</td>
				<td>".$giftData['Gift']['gift_description']."</td>
			</tr>
			<tr>
				<td width='20%'>Points:</td>
				<td>".$giftData['Gift']['points']."</td>
			</tr>
			<tr>
				<td width='20%'>End Time:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Gift']['end_datetime']))."</td>
			</tr>
			<tr>
				<td width='20%'>Status:</td>
				<td>".$giftData['Gift']['status']."</td>
			</tr>
			<tr>
				<td width='20%'>Quantity:</td>
				<td>".$giftData['Gift']['quantity']."</td>
			</tr>
			<tr>
				<td width='20%'>Total Redeems:</td>
				<td>".$giftData['Gift']['redeem_count']."</td>
			</tr>
			<tr>
				<td width='20%'>Remaining:</td>
				<td>".($giftData['Gift']['quantity'] - $giftData['Gift']['redeem_count'])."</td>
			</tr>
			<tr>
				<td width='20%'>Created:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Gift']['created']))."</td>
			</tr>
			<tr>
				<td width='20%'>Modified:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Gift']['modified']))."</td>
			</tr>";

			echo $response; die;


		}
	}

	public function admin_add_gift(){

		if(isset($this->request->data['Gift'])) {

			$dataToSave = $this->request->data['Gift'];
			$image = $this->request->data['Gift']['image'];

			$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
			if (in_array($image['type'], $mimeAarray)) {
				if ($url = $this->Custom->uploadFileToAws($image)) {

					$dataToSave['image'] = $url;
					$dataToSave['end_datetime'] = $dataToSave['end_date'].' '.$dataToSave['end_time'];
					$login = $this->Session->read('Auth.User');
					$dataToSave['thinapp_id'] = $login['thinapp_id'];
					$dataToSave['user_id'] = $login['id'];

					if($this->Gift->save($dataToSave))
					{
						$this->Session->setFlash(__("Gift added successfully."), 'default', array(), 'success');

						$this->redirect(array('controller' => 'supp', 'action' => 'admin_gift_list','admin' => true));

					}
					else
					{
						$this->Session->setFlash(__("Sorry, Gift wasn't added."), 'default', array(), 'error');
					}


				}
				else
				{
					$this->Session->setFlash(__("Sorry, can't upload image."), 'default', array(), 'error');
				}

			}
			else
			{
				$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
			}
		}
	}

	public function admin_edit_gift($giftID=0){

		$giftID = base64_decode($giftID);

		if($this->request->is(array('post','put'))) {

			$dataToSave = $this->request->data['Gift'];
			$image = $this->request->data['Gift']['image'];

			if($dataToSave['redeem_count'] > $dataToSave['quantity'])
			{
				$this->Session->setFlash(__("Sorry, Quantity can not be less then total redeems."), 'default', array(), 'error');
			}
			else if(!empty($image['tmp_name']))
			{

				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray)) {
					if ($url = $this->Custom->uploadFileToAws($image)) {

						$dataToSave['id'] = $giftID;
						$dataToSave['image'] = $url;
						$dataToSave['end_datetime'] = $dataToSave['end_date'].' '.$dataToSave['end_time'];
						$login = $this->Session->read('Auth.User');
						$dataToSave['thinapp_id'] = $login['thinapp_id'];
						$dataToSave['user_id'] = $login['id'];

						if($this->Gift->save($dataToSave))
						{
							$this->Session->setFlash(__("Gift edited successfully."), 'default', array(), 'success');

							$this->redirect(array('controller' => 'supp', 'action' => 'admin_gift_list','admin' => true));

						}
						else
						{
							$this->Session->setFlash(__("Sorry, Gift wasn't edited."), 'default', array(), 'error');
						}


					}
					else
					{
						$this->Session->setFlash(__("Sorry, can't upload image."), 'default', array(), 'error');
					}

				}
				else
				{
					$this->Session->setFlash(__("Please upload valid image."), 'default', array(), 'error');
				}

			}
			else
			{
				$dataToSave['id'] = $giftID;
				$dataToSave['end_datetime'] = $dataToSave['end_date'].' '.$dataToSave['end_time'];
				$login = $this->Session->read('Auth.User');
				$dataToSave['thinapp_id'] = $login['thinapp_id'];
				$dataToSave['user_id'] = $login['id'];
				unset($dataToSave['image']);

				if($this->Gift->save($dataToSave))
				{
					$this->Session->setFlash(__("Gift edited successfully."), 'default', array(), 'success');

					$this->redirect(array('controller' => 'supp', 'action' => 'admin_gift_list','admin' => true));

				}
				else
				{
					$this->Session->setFlash(__("Sorry, Gift wasn't edited."), 'default', array(), 'error');
				}
			}
		}

		$this->request->data = $this->Gift->findById($giftID);
		$endTimeArr = explode(' ',$this->request->data['Gift']['end_datetime']);
		$this->request->data['Gift']['end_date'] = $endTimeArr[0];
		$this->request->data['Gift']['end_time'] = $endTimeArr[1];

	}

	public function admin_search_gift_redeem_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['gift_name']))
		{
			$pram['g'] = $reqData['gift_name'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "gift_redeem_list",
				"?" => $pram,
			)
		);
	}

	public function admin_gift_redeem_list(){
		$conditions = array();
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		if(isset($searchData['g']) && !empty($searchData['g']))
		{
			$this->request->data['Search']['gift_name'] = $searchData['g'];
			$conditions["Gift.gift_name LIKE"] = '%'.$searchData['g'].'%';
		}
		$conditions["Gift.thinapp_id"] = $login['thinapp_id'];
		$this->paginate = array(
			"conditions" => $conditions,
			'contain'=>array('Gift','User'),
			'order' => 'Gift.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('RedeemGift');
		$this->set('data',$data);
	}

	public function admin_change_gift_redeem_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->RedeemGift->find("first",
				array(
					"conditions" => array("RedeemGift.id" => $rowID),
					"contain" => false,
				)
			);

			$statusToChange = ($giftData['RedeemGift']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';


			$giftData['RedeemGift']['status'] = $statusToChange;
			if($this->RedeemGift->save($giftData['RedeemGift']))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_gift_redeem(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->RedeemGift->find("first",
				array(
					"conditions" => array("RedeemGift.id" => $rowID),
					"contain" => array('Gift','User'),
				)
			);


			$response = "<tr>
				<td width='20%'>User Mobile:</td>
				<td>".$giftData['User']['mobile']."</td>
			</tr>
			<tr>
				<td width='20%'>Gift name:</td>
				<td>".$giftData['Gift']['gift_name']."</td>
			</tr>
			<tr>
				<td width='20%'>Status:</td>
				<td>".$giftData['RedeemGift']['status']."</td>
			</tr>
			<tr>
				<td width='20%'>Type:</td>
				<td>".$giftData['RedeemGift']['type']."</td>
			</tr>";


			if($giftData['RedeemGift']['type'] == 'GIFT')
			{
				$response .= "<tr>
				<td width='20%'>Gift mobile:</td>
				<td>".$giftData['RedeemGift']['gift_mobile']."</td>
			</tr>
			<tr>
				<td width='20%'>Gift email:</td>
				<td>".$giftData['RedeemGift']['gift_email']."</td>
			</tr>";
			}



			$response .= "<tr>
				<td width='20%'>Created:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['RedeemGift']['created']))."</td>
			</tr>
			<tr>
				<td width='20%'>Modified:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['RedeemGift']['modified']))."</td>
			</tr>";

			echo $response; die;


		}
	}





	/*****QUEST START HERE*****/

	public function admin_search_quest(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['quest_category']))
		{
			$pram['c'] = $reqData['quest_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "quest",
				"?" => $pram,
			)
		);
	}

	public function admin_quest(){
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['quest_category'] = $searchData['c'];
			$conditions["Quest.quest_category_id"] = $searchData['c'];
		}
		$conditions["Quest.user_id"] = $login['id'];
		$conditions["Quest.type"] = 'QUEST';

		$this->paginate = array(
			'fields'=>array('Quest.*','QuestCategory.*'),
			'conditions'=>$conditions,
			'contain'=>array( 'QuestCategory' ),
			'order'=> 'Quest.id DESC',
			'limit'=>10
		);
		$quest = $this->paginate('Quest');

		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));

		$this->set(compact('quest','questCategory'));
	}

	public function admin_change_quest_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$questData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.status','Quest.id'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => false,
				)
			);
			$statusToChange = ($questData['Quest']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$questData['Quest']['status'] = $statusToChange;
			if($this->Quest->save($questData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_quest(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$rowData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.*','User.mobile','Thinapp.name','QuestCategory.name','Channel.channel_name'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => array('User','Thinapp','QuestCategory','Channel'),
				)
			);

			if(!empty($rowData))
			{
				$response['status'] = '1';
				$html = "<tr><td>Question:</td><td>".$rowData['Quest']['question']."</td></tr>";
				if(!empty($rowData['Quest']['image']))
				{
					$html .= "<tr><td>Image:</td><td><img src='".$rowData['Quest']['image']."' style='width:150px;'> </td></tr>";
				}
				$html .= "<tr><td>Category:</td><td>".$rowData['QuestCategory']['name']."</td></tr>";
				$html .= "<tr><td>Post As Anonymous:</td><td>".$rowData['Quest']['post_as_anonymous']."</td></tr>";
				$html .= "<tr><td>Total Likes:</td><td>".$rowData['Quest']['like_count']."</td></tr>";
				$html .= "<tr><td>Total Shares:</td><td>".$rowData['Quest']['share_count']."</td></tr>";
				$html .= "<tr><td>Share On:</td><td>".$rowData['Quest']['share_on']."</td></tr>";
				if($rowData['Quest']['channel_id'] > 0)
				{
					$html .= "<tr><td>Share Channel:</td><td>".$rowData['Channel']['channel_name']."</td></tr>";
				}
				$html .= "<tr><td>Show On Mbroadcast:</td><td>".$rowData['Quest']['show_on_mbroadcast']."</td></tr>";
				$html .= "<tr><td>Enable Chat:</td><td>".$rowData['Quest']['enable_chat']."</td></tr>";
				if($rowData['Quest']['show_on_mbroadcast'] == 'YES')
				{
					$html .= "<tr><td>Mbroadcast Publish Status:</td><td>".$rowData['Quest']['mbroadcast_publish_status']."</td></tr>";
				}

				if($rowData['Quest']['share_on'] == 'QUEST_FACTORY')
				{
					$html .= "<tr><td>Factory Publish Status:</td><td>".$rowData['Quest']['factory_publish_status']."</td></tr>";
				}
				$html .= "<tr><td>User:</td><td>".$rowData['User']['mobile']."</td></tr>";
				$html .= "<tr><td>Thin App:</td><td>".$rowData['Thinapp']['name']."</td></tr>";
				$html .= "<tr><td>Status:</td><td>".$rowData['Quest']['status']."</td></tr>";
				$html .= "<tr><td>Created:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Quest']['created']))."</td></tr>";
				$response['html'] = $html;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_quest_result(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$rowData = $this->Quest->find('first',array(
				'conditions'=>array('Quest.id'=>$questID),
				'contain' => array('QuestReply'=>array('User')),
			));

			$dataToSend = array();
			$dataToSend['Quest']['question'] = $rowData['Quest']['question'];
			$dataToSend['Quest']['like_count'] = $rowData['Quest']['like_count'];
			$dataToSend['Quest']['share_count'] = $rowData['Quest']['share_count'];
			$dataToSend['QuestReply'] = array();
			foreach($rowData['QuestReply'] as $key => $value)
			{
				$dataToSend['QuestReply'][$key]['id'] = base64_encode($value['id']);
				$dataToSend['QuestReply'][$key]['message'] = $value['message'];
				$dataToSend['QuestReply'][$key]['status'] = $value['status'];
				$dataToSend['QuestReply'][$key]['thank_count'] = $value['thank_count'];
				$dataToSend['QuestReply'][$key]['User'] = $value['User']['mobile'];
			}


			if(!empty($dataToSend))
			{
				$response['status'] = '1';

				$response['data'] = $dataToSend;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_change_quest_reply_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questReplyID = base64_decode($this->request->data['questReplyID']);
			$questReplyData = $this->QuestReply->find("first",
				array(
					"fields"=>array('QuestReply.status','QuestReply.id'),
					"conditions" => array("QuestReply.id" => $questReplyID),
					"contain" => false,
				)
			);
			$statusToChange = ($questReplyData['QuestReply']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$questReplyData['QuestReply']['status'] = $statusToChange;
			if($this->QuestReply->save($questReplyData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_add_quest(){

		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$dataToSave = $this->request->data;
			$dataToSave['Quest']['user_id'] = $login['id'];
			$dataToSave['Quest']['thinapp_id'] = $login['thinapp_id'];
			$image = $dataToSave['Quest']['image'];
			unset($dataToSave['Quest']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToSave['Quest']['image'] = $url;
						if($this->Quest->saveAll($dataToSave))
						{
							$this->Session->setFlash(__('Quest added successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'supp', 'action' => 'quest'));
						}
						else
						{
							$this->Session->setFlash(__('Sorry, Quest could not added.'), 'default', array(), 'error');
						}
					}
					else
					{
						$this->Session->setFlash(__('Could not upload image.'), 'default', array(), 'error');
					}
				}
				else
				{
					$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
				}
			}
			else
			{
				if($this->Quest->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Quest added successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'quest'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, Quest could not added.'), 'default', array(), 'error');
				}
			}

		}
		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('questCategory','channels'));
	}

	public function admin_edit_quest($id=null){
		$id=base64_decode($id);
		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$dataToSave = $this->request->data;
			$dataToSave['Quest']['user_id'] = $login['id'];
			$dataToSave['Quest']['id'] = $id;
			$dataToSave['Quest']['thinapp_id'] = $login['thinapp_id'];
			$image = $dataToSave['Quest']['image'];
			unset($dataToSave['Quest']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToSave['Quest']['image'] = $url;
						if($this->Quest->saveAll($dataToSave))
						{
							$this->Session->setFlash(__('Quest updated successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'supp', 'action' => 'quest'));
						}
						else
						{
							$this->Session->setFlash(__('Sorry, Quest could not updated.'), 'default', array(), 'error');
						}
					}
					else
					{
						$this->Session->setFlash(__('Could not upload image.'), 'default', array(), 'error');
					}
				}
				else
				{
					$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
				}
			}
			else
			{
				if($this->Quest->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Quest updated successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'quest'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, Quest could not updated.'), 'default', array(), 'error');
				}
			}
		}
		else
		{
			$this->request->data = $this->Quest->findById($id);
			if(empty($this->request->data))
			{
				$this->Session->setFlash(__('Sorry, Quest was not found.'), 'default', array(), 'error');
				$this->redirect(array('controller' => 'supp', 'action' => 'quest'));
				return false;
			}
		}
		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('questCategory','channels'));
	}

	public function admin_search_permit_quest(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['quest_category']))
		{
			$pram['c'] = $reqData['quest_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "permit_quest",
				"?" => $pram,
			)
		);
	}

	public function admin_permit_quest(){
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		$conditions = array();
		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['quest_category'] = $searchData['c'];
			$conditions["Quest.quest_category_id"] = $searchData['c'];
		}
		$conditions["Quest.show_on_mbroadcast"] = 'YES';
		$conditions["Quest.type"] = 'QUEST';
		$this->paginate = array(
			'fields'=>array('Quest.*','QuestCategory.*'),
			'conditions'=>$conditions,
			'contain'=>array( 'QuestCategory' ),
			'order'=> 'Quest.id DESC',
			'limit'=>10
		);
		$quest = $this->paginate('Quest');

		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));

		$this->set(compact('quest','questCategory'));
	}

	public function admin_change_quest_mbroadcast_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$questData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.mbroadcast_publish_status','Quest.id'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => false,
				)
			);

			$statusToChange = ($questData['Quest']['mbroadcast_publish_status'] != 'APPROVED')?'APPROVED':'PENDING';
			$questData['Quest']['mbroadcast_publish_status'] = $statusToChange;
			if($this->Quest->save($questData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	/*****QUEST END HERE*****/

	/*****BUY, BORROW & RENT START HERE*****/

	public function admin_search_buy(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['quest_category']))
		{
			$pram['c'] = $reqData['quest_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "buy",
				"?" => $pram,
			)
		);
	}

	public function admin_buy(){
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['quest_category'] = $searchData['c'];
			$conditions["Quest.quest_category_id"] = $searchData['c'];
		}
		$conditions["Quest.user_id"] = $login['id'];
		$conditions["Quest.type !="] = 'QUEST';
		$this->paginate = array(
			'fields'=>array('Quest.*','QuestCategory.*'),
			'conditions'=>$conditions,
			'contain'=>array( 'QuestCategory' ),
			'order'=> 'Quest.id DESC',
			'limit'=>10
		);
		$quest = $this->paginate('Quest');

		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));

		$this->set(compact('quest','questCategory'));
	}

	public function admin_change_buy_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$questData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.status','Quest.id'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => false,
				)
			);
			$statusToChange = ($questData['Quest']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$questData['Quest']['status'] = $statusToChange;
			if($this->Quest->save($questData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_buy(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$rowData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.*','User.mobile','Thinapp.name','QuestCategory.name','Channel.channel_name'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => array('User','Thinapp','QuestCategory','Channel'),
				)
			);

			if(!empty($rowData))
			{
				$response['status'] = '1';
				$html = "<tr><td>Question:</td><td>".$rowData['Quest']['question']."</td></tr>";
				if(!empty($rowData['Quest']['image']))
				{
					$html .= "<tr><td>Image:</td><td><img src='".$rowData['Quest']['image']."' style='width:150px;'> </td></tr>";
				}
				$html .= "<tr><td>Category:</td><td>".$rowData['QuestCategory']['name']."</td></tr>";
				$html .= "<tr><td>Post As Anonymous:</td><td>".$rowData['Quest']['post_as_anonymous']."</td></tr>";
				$html .= "<tr><td>Total Likes:</td><td>".$rowData['Quest']['like_count']."</td></tr>";
				$html .= "<tr><td>Total Shares:</td><td>".$rowData['Quest']['share_count']."</td></tr>";
				$html .= "<tr><td>Share On:</td><td>".$rowData['Quest']['share_on']."</td></tr>";
				if($rowData['Quest']['channel_id'] > 0)
				{
					$html .= "<tr><td>Share Channel:</td><td>".$rowData['Channel']['channel_name']."</td></tr>";
				}
				$html .= "<tr><td>Show On Mbroadcast:</td><td>".$rowData['Quest']['show_on_mbroadcast']."</td></tr>";
				$html .= "<tr><td>Enable Chat:</td><td>".$rowData['Quest']['enable_chat']."</td></tr>";
				if($rowData['Quest']['show_on_mbroadcast'] == 'YES')
				{
					$html .= "<tr><td>Mbroadcast Publish Status:</td><td>".$rowData['Quest']['mbroadcast_publish_status']."</td></tr>";
				}

				if($rowData['Quest']['share_on'] == 'QUEST_FACTORY')
				{
					$html .= "<tr><td>Factory Publish Status:</td><td>".$rowData['Quest']['factory_publish_status']."</td></tr>";
				}
				$html .= "<tr><td>User:</td><td>".$rowData['User']['mobile']."</td></tr>";
				$html .= "<tr><td>Thin App:</td><td>".$rowData['Thinapp']['name']."</td></tr>";
				$html .= "<tr><td>Status:</td><td>".$rowData['Quest']['status']."</td></tr>";
				$html .= "<tr><td>Created:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Quest']['created']))."</td></tr>";
				$response['html'] = $html;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_buy_result(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$rowData = $this->Quest->find('first',array(
				'conditions'=>array('Quest.id'=>$questID),
				'contain' => array('QuestReply'=>array('User')),
			));

			$dataToSend = array();
			$dataToSend['Quest']['question'] = $rowData['Quest']['question'];
			$dataToSend['Quest']['like_count'] = $rowData['Quest']['like_count'];
			$dataToSend['Quest']['share_count'] = $rowData['Quest']['share_count'];
			$dataToSend['QuestReply'] = array();
			foreach($rowData['QuestReply'] as $key => $value)
			{
				$dataToSend['QuestReply'][$key]['id'] = base64_encode($value['id']);
				$dataToSend['QuestReply'][$key]['message'] = $value['message'];
				$dataToSend['QuestReply'][$key]['status'] = $value['status'];
				$dataToSend['QuestReply'][$key]['thank_count'] = $value['thank_count'];
				$dataToSend['QuestReply'][$key]['User'] = $value['User']['mobile'];
			}


			if(!empty($dataToSend))
			{
				$response['status'] = '1';

				$response['data'] = $dataToSend;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_change_buy_reply_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questReplyID = base64_decode($this->request->data['questReplyID']);
			$questReplyData = $this->QuestReply->find("first",
				array(
					"fields"=>array('QuestReply.status','QuestReply.id'),
					"conditions" => array("QuestReply.id" => $questReplyID),
					"contain" => false,
				)
			);
			$statusToChange = ($questReplyData['QuestReply']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$questReplyData['QuestReply']['status'] = $statusToChange;
			if($this->QuestReply->save($questReplyData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_add_buy(){

		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$dataToSave = $this->request->data;
			$dataToSave['Quest']['user_id'] = $login['id'];
			$dataToSave['Quest']['thinapp_id'] = $login['thinapp_id'];
			$image = $dataToSave['Quest']['image'];
			unset($dataToSave['Quest']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToSave['Quest']['image'] = $url;
						if($this->Quest->saveAll($dataToSave))
						{
							$this->Session->setFlash(__('Added successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'supp', 'action' => 'buy'));
						}
						else
						{
							$this->Session->setFlash(__('Sorry,could not be added.'), 'default', array(), 'error');
						}
					}
					else
					{
						$this->Session->setFlash(__('Could not upload image.'), 'default', array(), 'error');
					}
				}
				else
				{
					$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
				}
			}
			else
			{
				if($this->Quest->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Added successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'buy'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, could not be added.'), 'default', array(), 'error');
				}
			}

		}
		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('questCategory','channels'));
	}

	public function admin_edit_buy($id=null){
		$id=base64_decode($id);
		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$dataToSave = $this->request->data;
			$dataToSave['Quest']['user_id'] = $login['id'];
			$dataToSave['Quest']['id'] = $id;
			$dataToSave['Quest']['thinapp_id'] = $login['thinapp_id'];
			$image = $dataToSave['Quest']['image'];
			unset($dataToSave['Quest']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToSave['Quest']['image'] = $url;
						if($this->Quest->saveAll($dataToSave))
						{
							$this->Session->setFlash(__('Quest updated successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'supp', 'action' => 'buy'));
						}
						else
						{
							$this->Session->setFlash(__('Sorry, Quest could not updated.'), 'default', array(), 'error');
						}
					}
					else
					{
						$this->Session->setFlash(__('Could not upload image.'), 'default', array(), 'error');
					}
				}
				else
				{
					$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
				}
			}
			else
			{
				if($this->Quest->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Quest updated successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'buy'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, Quest could not updated.'), 'default', array(), 'error');
				}
			}
		}
		else
		{
			$this->request->data = $this->Quest->findById($id);
			if(empty($this->request->data))
			{
				$this->Session->setFlash(__('Sorry, Quest was not found.'), 'default', array(), 'error');
				$this->redirect(array('controller' => 'admin', 'action' => 'quest'));
				return false;
			}
		}
		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('questCategory','channels'));
	}

	public function admin_search_permit_buy(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['quest_category']))
		{
			$pram['c'] = $reqData['quest_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "permit_buy",
				"?" => $pram,
			)
		);
	}

	public function admin_permit_buy(){
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		$conditions = array();
		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['quest_category'] = $searchData['c'];
			$conditions["Quest.quest_category_id"] = $searchData['c'];
		}
		$conditions["Quest.show_on_mbroadcast"] = 'YES';
		$conditions["Quest.type !="] = 'QUEST';
		$this->paginate = array(
			'fields'=>array('Quest.*','QuestCategory.*'),
			'conditions'=>$conditions,
			'contain'=>array( 'QuestCategory' ),
			'order'=> 'Quest.id DESC',
			'limit'=>10
		);
		$quest = $this->paginate('Quest');

		$questCategory = $this->QuestCategory->find('list',array('conditions'=>array('QuestCategory.status' => 'ACTIVE')));

		$this->set(compact('quest','questCategory'));
	}

	public function admin_change_buy_mbroadcast_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$questID = $this->request->data['questID'];
			$questData = $this->Quest->find("first",
				array(
					"fields"=>array('Quest.mbroadcast_publish_status','Quest.id'),
					"conditions" => array("Quest.id" => $questID),
					"contain" => false,
				)
			);

			$statusToChange = ($questData['Quest']['mbroadcast_publish_status'] != 'APPROVED')?'APPROVED':'PENDING';
			$questData['Quest']['mbroadcast_publish_status'] = $statusToChange;
			if($this->Quest->save($questData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	/*****BUY, BORROW & RENT END HERE*****/


	/********SELL START HERE********/

	public function admin_search_sell(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['sell_category']))
		{
			$pram['s'] = $reqData['sell_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "sell",
				"?" => $pram,
			)
		);
	}

	public function admin_sell(){
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['s']) && !empty($searchData['s']))
		{
			$this->request->data['Search']['sell_category'] = $searchData['s'];
			$conditions["SellItem.sell_item_category_id"] = $searchData['s'];
		}

		$conditions["SellItem.thinapp_id"] = $login['thinapp_id'];

		$this->paginate = array(
			'fields'=>array('SellItem.id','SellItem.item_name','SellItem.price','SellItem.status','User.mobile','SellItemCategory.name',),
			'conditions'=>$conditions,
			'contain'=>array( 'User','SellItemCategory', ),
			'order'=> 'SellItem.id DESC',
			'limit'=>10
		);
		$sellItems = $this->paginate('SellItem');
		$sellItemCategory = $this->SellItemCategory->find('list',array('conditions'=>array('SellItemCategory.status' => 'ACTIVE')));
		$this->set(compact('sellItems','sellItemCategory'));
	}

	public function admin_change_sell_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$sellID = $this->request->data['sellID'];
			$sellItemData = $this->SellItem->find("first",
				array(
					"fields"=>array('SellItem.status','SellItem.id'),
					"conditions" => array("SellItem.id" => $sellID),
					"contain" => false,
				)
			);
			$statusToChange = ($sellItemData['SellItem']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$sellItemData['SellItem']['status'] = $statusToChange;
			if($this->SellItem->save($sellItemData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_sell(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$sellID = $this->request->data['sellID'];
			$rowData = $this->SellItem->find("first",
				array(
					"fields"=>array('SellItem.*','User.mobile','Thinapp.name','SellItemCategory.name','Channel.channel_name'),
					"conditions" => array("SellItem.id" => $sellID),
					"contain" => array('User','Thinapp','SellItemCategory','Channel'),
				)
			);
			if(!empty($rowData))
			{
				$response['status'] = '1';
				$html = "<tr><td>Item Name:</td><td>".$rowData['SellItem']['item_name']."</td></tr>";
				$html .= "<tr><td>Category:</td><td>".$rowData['SellItemCategory']['name']."</td></tr>";
				$html .= "<tr><td>Share On:</td><td>".$rowData['SellItem']['share_on']."</td></tr>";
				if($rowData['SellItem']['channel_id'] > 0)
				{
					$html .= "<tr><td>Share Channel:</td><td>".$rowData['Channel']['channel_name']."</td></tr>";
				}
				$html .= "<tr><td>Show On Mbroadcast:</td><td>".$rowData['SellItem']['show_on_mbroadcast']."</td></tr>";
				$html .= "<tr><td>Enable Chat:</td><td>".$rowData['SellItem']['enable_chat']."</td></tr>";
				if($rowData['SellItem']['show_on_mbroadcast'] == 'YES')
				{
					$html .= "<tr><td>Mbroadcast Publish Status:</td><td>".$rowData['SellItem']['mbroadcast_publish_status']."</td></tr>";
				}

				if($rowData['SellItem']['share_on'] == 'QUEST_FACTORY')
				{
					$html .= "<tr><td>Factory Publish Status:</td><td>".$rowData['SellItem']['factory_publish_status']."</td></tr>";
				}
				$html .= "<tr><td>User:</td><td>".$rowData['User']['mobile']."</td></tr>";
				$html .= "<tr><td>Thin App:</td><td>".$rowData['Thinapp']['name']."</td></tr>";
				$html .= "<tr><td>Status:</td><td>".$rowData['SellItem']['status']."</td></tr>";
				$html .= "<tr><td>Created:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['SellItem']['created']))."</td></tr>";
				$response['html'] = $html;
			}
			else
			{
				$response['status'] = '0';
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_sell_images($sellID=null){
		$sellID=base64_decode($sellID);
		$sellImages = $this->SellItem->find('first',array(
			'fields'=>array('SellItem.id','SellItem.item_name'),
			'conditions'=>array('SellItem.id'=>$sellID),
			'contain'=>array('SellImage')
		));
		if(empty($sellImages))
		{
			$this->Session->setFlash(__('Sorry, Sell item was not found.'), 'default', array(), 'error');
			$this->redirect(array('controller' => 'supp', 'action' => 'sell'));
			return false;
		}
		$this->set(compact('sellImages'));

	}

	public function admin_change_sell_image_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$sellImageID = $this->request->data['sellImageID'];
			$sellImageData = $this->SellImage->find("first",
				array(
					"fields"=>array('SellImage.status','SellImage.id'),
					"conditions" => array("SellImage.id" => $sellImageID),
					"contain" => false,
				)
			);
			$statusToChange = ($sellImageData['SellImage']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$sellImageData['SellImage']['status'] = $statusToChange;
			if($this->SellImage->save($sellImageData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_search_permit_sell(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['sell_category']))
		{
			$pram['s'] = $reqData['sell_category'];
		}
		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "permit_sell",
				"?" => $pram,
			)
		);
	}

	public function admin_permit_sell(){
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		$conditions = array();
		if(isset($searchData['s']) && !empty($searchData['s']))
		{
			$this->request->data['Search']['sell_category'] = $searchData['s'];
			$conditions["SellItem.sell_item_category_id"] = $searchData['s'];
		}
		$conditions["SellItem.show_on_mbroadcast"] = 'YES';
		$this->paginate = array(
			'fields'=>array('SellItem.id','SellItem.item_name','SellItem.mbroadcast_publish_status','SellItem.price','SellItem.status','User.mobile','SellItemCategory.name',),
			'conditions'=>$conditions,
			'contain'=>array( 'User','SellItemCategory', ),
			'order'=> 'SellItem.id DESC',
			'limit'=>10
		);

		$sellItems = $this->paginate('SellItem');
		$sellItemCategory = $this->SellItemCategory->find('list',array('conditions'=>array('SellItemCategory.status' => 'ACTIVE')));
		$this->set(compact('sellItems','sellItemCategory'));

	}

	public function admin_change_sell_mbroadcast_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$sellID = $this->request->data['sellID'];
			$sellData = $this->SellItem->find("first",
				array(
					"fields"=>array('SellItem.mbroadcast_publish_status','SellItem.id'),
					"conditions" => array("SellItem.id" => $sellID),
					"contain" => false,
				)
			);

			$statusToChange = ($sellData['SellItem']['mbroadcast_publish_status'] != 'APPROVED')?'APPROVED':'PENDING';
			$sellData['SellItem']['mbroadcast_publish_status'] = $statusToChange;
			if($this->SellItem->save($sellData))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_permit_sell_images($sellID=null){
		$sellID=base64_decode($sellID);
		$sellImages = $this->SellItem->find('first',array(
			'fields'=>array('SellItem.id','SellItem.item_name'),
			'conditions'=>array('SellItem.id'=>$sellID),
			'contain'=>array('SellImage')
		));
		if(empty($sellImages))
		{
			$this->Session->setFlash(__('Sorry, Sell item was not found.'), 'default', array(), 'error');
			$this->redirect(array('controller' => 'supp', 'action' => 'sell'));
			return false;
		}
		$this->set(compact('sellImages'));
	}

	/********SELL END HERE********/





	public function admin_support_reject( $id = null)
	{
		if($this->request->data)

			$this->Leads->customer_id = $id;
		if ($this->Leads->updateAll(array("status"=>3),array("Leads.customer_id"=>$id)))
		{
			$this->Session->setFlash(__('Leads has been Rejected successfully.'),'default', array(),'done');
			$this->redirect(array('controller' => 'usermanager', 'action' => 'support_view','admin'=>true));
		}
		$this->redirect(array('controller' => 'usermanager', 'action' => 'support_view','admin'=>true));
	}
	public function admin_change_role( $id = null)
	{
		if($this->request->data)

			$this->User->id = $id;
		if ($this->User->updateAll(array("User.role_id"=>3),array("User.id"=>$id)))
		{
			$this->Session->setFlash(__('Role has been Changed into ThinAppAdmin successfully.'),'default', array(),'done');
			$this->redirect(array('controller' => 'usermanager', 'action' => 'support_view','admin'=>true));
		}
		$this->redirect(array('controller' => 'usermanager', 'action' => 'support_view','admin'=>true));
	}
	public function admin_support_reply()
	{

		$this->autoRender = false;
		if($this->request->is('ajax')){
			$mobile = $this->request->data['mob'];
			$message = $this->request->data['msg'];
			$sms_resp = $this->Custom->send_message_system($mobile, $message);
			$res = json_decode($sms_resp);

			if($res->response->status=='success'){
				return true;
			}else{
				return false;
			}

		}else{

			exit();
		}


	}
	public function admin_edit_lead($id = null)
	{
		$this->loadModel('Leads');

		$conditions = array('customer_id'=>$id);



		$this->paginate = array(
			'conditions' => $conditions,
			//'order' => $order,
			//'limit' => 11,
			// 'ORDER' => 'id' ,DESC,
		);
		$users = $this->paginate('Leads'); //pr($users);
		$this->set('Leads',$users); //pr($users);
		$this->set('Lead', $this->Leads->find('list', array( 'fields' => array('customer_id', 'status'))));
		$this->set('Lead_org', $this->Leads->find('list', array( 'fields' => array('customer_id', 'org_type'))));
		$organization = $this->OrganizationType->find('all', array('fields' => array('org_type',)));
		//set products data and pass to the view
		$this->set('organization_type', $organization);
		$this->set('organization_type', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 'ACTIVE'), 'fields' => array('org_type_id', 'org_type'))));


	}
	public function admin_update_lead( $id = null)
	{
		if($this->request->data)

			$this->Leads->customer_id = $id;
		if ($this->Leads->updateAll(array("Leads.customer_id"=>$id)))
		{
			$this->Session->setFlash(__('Leads has been Rejected successfully.'),'default', array(),'done');
			$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));
		}
		$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));
	}
	public function admin_support_delete( $id = null)
	{
		if($this->request->data)
		{
			//echo '<pre>'; print_r($this->request->data); die;
			$data = $this->request->data['deleteall'];
			for($x=0;$x<count($data); $x++)
			{
				$this->Leads->customer_id = $data[$x];
				$this->Leads->delete();
			}
			$this->Session->setFlash(__('Leads has been deleted successfully.'),'default', array(),'done');
			$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));
		}
		$this->Leads->customer_id = $id;
		if ($this->Leads->delete()) {
			$this->Session->setFlash(__('Leads has been deleted successfully.'),'default', array(),'done');
			$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));
		}
		$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));
	}
	public function verify()
	{
  		$this->layout = 'support_verify';
		$response = array();
		if ($this->request->data) {

			$code = $this->request->data['Verify']['code'];
			$m_code = $this->request->data['Verify']['m_code'];
			if ($code != '') {
				$userdata = $this->User->find("first", array("conditions" => array("User.mob_vf_code" => $m_code, "User.verification_code" => $code)));
				if (!empty($userdata)) {
					$this->User->id = $userdata['User']['id'];
					if ($this->User->saveField('is_verified', 'Y')) {
						$response['status'] = 1;
						$response['message'] = "Verification successful";
						$response['org'] = $userdata['User']['org_unique_url'];
						echo json_encode($response);
						exit;
						//$this->Session->setFlash(__('Verification successfull.'),'default', array(),'front_success');
					} else {
						$response['status'] = 0;
						$response['message'] = "Verification Falied";
						echo json_encode($response);
						exit;
					}
				} else {
					$response['status'] = 0;
					$response['message'] = "Wrong Verification code";
					echo json_encode($response);
					exit;
				}
			} else {
				$response['status'] = 0;
				$response['message'] = 'Please Enter Verification code.';
				echo json_encode($response);
				exit;
			}
		}

	}
	public function admin_accept($id=null)
	{
		$id = base64_decode($id);
		$this->autoRender = false;
		$login = $this->Session->read('Auth.User');
		$userdata = $this->Leads->find("first", array("conditions" => array("Leads.customer_id" =>$id)));
		if($userdata['Leads']['support_admin_id']==0){

			if($this->Leads->save(array('customer_id'=>$id,'support_admin_id'=>$login['id'],'status'=>'INPROCESS'))){
				$this->Session->setFlash(__('Leads has been accepted successfully.'),'default', array(),'front_success');
			}else{
				$this->Session->setFlash(__('Sorry lead could not accepted.'),'default', array(),'front_error');
			}
		}else{
				$this->Session->setFlash(__('Sorry lead has been accepted by someone'),'default', array(),'front_error');
		}

		$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));

	}

	public function admin_load_permission()
	{
		if($this->request->is('ajax')) {
			$response = array();
			$thin_app_id = $this->request->data['rowID'];
			
			/* work for app functionlary*/
			$app_fun_type_data = $this->AppFunctionalityType->find('list',array(
				"conditions"=>array(
					"AppFunctionalityType.status"=>'Y',
				),
				'contain'=>false,
				'fields'=>array('AppFunctionalityType.id','AppFunctionalityType.label_value')
			));

			if(!empty($app_fun_type_data)){
				$app_enable_fun = $this->AppEnableFunctionality->find('list',array(
					"conditions"=>array(
						"AppEnableFunctionality.thinapp_id"=>$thin_app_id,
					),
					'contain'=>false,
					'fields'=>array('AppEnableFunctionality.id','AppEnableFunctionality.app_functionality_type_id')
				));
				$features = array();


                $thinappData = $this->Thinapp->find('first',array(
                    "conditions"=>array("Thinapp.id"=>$thin_app_id,),
                    'contain'=>false,
                    'fields'=>array('Thinapp.id','Thinapp.booking_convenience_fee','Thinapp.booking_doctor_share_percentage','Thinapp.booking_payment_getway_fee_percentage','Thinapp.booking_convenience_fee_restrict_ivr','Thinapp.booking_convenience_fee_terms_condition')
                ));




				//pr($app_enable_fun);die;
				foreach($app_fun_type_data as $key => $value){
					if((in_array($key,$app_enable_fun))){
						$features[] = array(
							'label'=>$value,
							'enabled_fun_id'=>array_search ($key, $app_enable_fun),
							'app_function_type_id'=>$key,
							'status'=>'ACTIVE',
							'thin_app_id'=>$thin_app_id
						);
					}else{
						$features[] = array(
							'label'=>$value,
							'enabled_fun_id'=>0,
							'app_function_type_id'=>$key,
							'status'=>'INACTIVE',
							'thin_app_id'=>$thin_app_id
						);
					}
					//$features[]= (array_key_exists($key,$app_enable_fun))?'YES':"NO";
				}
				//pr($features);die;
				$this->set(compact('features','thinappData'));

			}else{
				exit();
			}

			
			
			
		}
	}


	public function admin_load_subscription()
	{
		if($this->request->is('ajax')) {
			$response = array();
			$thin_app_id = $this->request->data['rowID'];
			$thin_app_data = $this->Thinapp->find('first',array(
				"conditions"=>array(
					"Thinapp.id"=>$thin_app_id,
				),
				'contain'=>false
			));

            $query = "select * from thinapp_subscription_history  where  thinapp_id = $thin_app_id order by created desc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $history=array();
            if ($service_message_list->num_rows) {
                $history = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
        
        	 $query = "select id, name,staff_type,mobile,thinapp_id from appointment_staffs where status='ACTIVE' and thinapp_id = $thin_app_id order by created desc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $staff_list=array();
            if ($service_message_list->num_rows) {
                $staff_list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
        	
			$this->set(compact('thin_app_data','history','staff_list'));
		}else{
			exit();
		}
	}

	public function admin_change_app_permission()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$enable_fun_type_id = base64_decode($this->request->data['data_fun_id']);
			$fun_type_id = base64_decode($this->request->data['fun_type_id']);
			$thin_app_id = base64_decode($this->request->data['data_app_id']);
			$is_enable_func = $this->AppEnableFunctionality->find("first",
				array(
					"conditions" => array(
						"AppEnableFunctionality.id" => $enable_fun_type_id
					),
					"contain" => false,
				)
			);
			$response=array();
			if(!empty($is_enable_func)){


				$is_del = $this->AppEnableFunctionality->delete(array('AppEnableFunctionality.id' => $enable_fun_type_id));
				$user_enable_fun_perm = $this->UserEnabledFunPermission->deleteAll(array(
					'UserEnabledFunPermission.app_enable_functionality_id' => $enable_fun_type_id,
					'UserEnabledFunPermission.thinapp_id' => $thin_app_id
				));
				if($is_del){
					$response['status']=1;
					$response['html_string']='<button data-fun-id="'.base64_encode(0).'" data-app-id="'.base64_encode($thin_app_id).'" fun-type-id="'.base64_encode($fun_type_id).'" type="button" class="action_btn btn btn-warning btn-xs" >INACTIVE</button>';
					WebservicesFunction::deleteJson(array('get_app_enabled_functionality_' .$thin_app_id),'permission');
				}else{
					$response['status']=0;
				}

			}else{
				$this->AppEnableFunctionality->create();
				$this->AppEnableFunctionality->set('app_functionality_type_id', $fun_type_id);
				$this->AppEnableFunctionality->set('thinapp_id', $thin_app_id);
				if ($this->AppEnableFunctionality->save()) {
					$permission_id = $this->AppEnableFunctionality->getLastInsertId();
					$response['status']=1;
					$response['html_string']='<button data-fun-id="'.base64_encode($permission_id).'" data-app-id="'.base64_encode($thin_app_id).'" fun-type-id="'.base64_encode($fun_type_id).'" type="button" class="action_btn btn btn-success btn-xs" >ACTIVE</button>';
					WebservicesFunction::deleteJson(array('get_app_enabled_functionality_' .$thin_app_id),'permission');
				}else{
					$response['status']=0;
				}
			}
			echo json_encode($response);
			exit();

		}
	}




	public function admin_delete($id=null)
	{
		$id = base64_decode($id);
		$this->autoRender = false;
		$login = $this->Session->read('Auth.User');
		$userdata = $this->Leads->find("first", array("conditions" => array("Leads.customer_id" =>$id,'support_admin_id'=>$login['id'])));
		if(!empty($userdata)){
			if($this->Leads->save(array('customer_id'=>$id,'status'=>'REJECTED'))){
				$this->Session->setFlash(__('Leads has been rejected successfully.'),'default', array(),'front_success');
			}else{
				$this->Session->setFlash(__('Sorry lead could not rejected.'),'default', array(),'front_error');
			}
		}
		$this->redirect(array('controller' => 'supp', 'action' => 'support_list','admin'=>true));

	}



	public function admin_search_static_query_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['email']))
		{
			$pram['e'] = $reqData['email'];
		}
		if(!empty($reqData['department_from']))
		{
			$pram['d'] = $reqData['department_from'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "static_query_list",
				"?" => $pram,
			)
		);
	}

	public function admin_static_query_list()
	{
		$login = $this->Session->read('Auth.User');


		$category = unserialize(STATIC_CATEGORY);
		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["AppEnquiry.name LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["AppEnquiry.email LIKE"] = '%'.$searchData['e'].'%';
		}
		if(isset($searchData['d']) && !empty($searchData['d']))
		{
			$this->request->data['Search']['department_from'] = $searchData['d'];
			$conditions["AppEnquiry.department_from"] = '%'.$searchData['d'].'%';
		}

		$conditions["AppEnquiry.enquiry_type"] = $category;



		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('AppEnquiry.*','Support.username'),
			'contain'=>array('Support'),
			'order' => 'id DESC',
			'limit' => 10
		);
		$data = $this->paginate('AppEnquiry');
		$this->set('data',$data);

	}




	public function admin_logout()
	{
		$this->Auth->logout();
		$this->redirect("/admin");
	}

	public function admin_function()
	{
		$this->layout = 'support_admin_header';
		$login = $this->Session->read('Auth');

		$searchData = $this->request->query;
		$conditions = array();
		if(isset($searchData['t']) && !empty($searchData['t']))
		{
			$this->request->data['Search']['type'] = $searchData['t'];
			$conditions["ServiceMenu.channel_status"] = $searchData['t'];
		}
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["ServiceMenu.channel_name LIKE"] = '%'.$searchData['n'].'%';
		}
		$this->paginate = array(
			"conditions" => array(
				"ServiceMenuCategory.thinapp_id" => $login['User']['thinapp_id'],
				$conditions
			),
			'contain'=>false,
			'limit'=>WEB_PAGINATION_LIMIT,
			'order'=>array('ServiceMenuCategory.id'=>'desc')
		);
		$data = $this->paginate('ServiceMenuCategory');
		$this->set('channel',$data);
	}
	public function admin_add_funcation()
	{
		$this->layout = 'app_admin_home';
		$login = $this->Session->read('Auth.User');
		if(!$this->Custom->check_app_enable_permission($login['User']['thinapp_id'],"SERVICE")){
			$this->redirect(array('controller' => 'app_admin', 'action' => 'dashboard'));
		}
		if($this->request->is(array('post','put')))
		{
			$this->ServiceMenuCategory->set($this->request->data['ServiceMenuCategory']);
			if ($this->ServiceMenuCategory->validates()) {
				try {
					$this->request->data['ServiceMenuCategory']['thinapp_id'] = $login['User']['thinapp_id'];
					if ($this->ServiceMenuCategory->save($this->request->data['ServiceMenuCategory'])) {
						$this->Session->setFlash(__('Category add successfully.'), 'default', array(), 'success');
						$this->redirect(array('controller' => 'app_admin', 'action' => 'category'));
					} else {
						$this->Session->setFlash(__('Sorry category could not add.'), 'default', array(), 'error');
					}
				} catch (Exception $e) {
					$this->Session->setFlash(__('Sorry category could not add.'), 'default', array(), 'error');
				}
			}

		}

	}
	public function admin_edit_function($id =null)
	{
		$this->layout = 'app_admin_home';
		$login = $this->Session->read('Auth.User');
		if(!$this->Custom->check_app_enable_permission($login['User']['thinapp_id'],"SERVICE")){
			$this->redirect(array('controller' => 'app_admin', 'action' => 'dashboard'));
		}
		$id = base64_decode($id);
		if($this->request->is(array('post','put')))
		{
			$this->ServiceMenuCategory->id = $id;
			$this->request->data['ServiceMenuCategory']['id'] = $id;
			$this->ServiceMenuCategory->set($this->request->data['ServiceMenuCategory']);
			if ($this->ServiceMenuCategory->validates()) {
				try {
					if ($this->ServiceMenuCategory->save($this->request->data['ServiceMenuCategory'])) {
						$this->Session->setFlash(__('Category update successfully.'), 'default', array(), 'success');
						$this->redirect(array('controller' => 'app_admin', 'action' => 'category'));
					} else {
						$this->Session->setFlash(__('Sorry category could not update.'), 'default', array(), 'error');
					}
				} catch (Exception $e) {
					$this->Session->setFlash(__('Sorry category could not update.'), 'default', array(), 'error');
				}
			}

		}

		$channel = $this->ServiceMenuCategory->find("first",array(
			"conditions" => array(
				"ServiceMenuCategory.id" => ($id),
			),
			'contain'=>false
		));
		if (!$this->request->data) {
			$this->request->data = $channel;
		}

		$this->set('post',$channel);
	}

	public function admin_get_user_statics($date = null,$thinappID = null){

		if($date != "")
        {
			$date = explode("_",$date);
			$startDate = $date[0];
			$endDate = $date[1];
		}
		else
		{
			$startDate = date("Y-m-d");
			$endDate = date("Y-m-d");
		}

		$thinapps = $this->Thinapp->find( "list",array( "fields"=>array("id","name"),"conditions"=>array("status"=>"ACTIVE") ) );

		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition["created >="]=$startDate." 00:00:00";
		$condition["created <="]=$endDate." 23:59:59";
		$userCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(`id`) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));

		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition["created >="]=$startDate." 00:00:00";
		$condition["created <="]=$endDate." 23:59:59";
		$uniqueUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(DISTINCT(`user_id`)) AS unique_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="yearweek(DATE(created), 1) = yearweek(curdate(), 1)";
		$weekUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(`id`) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="YEAR(created) = YEAR(CURRENT_DATE())";
		$condition[]="MONTH(created) = MONTH(CURRENT_DATE())";
		$monthUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(`id`) AS total_users"),
			'contain'=>false,
			'conditions'=> $condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="YEAR(created) = YEAR(CURRENT_DATE())";
		$condition[]="MONTH(created) = MONTH(CURRENT_DATE())";
		$condition[]="DAY(created) = DAY(CURRENT_DATE())";
		$todayUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(`id`) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="yearweek(DATE(created), 1) = yearweek(curdate(), 1)";
		$weekUniqueUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(DISTINCT(`user_id`)) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="YEAR(created) = YEAR(CURRENT_DATE())";
		$condition[]="MONTH(created) = MONTH(CURRENT_DATE())";
		$monthUniqueUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(DISTINCT(`user_id`)) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$condition = array();
		if($thinappID != ''){ $condition["thin_app_id"] = $thinappID; }
		$condition[]="YEAR(created) = YEAR(CURRENT_DATE())";
		$condition[]="MONTH(created) = MONTH(CURRENT_DATE())";
		$condition[]="DAY(created) = DAY(CURRENT_DATE())";
		$todayUniqueUserCount = $this->AppUserStatic->find("first",array(
			"fields"=>array("count(DISTINCT(`user_id`)) AS total_users"),
			'contain'=>false,
			'conditions'=>$condition,
		));


		$this->set(compact('thinappID','thinapps','weekUniqueUserCount','monthUniqueUserCount','todayUniqueUserCount','weekUserCount','monthUserCount','todayUserCount','uniqueUserCount','userCount','startDate','endDate'));
	}
	public function admin_user_statics_details($date = null,$unique = null,$thinappID = null){

		$group = false;
		if($date != "")
        {
			$date = explode("_",$date);
			$startDate = $date[0];
			$endDate = $date[1];
		}
		else
		{
			$startDate = date("Y-m-d");
			$endDate = date("Y-m-d");
		}
		if($unique == "unique")
		{
			$group =  "AppUserStatic.user_id";
		}
		else
		{
			$thinappID = $unique;
		}
		$condition = array();
		$condition["AppUserStatic.created >="] = $startDate;
		$condition["AppUserStatic.created <="] = $endDate;
		if($thinappID != "")
		{
			$condition["AppUserStatic.thin_app_id"] = $thinappID;
		}

		$this->paginate = array(
			"fields"=>array("AppUserStatic.*","Owner.username","Owner.mobile"),
			"conditions" => $condition,
			'contain'=>"Owner",
			'group'=>$group,
			'order'=>array('AppUserStatic.id'=>'desc'),
			'limit'=>WEB_PAGINATION_LIMIT
		);
		$userData = $this->paginate('AppUserStatic');

		$thinapps = $this->Thinapp->find( "list",array( "fields"=>array("id","name"),"conditions"=>array("status"=>"ACTIVE") ) );
		$this->set(compact('thinappID','thinapps','userData','startDate','endDate'));
	}

	public function admin_list_cms_doc_sub_category(){
		//CmsDocHealthTipSubCategory
		$this->paginate = array(
			"fields"=>array("CmsDocHealthTipSubCategory.*"),
			'order'=>array('CmsDocHealthTipSubCategory.id'=>'desc'),
			'limit'=>WEB_PAGINATION_LIMIT
		);
		$userData = $this->paginate('CmsDocHealthTipSubCategory');
		//pr($userData); die;
		$this->set('data',$userData);
	}
	public function admin_add_cms_doc_sub_category() {
		$login = $this->Session->read('Auth.User');
		$thin_app_id = $login['thinapp_id'];
		if($this->request->is(array('post','put')))
		{
			$this->CmsDocHealthTipSubCategory->set($this->request->data['CmsDocHealthTipSubCategory']);

				try {
					if ($this->CmsDocHealthTipSubCategory->save($this->request->data['CmsDocHealthTipSubCategory'])) {
						
						WebservicesFunction::deleteJson(array('health_tip_category'));
						$this->Session->setFlash(__('Subcategory added successfully.'), 'default', array(), 'success');
						$this->redirect(array('controller' => 'Supp', 'action' => 'admin_list_cms_doc_sub_category'));
					} else {
						$this->Session->setFlash(__('Sorry category could not add.'), 'default', array(), 'error');
					}
				} catch (Exception $e) {
					$this->Session->setFlash(__('Sorry subcategory could not add.'), 'default', array(), 'error');
				}

		}
	}
	public function admin_change_doc_cms_subcategory_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$sellID = $this->request->data['sellID'];
			$sellItemData = $this->CmsDocHealthTipSubCategory->find("first",
				array(
					"fields"=>array('CmsDocHealthTipSubCategory.status','CmsDocHealthTipSubCategory.id'),
					"conditions" => array("CmsDocHealthTipSubCategory.id" => $sellID),
					"contain" => false,
				)
			);
			$statusToChange = ($sellItemData['CmsDocHealthTipSubCategory']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$sellItemData['CmsDocHealthTipSubCategory']['status'] = $statusToChange;
			if($this->CmsDocHealthTipSubCategory->save($sellItemData))
			{
				WebservicesFunction::deleteJson(array('health_tip_category'));
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}


	public function admin_cms_doc_dashboard()
	{
		$login = $this->Session->read('Auth');


		$this->paginate = array(
			"conditions" => array(
				"CmsDocDashboard.thinapp_id" => $login['User']['thinapp_id'],
				"CmsDocDashboard.user_id" => $login['User']['id'],
			),
			'contain'=>false,
			'order'=>array('CmsDocDashboard.id'=>'desc'),
			'limit'=>WEB_PAGINATION_LIMIT
		);
		$data = $this->paginate('CmsDocDashboard');
		$this->set('channel',$data);
	}
	public function admin_add_cms_doc_dashboard()
	{
		$login = $this->Session->read('Auth');

		$dataCategories = $this->CmsDocHealthTipSubCategory->find('list',array(
			"fields"=>array("id","sub_category_name"),
			"conditions" => array(
				"CmsDocHealthTipSubCategory.status" => 'ACTIVE',
				"CmsDocHealthTipSubCategory.category" => 'HEALTH_TIP'
			),
		));

		$dataEmergencyCategories = $this->CmsDocHealthTipSubCategory->find('list',array(
			"fields"=>array("id","sub_category_name"),
			"conditions" => array(
				"CmsDocHealthTipSubCategory.status" => 'ACTIVE',
				"CmsDocHealthTipSubCategory.category" => 'EMERGENCY'
			),
		));


        $mengageChannel = $this->CmsDocHealthTipSubCategory->find('list',array(
            "fields"=>array("id","sub_category_name"),
            "conditions" => array(
                "CmsDocHealthTipSubCategory.status" => 'ACTIVE',
                "CmsDocHealthTipSubCategory.category" => 'MENGAGE_CHANNEL'
            ),
        ));


        $this->set('subCategoriesEmergency',$dataEmergencyCategories);

		$this->set('subCategories',$dataCategories);
		$this->set('mengageChannel',$mengageChannel);

		if($this->request->is(array('post','put')))
		{
			$this->request->data['CmsDocDashboard']['user_id'] = $login['User']['id'];
			$this->request->data['CmsDocDashboard']['thinapp_id'] = $login['User']['thinapp_id'];

			$logo = $this->request->data['CmsDocDashboard']['file'];
			if(isset($logo['tmp_name']) && !empty($logo['tmp_name'])){
				
				$this->request->data['CmsDocDashboard']['image'] = $this->Custom->uploadFileToAws($logo);;
			
			}else{
				$this->request->data['CmsDocDashboard']['image'] = null;
			}

			$this->CmsDocDashboard->set($this->request->data['CmsDocDashboard']);
			if ($this->CmsDocDashboard->validates()) {
				if ($this->CmsDocDashboard->save($this->request->data['CmsDocDashboard'])) {
					$last_insert_id = $this->CmsDocDashboard->getLastInsertId();
					$this->Session->setFlash(__('Cms added successfully.'), 'default', array(), 'success');
                    $silent = $this->request->data['CmsDocDashboard']['notification'];
						$flag = $this->request->data['CmsDocDashboard']['category'];
						$thin_app_id =$login['User']['thinapp_id'];
						$option = array(
							'thinapp_id' => $thin_app_id,
							'channel_id' => 0,
							'role' => "USER",
							'flag' => $flag,
							'title' => mb_strimwidth($this->request->data['CmsDocDashboard']['title'], 0, 100, '...'),
							'file_path_url' => $this->request->data['CmsDocDashboard']['image'],
							'type' => 'IMAGE',
							'message' => mb_strimwidth($this->request->data['CmsDocDashboard']['title'], 0, 100, '...'),
							'description' => "",
							'chat_reference' => '',
							'module_type' => $flag,
							'module_type_id' => $last_insert_id,
							'firebase_reference' => "",
                            "silent"=>$silent
						);
						// Custom::send_process_to_background();
                        $save_array = array('option'=>$option,'thin_app_id'=>1,'send_notification_to'=>$this->request->data['CmsDocDashboard']['visible_for'],'id'=>$last_insert_id);
                        $save_array = json_encode($save_array);
                        $update = Custom::update_health_tip_flag($last_insert_id, $save_array);
                        if ($update) {
                            $this->redirect(array('controller'=>'Supp','action'=>'admin_cms_doc_dashboard'),null,false);
                        }
                        //Custom::send_health_and_emergency_notification($option,1,$this->request->data['CmsDocDashboard']['visible_for']);


				} else {
					$this->Session->setFlash(__('Sorry cms could not add.'), 'default', array(), 'error');
				}
			}
		}
		else
		{
			$this->request->data['CmsDocDashboard']['category'] = "EMERGENCY";
		}

	}
	public function admin_edit_cms_doc_dashboard($id=null)
	{
		$login = $this->Session->read('Auth');
		$dataCategories = $this->CmsDocHealthTipSubCategory->find('list',array(
			"fields"=>array("id","sub_category_name"),
			"conditions" => array(
				"CmsDocHealthTipSubCategory.status" => 'ACTIVE',
				"CmsDocHealthTipSubCategory.category" => 'HEALTH_TIP'
			),
		));

		$dataEmergencyCategories = $this->CmsDocHealthTipSubCategory->find('list',array(
			"fields"=>array("id","sub_category_name"),
			"conditions" => array(
				"CmsDocHealthTipSubCategory.status" => 'ACTIVE',
				"CmsDocHealthTipSubCategory.category" => 'EMERGENCY'
			),
		));


        $mengageChannel = $this->CmsDocHealthTipSubCategory->find('list',array(
            "fields"=>array("id","sub_category_name"),
            "conditions" => array(
                "CmsDocHealthTipSubCategory.status" => 'ACTIVE',
                "CmsDocHealthTipSubCategory.category" => 'MENGAGE_CHANNEL'
            ),
        ));


        $this->set('subCategoriesEmergency',$dataEmergencyCategories);
		$this->set('subCategories',$dataCategories);
        $this->set('mengageChannel',$mengageChannel);

		if($this->request->is(array('post','put')))
		{

			$this->CmsDocDashboard->id = base64_decode($id);
			$this->request->data['CmsDocDashboard']['id'] = base64_decode($id);;

			$logo = $this->request->data['CmsDocDashboard']['file'];
			if(isset($logo['tmp_name']) && !empty($logo['tmp_name'])){
				$this->request->data['CmsDocDashboard']['image'] = $this->Custom->uploadFileToAws($logo);;
			}
			else
			{
				unset($this->request->data['CmsDocDashboard']['media_type']);
			}

			$this->CmsDocDashboard->set($this->request->data['CmsDocDashboard']);
			if ($this->CmsDocDashboard->validates()) {
				if ($this->CmsDocDashboard->save($this->request->data['CmsDocDashboard'])) {

					$this->Session->setFlash(__('Cms update successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller'=>'Supp','action'=>'admin_cms_doc_dashboard'));
				} else {
					$this->Session->setFlash(__('Sorry cms could not update.'), 'default', array(), 'error');
				}
			}

		}

		$cms = $this->CmsDocDashboard->find("first",array(
			"conditions" => array(
				"CmsDocDashboard.id" => base64_decode($id),
			),
			'contain'=>false
		));


		if (!$this->request->data) {
			$this->request->data = $cms;
		}
		$this->set('post',$cms);

	}
	public function admin_view_cms_doc_dashboard($id=null)
	{
		$this->layout = false;
		$login = $this->Session->read('Auth');
		$cms = $this->CmsDocDashboard->find("first",array(
			"conditions" => array(
				"CmsDocDashboard.id" => base64_decode($id),
			),
			'contain'=>false
		));
		$this->set('content',$cms['CmsDocDashboard']);
	}
	public function admin_delete_cms_doc_dashboard($id=null)
	{
		$this->layout = false;
        $post_data = $this->CmsDocDashboard->find("first",array(
            "conditions" => array(
                "CmsDocDashboard.id" => (base64_decode($id))
            ),
            'contain'=>false
        ));
        $cms = $this->CmsDocDashboard->delete(base64_decode($id));
        if ($cms && $post_data) {
            if(!empty($post_data['CmsDocDashboard']['image'])){
                $file_name = explode("/", $post_data['CmsDocDashboard']['image']);
                $file_key = end($file_name);
                Custom::deleteFileToAws($file_key);
            }
			$this->Session->setFlash(__('Delete successfully.'), 'default', array(), 'success');
		} else {
			$this->Session->setFlash(__('Sorry could not update.'), 'default', array(), 'error');
		}
		$this->redirect(array('controller'=>'Supp','action'=>'admin_cms_doc_dashboard'));
	}

	public function admin_user_statics(){
		$thinappList = $this->Thinapp->find( "list",array( "fields"=>array("id","name"),"contain"=>false,"order"=>"name ASC" ) );
		$totalDownloads = $this->User->find("count",array("conditions"=>array("thinapp_id !="=>0),"contain"=>false));
		$totalActive = $this->User->find("count",array("conditions"=>array("thinapp_id !="=>0,"app_installed_status"=>"INSTALLED"),"contain"=>false));
		
		
		$conditionTotalData = array();
		$conditionTotalData["thinapp_id !="] = 0;
		$conditionInactiveData = array();
		$conditionInactiveData["thinapp_id !="] = 0;
		$conditionInactiveData["app_installed_status"] = "UNINSTALLED";
		
		if(isset($this->request->data['searchAllApp']))
		{
			if(!empty($this->request->data['searchAllApp']['thinapp']))
			{
				$conditionTotalData['thinapp_id'] = $this->request->data['searchAllApp']['thinapp'];
				$conditionInactiveData['thinapp_id'] = $this->request->data['searchAllApp']['thinapp'];
			}
			if(!empty($this->request->data['searchAllApp']['date']))
			{
				$conditionTotalData["DATE(created) <="] = $this->request->data['searchAllApp']['date'];
				$conditionInactiveData["DATE(modified) <="] = $this->request->data['searchAllApp']['date'];
			}
		}	
		
		$allAppsTotalData = $this->User->find("all",array(
			"fields"=>array("COUNT(`id`) AS `total_active`","thinapp_id"),
			"conditions"=>$conditionTotalData,
			"group"=>'thinapp_id',
			"contain"=>false
		));
		
		$allAppsTotalInactiveData = $this->User->find("all",array(
			"fields"=>array("COUNT(`id`) AS `total_inactive`","thinapp_id"),
			"conditions"=>$conditionInactiveData,
			"group"=>'thinapp_id',
			"contain"=>false
		));
		
		
		$allAppsTotal = array();
		foreach($allAppsTotalData AS $appDataActive)
		{
			$allAppsTotal[$appDataActive['User']['thinapp_id']] = $appDataActive[0]['total_active'];
		}
		
		$allAppsTotalInactive = array();
		foreach($allAppsTotalInactiveData AS $appDataInactive)
		{
			$allAppsTotalInactive[$appDataInactive['User']['thinapp_id']] = $appDataInactive[0]['total_inactive'];
		}
		
		
		$intervalType = 'none';
		if(isset($this->request->data['intervalType']))
		{
			$intervalType = $this->request->data['intervalType'];
			
			if($intervalType == 'day')
			{
				$startDate = $this->request->data['startDate'];
				$endDate = $this->request->data['endDate'];
				$endDate = date('Y-m-d', strtotime($endDate.'+1 day'));
				
				$begin = new DateTime($startDate);
				$end = new DateTime($endDate);
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				$interval = array();
				foreach($daterange as $date){
					$interval[] = $date->format("Y-m-d");
				}
				
				$thinappData = $this->User->query("SELECT DATE(`User`.`created`) AS `date`,
				COUNT(`User`.`id`) AS `new_created`,
				`thinapps`.`id` AS `thinappID`,
				(SELECT COUNT(`id`) FROM `users` WHERE `thinapp_id` = `thinappID` AND DATE(`created`) <= IF(`date` != '', `date`, '".$endDate."') ) AS `total_users`
				FROM `thinapps`
				LEFT JOIN `users` AS `User` ON (`User`.`thinapp_id` = `thinapps`.`id` AND DATE(`User`.`created`) <= '".$endDate."' AND DATE(`User`.`created`) >= '".$startDate."')
				GROUP BY DATE(`date`), `thinappID`");
				
				$dataToSend = array();
				foreach($thinappList as $thinappID => $thinappName)
				{
					$dataToSend[$thinappID]['thinapp_name'] = $thinappName;
				}
				
				$unavailableData = array();
				$availableData = array();
				foreach($thinappData as $rowData)
				{
					if($rowData[0]['date'] == '')
					{
						$unavailableData[$rowData['thinapps']['thinappID']] = $rowData[0]['total_users']."(".$rowData[0]['new_created'].")";
					}
					else
					{
						$availableData[$rowData['thinapps']['thinappID']][$rowData[0]['date']] = array('total_users' => $rowData[0]['total_users'],'new_created'=>$rowData[0]['new_created']);
					}
				}
				
				$dataToSendForLoop = $dataToSend;
				
					foreach($dataToSendForLoop as $thinappID => $thinapppData)
					{
						if(isset($unavailableData[$thinappID]))
						{
							foreach($interval AS $date)
							{
								$dataToSend[$thinappID][$date] = $unavailableData[$thinappID];
							}
						}
						else
						{
							foreach($availableData[$thinappID] AS $availableDate => $dateData)
							{
								foreach($interval AS $date)
								{
									if(strtotime($date) < strtotime($availableDate))
									{
										if(!isset($dataToSend[$thinappID][$date]))
										{
											$dataToSend[$thinappID][$date] = ($dateData['total_users']-$dateData['new_created'])."(0)";
										}
										
									}
									else if(strtotime($date) == strtotime($availableDate))
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(".$dateData['new_created'].")";
									}
									else
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(0)";
									}									
								}
							}
						}
					}
				$endDate = date('Y-m-d', strtotime($endDate.'-1 day'));
				$this->set(compact('intervalType','interval','dataToSend','startDate','endDate'));
			}
			
			if($intervalType == 'week')
			{
				$startWeek = $this->request->data['startWeek'];
				$endWeek = $this->request->data['endWeek'];
				$endWeek = date('Y-m-d', strtotime($endWeek.'+3 day'));
				
				$begin = new DateTime($startWeek);
				$end = new DateTime($endWeek);
				$daterange = new DatePeriod($begin, new DateInterval('P1W'), $end);
				$interval = array();
				foreach($daterange as $date){
					$interval[] = $date->format("Y-m-d");
				}
				$thinappData = $this->User->query("SELECT str_to_date(concat(yearweek(`User`.`created`), 'sunday'), '%X%V %W') as `date`,
				COUNT(`User`.`id`) AS `new_created`,
				`thinapps`.`id` AS `thinappID`,
				(SELECT COUNT(`id`) FROM `users` WHERE `thinapp_id` = `thinappID` AND DATE(`created`) <= IF(`date` != '', `date`, '".$endWeek."') ) AS `total_users`
				FROM `thinapps`
				LEFT JOIN `users` AS `User` ON (`User`.`thinapp_id` = `thinapps`.`id` AND DATE(`User`.`created`) <= '".$endWeek."' AND DATE(`User`.`created`) >= '".$startWeek."')
				GROUP BY yearweek(`date`), `thinappID`");
				$dataToSend = array();
				foreach($thinappList as $thinappID => $thinappName)
				{
					$dataToSend[$thinappID]['thinapp_name'] = $thinappName;
				}
				
				$unavailableData = array();
				$availableData = array();
				foreach($thinappData as $rowData)
				{
					if($rowData[0]['date'] == '')
					{
						$unavailableData[$rowData['thinapps']['thinappID']] = $rowData[0]['total_users']."(".$rowData[0]['new_created'].")";
					}
					else
					{
						$availableData[$rowData['thinapps']['thinappID']][$rowData[0]['date']] = array('total_users' => $rowData[0]['total_users'],'new_created'=>$rowData[0]['new_created']);
					}
				}
				
				$dataToSendForLoop = $dataToSend;
				
					foreach($dataToSendForLoop as $thinappID => $thinapppData)
					{
						if(isset($unavailableData[$thinappID]))
						{
							foreach($interval AS $date)
							{
								$dataToSend[$thinappID][$date] = $unavailableData[$thinappID];
							}
						}
						else
						{
							foreach($availableData[$thinappID] AS $availableDate => $dateData)
							{
								foreach($interval AS $date)
								{
									if(strtotime($date) < strtotime($availableDate))
									{
										if(!isset($dataToSend[$thinappID][$date]))
										{
											$dataToSend[$thinappID][$date] = ($dateData['total_users']-$dateData['new_created'])."(0)";
										}
										
									}
									else if(strtotime($date) == strtotime($availableDate))
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(".$dateData['new_created'].")";
									}
									else
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(0)";
									}									
								}
							}
						}
					}
				$endWeek = date('Y-m-d', strtotime($endWeek.'-3 day'));
				$this->set(compact('intervalType','interval','dataToSend','startWeek','endWeek'));
			}
			
			if($intervalType == 'month')
			{
				$startMonth = $this->request->data['startMonth']."-1";
				$endMonth = $this->request->data['endMonth']."-28";
				//$endMonth = date('Y-m-d', strtotime($endMonth.'+4 day'));
				
				$begin = new DateTime($startMonth);
				$end = new DateTime($endMonth);
				$daterange = new DatePeriod($begin, new DateInterval('P1M'), $end);
				$interval = array();
				foreach($daterange as $date){
					$interval[] = $date->format("Y-m");
				}
				$endMonth = date('Y-m', strtotime($endMonth));
				$startMonth = date('Y-m', strtotime($startMonth));
				
				$thinappData = $this->User->query("SELECT DATE_FORMAT(`User`.`created`, '%Y-%m') as `date`,
				COUNT(`User`.`id`) AS `new_created`,
				`thinapps`.`id` AS `thinappID`,
				(SELECT COUNT(`id`) FROM `users` WHERE `thinapp_id` = `thinappID` AND DATE_FORMAT(`created`, '%Y-%m') <= IF(`date` != '', `date`, '".$endMonth."') ) AS `total_users`
				FROM `thinapps`
				LEFT JOIN `users` AS `User` ON (`User`.`thinapp_id` = `thinapps`.`id` AND DATE_FORMAT(`User`.`created`, '%Y-%m') <= '".$endMonth."' AND DATE_FORMAT(`User`.`created`, '%Y-%m') >= '".$startMonth."')
				GROUP BY `date`, `thinappID`");
				
				$dataToSend = array();
				foreach($thinappList as $thinappID => $thinappName)
				{
					$dataToSend[$thinappID]['thinapp_name'] = $thinappName;
				}
				
				$unavailableData = array();
				$availableData = array();
				foreach($thinappData as $rowData)
				{
					if($rowData[0]['date'] == '')
					{
						$unavailableData[$rowData['thinapps']['thinappID']] = $rowData[0]['total_users']."(".$rowData[0]['new_created'].")";
					}
					else
					{
						$availableData[$rowData['thinapps']['thinappID']][$rowData[0]['date']] = array('total_users' => $rowData[0]['total_users'],'new_created'=>$rowData[0]['new_created']);
					}
				}
				
				$dataToSendForLoop = $dataToSend;
				
					foreach($dataToSendForLoop as $thinappID => $thinapppData)
					{
						if(isset($unavailableData[$thinappID]))
						{
							foreach($interval AS $date)
							{
								$dataToSend[$thinappID][$date] = $unavailableData[$thinappID];
							}
						}
						else
						{
							foreach($availableData[$thinappID] AS $availableDate => $dateData)
							{
								foreach($interval AS $date)
								{
									if(strtotime($date) < strtotime($availableDate))
									{
										if(!isset($dataToSend[$thinappID][$date]))
										{
											$dataToSend[$thinappID][$date] = ($dateData['total_users']-$dateData['new_created'])."(0)";
										}
										
									}
									else if(strtotime($date) == strtotime($availableDate))
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(".$dateData['new_created'].")";
									}
									else
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(0)";
									}									
								}
							}
						}
					}
				
				$this->set(compact('intervalType','interval','dataToSend','startMonth','endMonth'));
			}
			
			if($intervalType == 'year')
			{
				$startYear = $this->request->data['startYear'];
				$endYear = $this->request->data['endYear'];
				
				$interval = array();
				for ($x = $startYear; $x <= $endYear; $x++) {
					$interval[] = $x;
				}
				
				$thinappData = $this->User->query("SELECT DATE_FORMAT(`User`.`created`, '%Y') as `date`,
				COUNT(`User`.`id`) AS `new_created`,
				`thinapps`.`id` AS `thinappID`,
				(SELECT COUNT(`id`) FROM `users` WHERE `thinapp_id` = `thinappID` AND DATE_FORMAT(`created`, '%Y') <= IF(`date` != '', `date`, '".$endYear."') ) AS `total_users`
				FROM `thinapps`
				LEFT JOIN `users` AS `User` ON (`User`.`thinapp_id` = `thinapps`.`id` AND DATE_FORMAT(`User`.`created`, '%Y') <= '".$endYear."' AND DATE_FORMAT(`User`.`created`, '%Y') >= '".$startYear."')
				GROUP BY `date`, `thinappID`");
				
				$dataToSend = array();
				foreach($thinappList as $thinappID => $thinappName)
				{
					$dataToSend[$thinappID]['thinapp_name'] = $thinappName;
				}
				
				$unavailableData = array();
				$availableData = array();
				foreach($thinappData as $rowData)
				{
					if($rowData[0]['date'] == '')
					{
						$unavailableData[$rowData['thinapps']['thinappID']] = $rowData[0]['total_users']."(".$rowData[0]['new_created'].")";
					}
					else
					{
						$availableData[$rowData['thinapps']['thinappID']][$rowData[0]['date']] = array('total_users' => $rowData[0]['total_users'],'new_created'=>$rowData[0]['new_created']);
					}
				}
				
				$dataToSendForLoop = $dataToSend;
				
					foreach($dataToSendForLoop as $thinappID => $thinapppData)
					{
						if(isset($unavailableData[$thinappID]))
						{
							foreach($interval AS $date)
							{
								$dataToSend[$thinappID][$date] = $unavailableData[$thinappID];
							}
						}
						else
						{
							foreach($availableData[$thinappID] AS $availableDate => $dateData)
							{
								foreach($interval AS $date)
								{
									if(strtotime($date) < strtotime($availableDate))
									{
										if(!isset($dataToSend[$thinappID][$date]))
										{
											$dataToSend[$thinappID][$date] = ($dateData['total_users']-$dateData['new_created'])."(0)";
										}
										
									}
									else if(strtotime($date) == strtotime($availableDate))
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(".$dateData['new_created'].")";
									}
									else
									{
										$dataToSend[$thinappID][$date] = $dateData['total_users']."(0)";
									}									
								}
							}
						}
					}
				$this->set(compact('intervalType','interval','dataToSend','startYear','endYear'));
			}
			
			
		}
		
 		
		$this->set(compact('thinappList','totalDownloads','totalActive','allAppsTotal','allAppsTotalInactive'));
	}
	
	public function admin_load_bar_chart_graph()
	{
		$this->layout = 'ajax';
		if($this->request->is('ajax')) {
			$thin_app_id = ($this->request->data['tid']);
			$from_date = ($this->request->data['fd']);
			$to_date = $this->request->data['td'];
			$chart_data = json_encode(Custom::get_bar_chart_data($thin_app_id,$from_date,$to_date));
			$chart_data_2 = json_encode(Custom::get_bar_chart_data($thin_app_id,$from_date,$to_date,true));
			$this->set(compact(array('chart_data','chart_data_2')));
			$this->render('admin_load_bar_chart_graph', 'ajax');
		}else{
			exit();
		}

	}

	public function admin_send_health_tip_notification()
	{
		$this->layout = 'ajax';
		$this->autoRender =false;
		ignore_user_abort(true);
		if($this->request->is('ajax')) {
			$id = ($this->request->data['id']);
            $query = "select notification_param from cms_doc_dashboards where id = $id and status = 'ACTIVE'";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $cms_data = mysqli_fetch_assoc($service_message_list);
                $data = json_decode($cms_data['notification_param'],true);
                $res =  Custom::send_health_and_emergency_notification($data['option'],$data['thin_app_id'],$data['send_notification_to']);
                if(!empty($res)){
                    $update = Custom::update_health_tip_flag($id, '');
                    if ($update) {
                        $response['status'] = 1;
                        $response['message'] = "Notification send";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Notification send, Please do not press notification button again.";
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Notification could not send";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Invalid health tip";
            }
            Custom::sendResponse($response);
        }else{
			exit();
		}

	}

	public function admin_load_user_chart_graph()
	{
		$this->layout = 'ajax';
		if($this->request->is('ajax')) {
			$thin_app_id = ($this->request->data['tid']);
			$from_date = ($this->request->data['fd']);
			$to_date = $this->request->data['td'];
			$chart_data = json_encode(Custom::get_chart_user_static($thin_app_id,$from_date,$to_date));
			$this->set(compact('chart_data'));
			$this->render('admin_load_user_chart_graph', 'ajax');
		}else{
			exit();
		}

	}

	public function admin_load_bar_chart_sub_module_graph()
	{
		$this->layout = 'ajax';
		if($this->request->is('ajax')) {
			$thin_app_id = ($this->request->data['tid']);
			$from_date = ($this->request->data['fd']);
			$to_date = $this->request->data['td'];
			$module_name = $this->request->data['mn'];
			$chart_data = json_encode(Custom::get_bar_chart_sub_module_data($thin_app_id,$from_date,$to_date,$module_name));
			$this->set(compact('chart_data'));
			$this->render('admin_load_bar_chart_sub_module_graph', 'ajax');
		}else{
			exit();
		}

	}




	public function admin_search_contest(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['contest']))
		{
			$pram['t'] = $reqData['contest'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "list_contest",
				"?" => $pram,
			)
		);
	}

	public function admin_list_contest(){
		$login = $this->Session->read('Auth');

		$searchData = $this->request->query;
		$conditions = array();
		if(isset($searchData['t']) && !empty($searchData['t']))
		{
			$this->request->data['Search']['contest'] = $searchData['t'];
			$conditions["Contest.title LIKE "] = '%'.$searchData['t'].'%';
		}

		$conditions["Contest.thinapp_id"] = $login['User']['thinapp_id'];
		$this->paginate = array(
			"conditions" => $conditions,
			'contain'=>false,
			'order'=>array('Contest.id'=>'desc'),
			'limit'=>WEB_PAGINATION_LIMIT
		);
		$data = $this->paginate('Contest');
		$this->set('data',$data);
	}

	public function admin_add_contest(){
		$login = $this->Session->read('Auth.User');
		$thin_app_id = $login['thinapp_id'];
		if($this->request->is(array('post','put')))
		{
			$this->request->data['Contest']['thinapp_id'] = $thin_app_id;
			$image = $this->request->data['Contest']['image'];
			if(isset($image['tmp_name']) && !empty($image['tmp_name'])){

				$this->request->data['Contest']['image'] = $this->Custom->uploadFileToAws($image);;

			}else{
				$this->request->data['Contest']['image'] = null;
			}
			$dataToSave = $this->request->data['Contest'];

            $dataToSave['start_time'] = $this->request->data['Contest']['start_date'].' '.$this->request->data['Contest']['start_time'];
            $dataToSave['end_time'] = $this->request->data['Contest']['end_date'].' '.$this->request->data['Contest']['end_time'];


            unset($dataToSave['start_date']);
			unset($dataToSave['end_date']);
			$dataToSave['maximum_time_limit'] = empty($dataToSave['maximum_time_limit'])?0:$dataToSave['maximum_time_limit'];
			$this->Contest->set($dataToSave);

			try {
				if ($RS = $this->Contest->save($dataToSave)) {
					$this->Session->setFlash(__('Contest added successfully.'), 'default', array(), 'success');

                    //send_notification_via_token
                    $contestFunTypeID = $this->AppFunctionalityType->find("first",array('conditions'=>array('label_key'=>'CONTEST','status'=>'Y'),'contain'=>false));
                    $contestFunTypeID = $contestFunTypeID['AppFunctionalityType']['id'];


                    $enableContestThinapp = $this->AppEnableFunctionality->find('all',array('fields'=>array('thinapp_id'),'conditions'=>array('app_functionality_type_id'=>$contestFunTypeID),'contain'=>false));
                    $thinappArr = array();
                    foreach($enableContestThinapp AS $val)
                    {
                        $thinappArr[] = $val['AppEnableFunctionality']['thinapp_id'];
                    }

                    $userData = $this->User->find('all',array('fields'=>array('thinapp_id','firebase_token'),'conditions'=>array('firebase_token <>'=>"",'firebase_token !='=>null,'status'=>'Y','thinapp_id'=>$thinappArr,),'contain'=>false));
                    $notificationData = array();

                    foreach($userData AS $val)
                    {
                        $notificationData[$val['User']['thinapp_id']][] = $val['User']['firebase_token'];
                    }


                    foreach($notificationData AS $key => $fireArr)
                    {
                        $date = date('d/m/Y H:i',strtotime($dataToSave['start_time']));
                        $message = "Attention! A New Contest Is Going To Start From ".$date.".Play And Get Rewarded";
                        $option = array(
                            'channel_id' => 0,
                            'thinapp_id' => $key,
                            'flag' => 'CONTEST',
                            'title' => 'New Message',
                            'message' => $message,
                            'description' => $message,
                            'chat_reference' => '',
                            'type' => $message,
                            'file_path_url' => '',
                            'module_type' => 'CONTEST',
                            'module_type_id' => 0,
                            'firebase_reference' => ""
                        );
                        Custom::send_notification_via_token($option, $fireArr, $key);
                    }

					$this->redirect(array('controller' => 'Supp', 'action' => 'admin_list_contest'));
				} else {
					$this->Session->setFlash(__('Sorry Contest could not add.'), 'default', array(), 'error');

				}
			} catch (Exception $e) {
				$this->Session->setFlash(__('Sorry Contest could not add.'), 'default', array(), 'error');
			}

		}
	}

	public function admin_view_contest(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->Contest->find("first",
				array(
					"conditions" => array("Contest.id" => $rowID),
					"contain" => false,
				)
			);


			$response = "<tr>
				<td width='100%' colspan='2' style='text-align: center;'>
				<img src='".$giftData['Contest']['image']."' style='width: 200px;'>
				</td>
			</tr>
			<tr>
				<td width='20%'>Title:</td>
				<td>".$giftData['Contest']['title']."</td>
			</tr>
			<tr>
				<td width='20%'>Description:</td>
				<td>".$giftData['Contest']['description']."</td>
			</tr>
			
			<tr>
				<td width='20%'>Contest Type:</td>
				<td>".$giftData['Contest']['contest_type']."</td>
			</tr>
			
			<tr>
				<td width='20%'>Start Time:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Contest']['start_time']))."</td>
			</tr>
			
			<tr>
				<td width='20%'>End Time:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Contest']['end_time']))."</td>
			</tr>
			
			
			<tr>
				<td width='20%'>Allow Maximum Time Limit:</td>
				<td>".$giftData['Contest']['allow_maximum_time_limit']."</td>
			</tr>
			<tr>
				<td width='20%'>Maximum Time Limit:</td>
				<td>".$giftData['Contest']['maximum_time_limit']."</td>
			</tr>
			<tr>
				<td width='20%'>Response Count:</td>
				<td>".$giftData['Contest']['response_count']."</td>
			</tr>
			<tr>
				<td width='20%'>View Count:</td>
				<td>".$giftData['Contest']['view_count']."</td>
			</tr>
			<tr>
				<td width='20%'>Open Status:</td>
				<td>".$giftData['Contest']['open_status']."</td>
			</tr>
			
			<tr>
				<td width='20%'>Status:</td>
				<td>".$giftData['Contest']['status']."</td>
			</tr>
			
			<tr>
				<td width='20%'>Created:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Contest']['created']))."</td>
			</tr>
			<tr>
				<td width='20%'>Modified:</td>
				<td>".date("d-M-Y H:i:s",strtotime($giftData['Contest']['modified']))."</td>
			</tr>";

			echo $response; die;


		}
	}

	public function admin_change_contest_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$giftData = $this->Contest->find("first",
				array(
					"conditions" => array("Contest.id" => $rowID),
					"contain" => false,
				)
			);

			$statusToChange = ($giftData['Contest']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';


			$giftData['Contest']['status'] = $statusToChange;

			if($this->Contest->save($giftData['Contest']))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_edit_contest($id=null){
		$id=base64_decode($id);
		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$this->request->data['Contest']['start_time'] = $this->request->data['Contest']['start_date'].' '.$this->request->data['Contest']['start_time'];
			$this->request->data['Contest']['end_time'] = $this->request->data['Contest']['end_date'].' '.$this->request->data['Contest']['end_time'];


			$dataToSave = $this->request->data;
			$dataToSave['Contest']['id'] = $id;
			$image = $dataToSave['Contest']['image'];
			unset($dataToSave['Contest']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToSave['Contest']['image'] = $url;
						if($this->Contest->saveAll($dataToSave))
						{
							$this->Session->setFlash(__('Contest updated successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'supp', 'action' => 'list_contest'));
						}
						else
						{
							$this->Session->setFlash(__('Sorry, Contest could not updated.'), 'default', array(), 'error');
						}
					}
					else
					{
						$this->Session->setFlash(__('Could not upload image.'), 'default', array(), 'error');
					}
				}
				else
				{
					$this->Session->setFlash(__('Please upload image.'), 'default', array(), 'error');
				}
			}
			else
			{
				if($this->Contest->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Contest updated successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'list_contest'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, Contest could not updated.'), 'default', array(), 'error');
				}
			}
		}
		else
		{
			$this->request->data = $this->Contest->findById($id);
			if(empty($this->request->data))
			{
				$this->Session->setFlash(__('Sorry, Contest was not found.'), 'default', array(), 'error');
				$this->redirect(array('controller' => 'supp', 'action' => 'list_contest'));
				return false;
			}
			else
			{
				$start = $this->request->data['Contest']['start_time'];
				$start = explode(' ', $start);
				$this->request->data['Contest']['start_time'] = $start[1];
				$this->request->data['Contest']['start_date'] = $start[0];
				$end = $this->request->data['Contest']['end_time'];
				$end = explode(' ', $end);
				$this->request->data['Contest']['end_time'] = $end[1];
				$this->request->data['Contest']['end_date'] = $end[0];
			}
		}
	}

	public function admin_edit_contest_que($id=null){
		$id=base64_decode($id);
		$login = $this->Session->read('Auth.User');
		$thin_app_id = $login['thinapp_id'];

		if($this->request->is(array('post','put')))
		{
			$dataReceived = $this->request->data;
			$dataToSave = array();

			foreach($dataReceived['question'] as $key => $question)
			{
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['thinapp_id'] = $thin_app_id;
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['contest_id'] = $id;
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['question'] = $question;
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['option_a'] = $dataReceived['answer_a'][$key];
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['option_b'] = $dataReceived['answer_b'][$key];
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['option_c'] = $dataReceived['answer_c'][$key];
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['option_d'] = $dataReceived['answer_d'][$key];
				$dataToSave[$key]['ContestMultipleChoiceQuestion']['answer'] = $dataReceived['correct_answer'][$key];
				if(isset($dataReceived['id'][$key]))
				{
					$dataToSave[$key]['ContestMultipleChoiceQuestion']['id'] = $dataReceived['id'][$key];
				}
				
			}
			$this->request->data = $dataToSave;
			$this->ContestMultipleChoiceQuestion->deleteAll(array(
				'ContestMultipleChoiceQuestion.contest_id' => $id
			));
			if($this->ContestMultipleChoiceQuestion->saveAll($dataToSave))
			{
				$this->Session->setFlash(__('Questions updated successfully.'), 'default', array(), 'success');
				$this->redirect(array('controller' => 'supp', 'action' => 'list_contest'));
			}
			else
			{
				$this->Session->setFlash(__('Sorry, Questions could not updated.'), 'default', array(), 'error');
			}
		}
		else
		{
			$this->request->data = $this->ContestMultipleChoiceQuestion->find("all",array(
				"conditions" => array(
					"ContestMultipleChoiceQuestion.contest_id" => $id,
				),
				'contain'=>false
			));
		}

	}

	/* public function admin_view_response(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = '';
			$rowID = $this->request->data['rowID'];
			$responseContest = $this->Contest->find("first",
				array(
					"fields"=>array("id","contest_type"),
					"conditions" => array("Contest.id" => $rowID),
					"contain" => false,
				)
			);
			$contestType =  $responseContest['Contest']['contest_type'];

			if($contestType == 'MULTIPLE_CHOICE')
			{
				$dataToShow = $this->ContestMultipleChoiceAnswer->find("all",
					array(
						"conditions" => array("ContestMultipleChoiceAnswer.contest_id" => $rowID),
						"contain" => array('User','Thinapp'),
						"fields" => array("ContestMultipleChoiceAnswer.*","User.username","User.mobile",'Thinapp.name')
					)
				);
				pr($dataToShow); die;
			}


			echo $response; die;


		}
	} */

	public function admin_view_response(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {

					$contest_id = $this->request->data['rowID'];
					$response = array();
					if ($contest_id != '') {

						$contestData = $this->ContestMultipleChoiceAnswer->query("SELECT `contests`.`contest_type`,`contests`.`response_count`,`contests`.`start_time`,`contests`.`end_time`,`contests`.`open_status` FROM `contests` WHERE `id` = '$contest_id'");

						if($contestData[0]['contests']['contest_type'] == 'TEXT')
						{
							$contestAnswerData = $this->ContestMultipleChoiceAnswer->query("SELECT `contest_text_responses`.*,`users`.`username` FROM `contest_text_responses` LEFT JOIN `users` ON (`users`.`id` = `contest_text_responses`.`user_id`) WHERE `contest_text_responses`.`contest_id` = '$contest_id' ORDER BY `contest_text_responses`.`id` DESC");

							if (!empty($contestAnswerData)) {

								$tableData = "";
								foreach($contestAnswerData AS $answer)
								{
									$tableData .= "<tr><td><b>".$answer['users']['username']."</b> (".$answer['contest_text_responses']['mobile'].")</td></tr>";
									$tableData .= "<tr><td>".$answer['contest_text_responses']['description']."</td></tr>";
								}

							} else {

								$tableData = "<tr><td>No response found</td></tr>";

							}

						}
						else
						{

							$userData = $this->ContestMultipleChoiceAnswer->query("SELECT DISTINCT(`contest_multiple_choice_answers`.`user_id`),`users`.`username`,`users`.`mobile`,`contest_multiple_choice_answers`.`user_desc` FROM `contest_multiple_choice_answers` LEFT JOIN `users` ON(`contest_multiple_choice_answers`.`user_id` = `users`.`id`) WHERE `contest_multiple_choice_answers`.`contest_id` = '$contest_id'  ORDER BY `contest_multiple_choice_answers`.`id` DESC");
							
							if (!empty($userData)) {
								$totalQuestionData = $this->ContestMultipleChoiceAnswer->query("SELECT COUNT(`id`) AS `total_questions` FROM `contest_multiple_choice_questions` WHERE `contest_multiple_choice_questions`.`contest_id` = '$contest_id' AND `contest_multiple_choice_questions`.`status` = 'ACTIVE'");
								$dataToSend = array();
								foreach($userData AS $user)
								{
									$answerData = $this->ContestMultipleChoiceAnswer->query("SELECT COUNT(`id`) AS `total_correct_answers` FROM `contest_multiple_choice_answers` WHERE `contest_id` = '$contest_id' AND `user_id` = '".$user['contest_multiple_choice_answers']['user_id']."' AND `is_correct` = 'YES'");

									$user['total_correct_answers'] = $answerData[0][0]['total_correct_answers'];
									$user['total_questions'] = $totalQuestionData[0][0]['total_questions'];
									$dataToSend[] = $user;
								}

								$tableData = "";

								foreach($dataToSend as $data)
								{
									$tableData .="<tr><td><b>".$data['users']['username']."</b> (".$data['users']['mobile'].")</td><td>Correct Answers: ".$data['total_correct_answers']."</td></tr>";
								}

							} else {
								$tableData = "<tr><td>No response found</td></tr>";
							}


						}

					} else {
						$response['status'] = 0;
						$response['message'] = "Invalid request parameter";
					}
					echo $tableData;


		}

		die;
	}

	public function admin_update_contest_winner($id=null) {
		$contestID=base64_decode($id);
		if(!empty($contestID))
		{
			
			if($this->request->is(array('post','put')))
			{
				$dataReceived = $this->request->data;
				$dataToSave = array();
				foreach($dataReceived['title'] as $key => $question)
				{
					$dataToSave[$key]['ContestWinner']['title'] = $dataReceived['title'][$key];
					$dataToSave[$key]['ContestWinner']['winning_title'] = $dataReceived['winning_title'][$key];
					if(isset($dataReceived['id'][$key])){ $dataToSave[$key]['ContestWinner']['id'] = $dataReceived['id'][$key]; }
					$userData = explode("-",$dataReceived['user_data'][$key]);
					$dataToSave[$key]['ContestWinner']['user_id'] = $userData[0];
					$dataToSave[$key]['ContestWinner']['thinapp_id'] = $userData[1];
					$dataToSave[$key]['ContestWinner']['mobile'] = $userData[2];
					$dataToSave[$key]['ContestWinner']['contest_text_response_id'] = $userData[3];
					$dataToSave[$key]['ContestWinner']['contest_id'] = $contestID;
					
				}
				
				$this->ContestWinner->deleteAll(array(
					'ContestWinner.contest_id' => $contestID
				));
				
				if($this->ContestWinner->saveAll($dataToSave))
				{
					$this->Session->setFlash(__('Winners updated successfully.'), 'default', array(), 'success');
					$this->redirect(array('controller' => 'supp', 'action' => 'list_contest'));
				}
				else
				{
					$this->Session->setFlash(__('Sorry, Winners could not updated.'), 'default', array(), 'error');
				}
			}
			else
			{
			
				$contestWinners = $this->ContestWinner->find("all",array(
					"fields"=>array("CONCAT(`ContestWinner`.`user_id`,'-',`ContestWinner`.`thinapp_id`,'-',`ContestWinner`.`mobile`,'-',`ContestWinner`.`contest_text_response_id`) AS contest_winner , `ContestWinner`.`id`, `ContestWinner`.`title`, `ContestWinner`.`winning_title`"),
					"conditions"=>array("contest_id"=>$contestID),
					"contain"=>false
				));
				
				$contestData = $this->Contest->find('first',array("conditions"=>array("id"=>$contestID),"contain"=>false));
				$contestType = $contestData['Contest']['contest_type'];
				if($contestData['Contest']['contest_type'] == 'TEXT'){
					$contestAnswerData = $this->ContestTextResponse->find('all',array(
						"fields"=>array("User.username","Thinapp.id","Thinapp.name","ContestTextResponse.id","ContestTextResponse.mobile","ContestTextResponse.contest_id"),
						"conditions"=>array(
						"ContestTextResponse.contest_id"=>$contestID,
						"ContestTextResponse.status"=>'ACTIVE'
						),
						"contain"=>array("User","Thinapp")
					));
				}
				else
				{
					
					$contestAnswerData = $this->ContestMultipleChoiceAnswer->find("all",array(
						"fields"=>array(
							"DISTINCT(`ContestMultipleChoiceAnswer`.`user_id`)",
							"User.username",
							"Thinapp.id",
							"Thinapp.name",
							"ContestMultipleChoiceAnswer.mobile",
							"ContestMultipleChoiceAnswer.contest_id"
						),
						"conditions"=>array(
							"ContestMultipleChoiceAnswer.contest_id"=>$contestID,
							"ContestMultipleChoiceAnswer.status"=>'ACTIVE'
						),
						"contain"=>array("User","Thinapp")
					));
					
				}
				
				$this->set(compact('contestType','contestAnswerData','contestWinners'));
			
			}
			
			
		}
	}


	public function admin_search_user_list(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}
		if(!empty($reqData['mobile']))
		{
			$pram['m'] = $reqData['mobile'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "user_list",
				"?" => $pram,
			)
		);
	}

	public function admin_user_list()
	{
		$login = $this->Session->read('Auth.User');
		$conditions["User.thinapp_id"] = DOCTOR_FACTORY_APP_ID;
		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["User.username LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['m']) && !empty($searchData['m']))
		{
			$this->request->data['Search']['mobile'] = $searchData['m'];
			$conditions["User.mobile LIKE"] = '%'.$searchData['m'].'%';
		}

		$this->paginate = array(
			"conditions" => $conditions,
			'fields'=>array('User.*'),
			'contain'=>false,
			'order' => 'User.id asc',
			'limit' => 50
		);
		$data = $this->paginate('User');

		$this->set('data',$data);

	}

	public function admin_change_support_user()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$thinappData = $this->User->find("first",
				array(
					"conditions" => array("User.id" => $rowID),
					"contain" => false,
				)
			);

			$connection = ConnectionUtil::getConnection();
			$created = Custom::created();
			$statusToChange = ($thinappData['User']['is_support_user'] == 'YES')?'NO':'YES';
			$sql = "UPDATE users set is_support_user =?, modified = ?  where id = ?";
			$stmt = $connection->prepare($sql);
			$stmt->bind_param('sss', $statusToChange, $created, $thinappData['User']['id']);
			if ($stmt->execute()){
				$response['status'] = 1;
				$response['text'] = $statusToChange;
				WebservicesFunction::deleteJson(array("support_user","support_user_token"));
			}else{
				$response['status'] = 0;
			}
			echo json_encode($response, true);
			exit();
		}
	}


    public function admin_doctor_report_graph()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $day_type = ($this->request->data['dt']);
            $thin_app_id = ($this->request->data['tid']);
            $download_count = ($this->request->data['dc']);
            $from_date = ($this->request->data['fd']);
            $to_date = $this->request->data['td'];
            $chart_data = json_encode(Custom::get_doctor_reports($thin_app_id,$day_type,$download_count,$from_date,$to_date));
            //  $chart_data_2 = json_encode(Custom::get_bar_chart_data($thin_app_id,$from_date,$to_date,true));
            $this->set(compact(array('chart_data','chart_data_2')));
            $this->render('admin_doctor_report_graph', 'ajax');
        }else{
            exit();
        }

    }

    public function admin_doctor_reports() {


    }

    public function admin_patient_report_graph()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $day_type = ($this->request->data['dt']);
            $thin_app_id = ($this->request->data['tid']);
            $download_count = ($this->request->data['dc']);
            $from_date = ($this->request->data['fd']);
            $to_date = $this->request->data['td'];
            $chart_data = json_encode(Custom::get_doctor_reports($thin_app_id,$day_type,$download_count,$from_date,$to_date,'PATIENT'));
            //  $chart_data_2 = json_encode(Custom::get_bar_chart_data($thin_app_id,$from_date,$to_date,true));
            $this->set(compact(array('chart_data','chart_data_2')));
            $this->render('admin_patient_report_graph', 'ajax');
        }else{
            exit();
        }

    }

    public function admin_patient_reports() {


    }

    public function admin_tiger_report() {


    }


    public function admin_load_tiger_report()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $download_count = ($this->request->data['dc']);
            $avg_count = $this->request->data['ac'];
            $app_list = Custom::load_tiger_report($download_count,$avg_count);
            $this->set(compact(array('app_list')));
            $this->render('admin_load_tiger_report', 'ajax');
        }else{
            exit();
        }

    }
    public function admin_load_user_download_graph()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $thin_app_id = ($this->request->data['tid']);
            $from_date = ($this->request->data['fd']);
            $to_date = $this->request->data['td'];
            $chart_data = json_encode(Custom::load_user_download_graph($thin_app_id,$from_date,$to_date));
            $this->set(compact(array('chart_data')));
            $this->render('admin_load_user_download_graph', 'ajax');
        }else{
            exit();
        }

    }
    public function admin_tiger_potential_report(){

    }
    public function admin_load_tiger_potential_report()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $download_count_from = ($this->request->data['dcf']);
            $download_count_to = ($this->request->data['dct']);
            $avg_count = $this->request->data['ac'];
            $app_list = Custom::load_tiger_potential_report($download_count_from,$download_count_to,$avg_count);
            $this->set(compact(array('app_list')));
            $this->render('admin_load_tiger_potential_report', 'ajax');
        }else{
            exit();
        }

    }




    public function admin_prescription_tag_list()
    {
       // $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');


        $this->paginate = array(
            "conditions" => array(
                "PrescriptionTag.thinapp_id" => 0,
                "PrescriptionTag.status" => 'ACTIVE',
                "PrescriptionTag.type" => 'PRESCRIPTION_TAG'
            ),
            'contain'=>false,
            'order'=>array('PrescriptionTag.id'=>'desc'),
            'limit'=>WEB_PAGINATION_LIMIT
        );
        $data = $this->paginate('PrescriptionTag');
        $this->set('channel',$data);
    }

    public function admin_add_prescription_tag()
    {
       // $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        if($this->request->is(array('post','put')))
        {
            $this->request->data['PrescriptionTag']['thinapp_id'] = 0;
            $this->request->data['PrescriptionTag']['type'] = "PRESCRIPTION_TAG";
            $this->PrescriptionTag->set($this->request->data['PrescriptionTag']);
            if ($this->PrescriptionTag->validates()) {
                if ($this->PrescriptionTag->save($this->request->data['PrescriptionTag'])) {
                    $this->Session->setFlash(__('Tag added successfully.'), 'default', array(), 'success');
                    $this->redirect(array('controller'=>'supp','action'=>'prescription_tag_list','admin'=>true),null,false);

                } else {
                    $this->Session->setFlash(__('Sorry tag could not add.'), 'default', array(), 'error');
                }
            }
        }


    }

    public function admin_edit_prescription_tag($id=null)
    {
       // $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        $id = base64_decode($id);
        if($this->request->is(array('post','put')))
        {
            $this->request->data['PrescriptionTag']['id'] =$id;
            $this->request->data['PrescriptionTag']['type'] ='PRESCRIPTION_TAG';
            $this->PrescriptionTag->set($this->request->data['PrescriptionTag']);
            if ($this->PrescriptionTag->validates()) {
                if ($this->PrescriptionTag->save($this->request->data['PrescriptionTag'])) {
                    $this->Session->setFlash(__('Tag added successfully.'), 'default', array(), 'success');
                    $this->redirect(array('controller'=>'supp','action'=>'prescription_tag_list','admin'=>true),null,false);

                } else {
                    $this->Session->setFlash(__('Sorry tag could not add.'), 'default', array(), 'error');
                }
            }
        }


        $cms = $this->PrescriptionTag->find("first",array(
            "conditions" => array(
                "PrescriptionTag.id" => $id,
            ),
            'contain'=>false
        ));


        if (!$this->request->data) {
            $this->request->data = $cms;
        }
        $this->set('post',$cms);


    }

    public function admin_child_milestone_list()
    {
      //  $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        $this->paginate = array(
            "conditions" => array(
                "PrescriptionTag.thinapp_id" => 0,
                "PrescriptionTag.status" => 'ACTIVE',
                "PrescriptionTag.type" => 'CHILD_MILESTONE'
            ),
            'contain'=>false,
            'order'=>array('PrescriptionTag.id'=>'desc'),
            'limit'=>WEB_PAGINATION_LIMIT
        );
        $data = $this->paginate('PrescriptionTag');
        $this->set('channel',$data);
    }

    public function admin_add_child_milestone()
    {
        //$this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        if($this->request->is(array('post','put')))
        {
            $this->request->data['PrescriptionTag']['thinapp_id'] = 0;
            $this->request->data['PrescriptionTag']['type'] = "CHILD_MILESTONE";
            $this->PrescriptionTag->set($this->request->data['PrescriptionTag']);
            if ($this->PrescriptionTag->validates()) {
                if ($this->PrescriptionTag->save($this->request->data['PrescriptionTag'])) {
                    $this->Session->setFlash(__('Milestone added successfully.'), 'default', array(), 'success');
                    $this->redirect(array('controller'=>'supp','action'=>'child_milestone_list','admin'=>true),null,false);

                } else {
                    $this->Session->setFlash(__('Sorry milestone could not add.'), 'default', array(), 'error');
                }
            }
        }


    }

    public function admin_edit_child_milestone($id=null)
    {
       // $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User.User');
        $id = base64_decode($id);
        if($this->request->is(array('post','put')))
        {
            $this->request->data['PrescriptionTag']['thinapp_id'] = 0;
            $this->request->data['PrescriptionTag']['id'] =$id;
            $this->request->data['PrescriptionTag']['type'] = "CHILD_MILESTONE";
            $this->PrescriptionTag->set($this->request->data['PrescriptionTag']);
            if ($this->PrescriptionTag->validates()) {
                if ($this->PrescriptionTag->save($this->request->data['PrescriptionTag'])) {
                    $this->Session->setFlash(__('Milestone added successfully.'), 'default', array(), 'success');
                    $this->redirect(array('controller'=>'supp','action'=>'child_milestone_list','admin'=>true),null,false);

                } else {
                    $this->Session->setFlash(__('Sorry milestone could not add.'), 'default', array(), 'error');
                }
            }
        }


        $cms = $this->PrescriptionTag->find("first",array(
            "conditions" => array(
                "PrescriptionTag.id" => $id,
                "PrescriptionTag.type" => 'CHILD_MILESTONE'
            ),
            'contain'=>false
        ));


        if (!$this->request->data) {
            $this->request->data = $cms;
        }
        $this->set('post',$cms);


    }

    public function admin_delete_child_milestone($id=null)
    {
        $this->layout = false;
        $cms = $this->PrescriptionTag->delete(base64_decode($id));
        if ($cms) {
            $this->Session->setFlash(__('Delete successfully.'), 'default', array(), 'success');
        } else {
            $this->Session->setFlash(__('Sorry could not update.'), 'default', array(), 'error');
        }
        $this->redirect(array('controller'=>'supp','action'=>'child_milestone_list','admin'=>true));
    }

    public function admin_delete_prescription_tag($id=null)
    {
        $this->layout = false;
        $cms = $this->PrescriptionTag->delete(base64_decode($id));
        if ($cms) {
            $this->Session->setFlash(__('Delete successfully.'), 'default', array(), 'success');
        } else {
            $this->Session->setFlash(__('Sorry could not delete.'), 'default', array(), 'error');
        }
        $this->redirect(array('controller'=>'supp','action'=>'prescription_tag_list','admin'=>true));
    }

    public function admin_patient_day_wise_reports() {


    }

    public function admin_patient_day_wise_report_table()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $day_type = ($this->request->data['dt']);
            $report_for = ($this->request->data['rf']);
            $download_count = ($this->request->data['dc']);
            $from_date = ($this->request->data['fd']);
            $to_date = $this->request->data['td'];
            $data = Custom::get_doctor_reports_day_wise($download_count,$from_date,$to_date,$day_type,$report_for); 
            $this->set(compact(array('data')));
            $this->render('admin_patient_day_wise_report_table', 'ajax');
        }else{
            exit();
        }

    }

	public function admin_search_referral(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['referral']))
		{
			$pram['r'] = $reqData['referral'];
		}

		$this->redirect(
			array(
				"controller" => "supp",
				"action" => "list_referral_users",
				"?" => $pram,
			)
		);
	}

	public function admin_list_referral_users(){

		$login = $this->Session->read('Auth.User');
		$conditions = array();
		$searchData = $this->request->query;
		if(isset($searchData['r']) && !empty($searchData['r']))
		{
			$this->request->data['Search']['referral'] = $searchData['r'];
			$conditions["or"]["DoctorRefferal.reffered_mobile LIKE"] = '%'.$searchData['r'].'%';
			$conditions["or"]["DoctorRefferal.reffered_name LIKE"] = '%'.$searchData['r'].'%';
			$conditions["or"]["User.username LIKE"] = '%'.$searchData['r'].'%';
			$conditions["or"]["User.mobile LIKE"] = '%'.$searchData['r'].'%';
			$conditions["or"]["Thinapp.name LIKE"] = '%'.$searchData['r'].'%';
			$conditions["or"]["DoctorRefferal.status LIKE"] = '%'.$searchData['r'].'%';
		}
		
		$this->paginate = array(
			'fields'=>array('DoctorRefferal.*','DoctorRefferalUser.*','User.username','User.mobile','Thinapp.name'),
			"conditions" => $conditions,
			'contain'=>array('User','Thinapp','DoctorRefferalUser'),
			'order' => 'DoctorRefferal.id DESC',
			'limit' => 50
		);
		$data = $this->paginate('DoctorRefferal');
		//pr($data); die;
		$this->set('data',$data);

	}

	public function admin_change_referral_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$statusToChange = $this->request->data['status'];
			$comment = $this->request->data['comment'];
			$dateTime = date('Y-m-d H:i:s');
			
			
			$giftData['DoctorRefferal']['status'] = $statusToChange;
			$giftData['DoctorRefferal']['id'] = $rowID;
			$giftData['DoctorRefferal']['comment'] = $comment;

			if($statusToChange == 'CONVERTED')
			{
				$giftData['DoctorRefferal']['converted_datetime'] = $dateTime;
			}

			if($statusToChange == 'DENIED')
			{
				$giftData['DoctorRefferal']['denied_datetime'] = $dateTime;
			}
			
			if($statusToChange == 'CONTACTED')
			{
				$giftData['DoctorRefferal']['contacted_datetime'] = $dateTime;
			}


			if($this->DoctorRefferal->save($giftData['DoctorRefferal']))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_referral(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$rowID = $this->request->data['rowID'];


			$referralData = $this->DoctorRefferal->find("first",
			array(
				"conditions" => array("DoctorRefferal.id" => $rowID),
				"contain" => false,
				)
			);

			$str = "";

			$str = "<tr><td>NEW</td><td>CONVERTED</td><td>DENIED</td><td>CONTACTED</td><td>REREFRRED</td><td>PAID</td></tr>";
			$str .= "<tr><td>".$referralData['DoctorRefferal']['new_datetime']."</td><td>".$referralData['DoctorRefferal']['converted_datetime']."</td><td>".$referralData['DoctorRefferal']['dead_datetime']."</td><td>".$referralData['DoctorRefferal']['contacted_datetime']."</td><td>".$referralData['DoctorRefferal']['rereferred_datetime']."</td><td>".$referralData['DoctorRefferal']['paid_datetime']."</td></tr>";
			$str .= "<tr><td colspan='6' align='center'>".$referralData['DoctorRefferal']['comment']."</td></tr>";
			
			echo $str;






		}
	}

	public function admin_add_referral_users() {
	

		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			//pr($this->request->data); die;
			$dataToSave = $this->request->data;

			foreach($dataToSave['DoctorRefferal']['reffered_name'] AS $key => $rowData)
			{
			
				if($refferedMobile = $this->Custom->create_mobile_number($dataToSave['DoctorRefferal']['reffered_mobile'][$key],"+91"))
				{
					$dataToInsert = array();
					$dataToInsert['reffered_mobile'] = $refferedMobile;
					$dataToInsert['thinapp_id'] = $dataToSave['DoctorRefferal']['thinapp_id'][$key];
					$dataToInsert['reffered_name'] = $dataToSave['DoctorRefferal']['reffered_name'][$key];



					$thinapp_id = $dataToSave['DoctorRefferal']['thinapp_id'][$key];
					$userData = $this->User->find('first',array(
						"fields"=>array("User.id","User.mobile","User.username"),
						"conditions"=>array("User.thinapp_id"=>$thinapp_id,"User.role_id"=>5)	
					));
		
					$dataToInsert['user_id'] = $userData['User']['id'];
					$dataToInsert['mobile'] = $userData['User']['mobile'];
					$dataToInsert['thinapp_id'] = $thinapp_id;
					$dataToInsert['status'] = 'NEW';
					$dataToInsert['new_datetime'] = date('Y-m-d H:i:s');
		
					$existRefferal = $this->DoctorRefferal->find("count",array(
						"conditions"=>array(
							"DoctorRefferal.reffered_mobile" => $dataToInsert['reffered_mobile']
							)
					));
					
					$existUser = $this->User->find("count",array(
						"conditions"=>array(
							"User.mobile" => $dataToInsert['reffered_mobile'],
							"User.role_id" => 5,
							)
					));
		
					if($existUser > 0 || $existRefferal > 0 )
					{
						$dataToInsert['is_reffered_already'] = 'YES';
					}


					$refferalUser = $this->DoctorRefferalUser->find("first",array(
						"conditions"=>array(
							"DoctorRefferalUser.mobile" =>$userData['User']['mobile'],
							"DoctorRefferalUser.thinapp_id" =>$thinapp_id,
						)
					));

					if(sizeof($refferalUser) == 0)
					{
						$insertUser = array();
						$insertUser['user_id'] = $userData['User']['id'];
						$insertUser['thinapp_id'] = $thinapp_id;
						$insertUser['mobile'] = $userData['User']['mobile'];
						$insertUser['refferal_points'] = 0;
						$this->DoctorRefferalUser->saveAll($insertUser);
					}

		
					$this->DoctorRefferal->saveAll($dataToInsert);
				
				}

			}


			$this->Session->setFlash(__('Doctor Refferal updated successfully.'), 'default', array(), 'success');
			$this->redirect(array('controller' => 'supp', 'action' => 'list_referral_users'));

			/************************
			if($refferedMobile = $this->Custom->create_mobile_number($this->request->data['DoctorRefferal']['reffered_mobile'],"+91"))
			{
							$this->request->data['DoctorRefferal']['reffered_mobile'] = $refferedMobile;
							$thinapp_id = $this->request->data['DoctorRefferal']['thinapp_id'];
							$userData = $this->User->find('first',array(
								"fields"=>array("User.id","User.mobile","User.username"),
								"conditions"=>array("User.thinapp_id"=>$thinapp_id,"User.role_id"=>5)	
							));
				
							$this->request->data['DoctorRefferal']['user_id'] = $userData['User']['id'];
							$this->request->data['DoctorRefferal']['mobile'] = $userData['User']['mobile'];
							$this->request->data['DoctorRefferal']['thinapp_id'] = $thinapp_id;
							$this->request->data['DoctorRefferal']['status'] = 'NEW';
							$this->request->data['DoctorRefferal']['new_datetime'] = date('Y-m-d H:i:s');
				
							$existRefferal = $this->DoctorRefferal->find("count",array(
								"conditions"=>array(
									"DoctorRefferal.reffered_mobile" => $this->request->data['DoctorRefferal']['reffered_mobile']
									)
							));
							
							$existUser = $this->User->find("count",array(
								"conditions"=>array(
									"User.mobile" => $this->request->data['DoctorRefferal']['reffered_mobile'],
									"User.role_id" => 5,
									)
							));
				
							if($existUser > 0 || $existRefferal > 0 )
							{
								$this->request->data['DoctorRefferal']['is_reffered_already'] = 'YES';
							}


							$refferalUser = $this->DoctorRefferalUser->find("first",array(
								"conditions"=>array(
									"DoctorRefferalUser.mobile" =>$userData['User']['mobile'],
									"DoctorRefferalUser.thinapp_id" =>$thinapp_id,
								)
							));

							if(sizeof($refferalUser) == 0)
							{
								$insertUser = array();
								$insertUser['user_id'] = $userData['User']['id'];
								$insertUser['thinapp_id'] = $thinapp_id;
								$insertUser['mobile'] = $userData['User']['mobile'];
								$insertUser['refferal_points'] = 0;
								$this->DoctorRefferalUser->saveAll($insertUser);
							}

				
							if($this->DoctorRefferal->saveAll($this->request->data))
							{
								$this->Session->setFlash(__('Doctor Refferal updated successfully.'), 'default', array(), 'success');
								$this->redirect(array('controller' => 'supp', 'action' => 'list_referral_users'));
							}
							else
							{
								$this->Session->setFlash(__('Sorry, Doctor Refferal could not updated.'), 'default', array(), 'error');
							}

			}
			else
			{

						$this->Session->setFlash(__('Error, Please provide a valid mobile.'), 'default', array(), 'error');

			}

			************************/

		}








		$thinappList = $this->Thinapp->find('list',array(
			"conditions"=>array(
				"Thinapp.status"=>'ACTIVE',
			),
			'contain'=>false,
			'fields'=>array('Thinapp.id','Thinapp.name')
		));

		$this->set('thinappList',$thinappList);
	}

	public function admin_change_referral_payment() {
		$payTillLevel = -1;
		$payPoint = 20;

		if($this->request->is(array('post','put')))
		{
			$amount = (float)$this->request->data['amount'];
			$totalPoints = ((float)$amount*((float)$payPoint/100));
			$ID = $this->request->data['id'];
			$dateTime = date('Y-m-d H:i:s');

			$refrralData = $this->DoctorRefferal->find("first",array(
				"conditions"=>array("DoctorRefferal.id"=>$ID),
				"contain" => false
			));
			$updateInDoctorRefferals = array();
			$updateInDoctorRefferals['total_amount'] = (float)$amount;
			$updateInDoctorRefferals['total_refferal_points'] = (float)$totalPoints;
			$updateInDoctorRefferals['id'] = $refrralData['DoctorRefferal']['id'];

			$parentMobile = $refrralData['DoctorRefferal']['mobile'];
			
			$pointsGiven = 0;
			$pointsToCalculate = (float)$totalPoints;




			/****************************** */

			$pointsToCalculate = ((float)$pointsToCalculate*((100 - (float)$payPoint)/100));
			$pointsToGive = (float)$pointsToCalculate;
			$pointsGiven = ((float)$pointsGiven + (float)$pointsToCalculate);
			$pointsToCalculate = ((float)$totalPoints - (float)$pointsGiven);

			
			$doctorRefferalUserData = $this->DoctorRefferalUser->find('first',array(
				"contain"=>false,
				"conditions"=>array("DoctorRefferalUser.mobile"=>$parentMobile)
			));  
				
			$messageReffPoint = $totalPointsToSave = $doctorRefferalUserData['DoctorRefferalUser']['refferal_points'] = ((float)$doctorRefferalUserData['DoctorRefferalUser']['refferal_points'] + (float)$pointsToGive);
			$refferalUserID =  $doctorRefferalUserData['DoctorRefferalUser']['id'];
			$mobileToSave =  $doctorRefferalUserData['DoctorRefferalUser']['mobile'];
			$thinappToSave =  $doctorRefferalUserData['DoctorRefferalUser']['thinapp_id'];
			$userIDToSave =  $doctorRefferalUserData['DoctorRefferalUser']['user_id'];
			$this->DoctorRefferalUser->save($doctorRefferalUserData);

			$messageUserID = $insertToHistory['user_id'] = $userIDToSave;
			$messageThinapp = $insertToHistory['thinapp_id'] = $thinappToSave;
			$messageMobile = $insertToHistory['mobile'] = $mobileToSave;
			$insertToHistory['refferal_user_id'] = $refferalUserID;
			$insertToHistory['points_get'] = (float)$pointsGiven;
			$insertToHistory['total_points'] = (float)$totalPointsToSave;

			$this->DoctorRefferalHistory->save($insertToHistory);

			/********************************* */


			$level = ($payTillLevel > 0)?$payTillLevel:1;
			for($x = 0; $x < $level; $x++)
			{
				if($payTillLevel == -1){ $x--; }
				
				$parent = $this->DoctorRefferal->find("first",array(
					"conditions"=>array("DoctorRefferal.reffered_mobile" => $parentMobile,
					"DoctorRefferal.is_reffered_already" => 'NO' ),
					"contain" => false,
				));
				//pr($parent); die;
				if(sizeof($parent) == 1)
				{
					$pointsToCalculate = ((float)$pointsToCalculate*((100 - (float)$payPoint)/100));
					$pointsToGive = (float)$pointsToCalculate;
					$pointsGiven = ((float)$pointsGiven + (float)$pointsToCalculate);
					$pointsToCalculate = ((float)$totalPoints - (float)$pointsGiven);

					$parentMobile = $parent['DoctorRefferal']['mobile'];
					
					$doctorRefferalUserData = $this->DoctorRefferalUser->find('first',array(
						"contain"=>false,
						"conditions"=>array("DoctorRefferalUser.mobile"=>$parentMobile)
					));
						
					$totalPointsToSave = $doctorRefferalUserData['DoctorRefferalUser']['refferal_points'] = ((float)$doctorRefferalUserData['DoctorRefferalUser']['refferal_points'] + (float)$pointsToGive);
					$refferalUserID =  $doctorRefferalUserData['DoctorRefferalUser']['id'];
					$mobileToSave =  $doctorRefferalUserData['DoctorRefferalUser']['mobile'];
					$thinappToSave =  $doctorRefferalUserData['DoctorRefferalUser']['thinapp_id'];
					$userIDToSave =  $doctorRefferalUserData['DoctorRefferalUser']['user_id'];
					$this->DoctorRefferalUser->save($doctorRefferalUserData);

					$insertToHistory['user_id'] = $userIDToSave;
					$insertToHistory['thinapp_id'] = $thinappToSave;
					$insertToHistory['mobile'] = $mobileToSave;
					$insertToHistory['refferal_user_id'] = $refferalUserID;
					$insertToHistory['points_get'] = (float)$pointsGiven;
					$insertToHistory['total_points'] = (float)$totalPointsToSave;

					$this->DoctorRefferalHistory->save($insertToHistory);
				}
				else
				{
					break;
				}
			}

			$updateInDoctorRefferals['remining_referral_points'] = ($totalPoints-$pointsGiven);
			$updateInDoctorRefferals['paid_datetime'] = $dateTime;
			$updateInDoctorRefferals['status'] = 'PAID';
			//pr($updateInDoctorRefferals); die;
			$this->DoctorRefferal->save($updateInDoctorRefferals);
			
			$message = "Congrats. You have earned ".$messageReffPoint." points. Earn more by referring your doctor friends.";
			Custom::send_single_sms($messageMobile,$message,$messageThinapp);
			
			
			
						$sendArrayCashback = array(
                            'channel_id' => 0,
                            'thinapp_id' => $messageThinapp,
                            'flag' => 'DOCTOR_REFERRAL',
                            'title' => 'Reward Points Received!',
                            'message' => $message,
                            'description' => '',
                            'chat_reference' => '',
                            'type' => 'DOCTOR_REFERRAL',
                            'module_type' => 'DOCTOR_REFERRAL',
                            'module_type_id' => 0,
                            'firebase_reference' => ""
                        );
					
					Custom::send_notification_by_user_id($sendArrayCashback, array($refferalUserID), $messageThinapp);
			
			
			$dataToShow['status'] = 1;
			echo json_encode($dataToShow);
			die;
		}		
	}


    public function admin_search_app_install_user(){
        $reqData = $this->request->query;
        $pram = array();
        if(!empty($reqData['name']))
        {
            $pram['n'] = $reqData['name'];
        }
        if(!empty($reqData['type']))
        {
            $pram['ty'] = $reqData['type'];
        }
        if(!empty($reqData['username']))
        {
            $pram['u'] = $reqData['username'];
        }

        if(!empty($reqData['from_date']))
        {
            $pram['f'] = $reqData['from_date'];
        }

        if(!empty($reqData['to_date']))
        {
            $pram['t'] = $reqData['to_date'];
        }

        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "app_install_user",
                "?" => $pram,
            )
        );


    }

    public function admin_app_install_user()
    {
        $login = $this->Session->read('Auth.User');
        $conditions["Thinapp.category_name"] = "DOCTOR";
        $searchData = $this->request->query;
        $label = "User.created";
        if(isset($searchData['n']) && !empty($searchData['n']))
        {
            $this->request->data['Search']['name'] = $searchData['n'];
            $conditions["Thinapp.name LIKE"] = '%'.$searchData['n'].'%';
        }
        if(isset($searchData['u']) && !empty($searchData['u']))
        {
            $this->request->data['Search']['username'] = $searchData['u'];
            $conditions['OR'] = array(
                "User.username LIKE" => '%'.$searchData['u'].'%',
                "User.mobile LIKE" => '%'.$searchData['u'].'%',
            );

        }
        if(isset($searchData['ty']) && !empty($searchData['ty']))
        {
            $this->request->data['Search']['type'] = $searchData['ty'];
            $conditions["User.app_installed_status"] = $searchData['ty'];

            if($searchData['ty'] == "UNINSTALLED"){
                $label = "User.modified";
            }
            if(isset($searchData['f']) && !empty($searchData['f']))
            {
                $this->request->data['Search']['from_date'] = $searchData['f'];
                $conditions["DATE($label) >="] = date('Y-m-d',strtotime($searchData['f']));
            }
            if(isset($searchData['t']) && !empty($searchData['t']))
            {
                $this->request->data['Search']['to_date'] = $searchData['t'];
                $conditions["DATE($label) <="] = date('Y-m-d',strtotime($searchData['t']));
            }

        }

        if(!isset($searchData['f']) && !isset($searchData['t'])){
            $conditions["DATE($label) >="] = date('Y-m-d');
            $conditions["DATE($label) <="] = date('Y-m-d');
        }

        $this->paginate = array(
            "conditions" => $conditions,
            'fields'=>array('Thinapp.name','User.username','User.mobile','User.created','User.modified','User.app_installed_status'),
            'contain'=>array('Thinapp'),
            'order' => 'User.username ASC',
            'limit' => 30
        );
        $data = $this->paginate('User');

        $this->set('data',$data);

    }


    public function admin_search_stats_report(){

        $reqData = $this->request->query;
        $pram = array();
        if(!empty($reqData['from_date']))
        {
            $pram['fd'] = $reqData['from_date'];
        }
        if(!empty($reqData['to_date']))
        {
            $pram['td'] = $reqData['to_date'];
        }
        if(!empty($reqData['report_for']))
        {
            $pram['rf'] = $reqData['report_for'];
        }
        if(!empty($reqData['ignore_date']))
        {
            $pram['id'] = $reqData['ignore_date'];
        }


        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "app_stats_report_list",
                "?" => $pram,
            )
        );
    }

    public function admin_app_stats_report_list(){
        $this->layout = 'support_admin_home';
        $login = $this->Session->read('Auth.User.User');
        $thinapp_id = $login['thinapp_id'];
        $cus_condition['AppointmentCustomer.thinapp_id'] =$thinapp_id;
        $cus_condition['AppointmentCustomer.status'] ='ACTIVE';
        $searchData = $this->request->query;


        $report_for = "USER_STATS";
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        $this->request->data['Search']['from_date'] = date('d/m/Y');
        $this->request->data['Search']['to_date'] = date('d/m/Y');

        $ignore_date = 0;
        if(isset($searchData['fd']) && !empty($searchData['fd']) && isset($searchData['td']) && !empty($searchData['td']))
        {

            $this->request->data['Search']['from_date'] = $searchData['fd'];
            $this->request->data['Search']['to_date'] = $searchData['td'];

            $from_date = DateTime::createFromFormat('d/m/Y', $searchData['fd']);
            $from_date =$from_date->format('Y-m-d');

            $to_date = DateTime::createFromFormat('d/m/Y', $searchData['td']);
            $to_date =$to_date->format('Y-m-d');

            $report_for = $searchData['rf'];
            $ignore_date = isset($searchData['id'])?1:0;
            $this->request->data['Search']['report_for'] = $report_for;
            $this->request->data['Search']['ignore_date'] = $ignore_date;

        }

        $data_array=array();
        $query=$condition=$condition_2 = '';
        if($report_for == "USER_STATS"){
            if($ignore_date == 0){
                $condition = " and DATE(u.created) >= '$from_date' and DATE(u.created) <= '$to_date' ";

                $condition_2 = " and DATE(uu.modified) >= '$from_date' and DATE(uu.modified) <= '$to_date' ";
            }
            $query = "select t.name as app_name, count(u.id) as total,  (select count(id) from users as uu where uu.thinapp_id = u.thinapp_id and uu.app_installed_status ='INSTALLED' $condition_2) AS installed, (select count(id) from users as uu where uu.thinapp_id = u.thinapp_id and uu.app_installed_status ='UNINSTALLED' $condition_2) AS uninstalled  from users as u join thinapps as t on u.thinapp_id= t.id  where t.category_name IN('DOCTOR','HOSPITAL') AND u.thinapp_id > 0  AND t.status ='ACTIVE' $condition  group by u.thinapp_id order by total desc";
        }else if($report_for == "APPOINTMENT_STATS"){
            if($ignore_date == 0){
                $condition = " WHERE DATE(acss.appointment_datetime) >= '$from_date' and DATE(acss.appointment_datetime) <= '$to_date' ";
            }
            $query = "select t.name as app_name, count(*) as total from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id   $condition  group by acss.thinapp_id order by total desc";
        }else if($report_for == "PRESCRIPTION_STATS"){
            if($ignore_date == 0){
                $condition = " AND DATE(acss.created) >= '$from_date' and DATE(acss.created) <= '$to_date' ";
            }
            $query = "select t.name as app_name, count(*) as total from drive_files as acss join thinapps as t on t.id = acss.thinapp_id where  acss.is_pad_prescription = 'YES' $condition group by acss.thinapp_id order by total desc";
        }



        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data_array = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }
        $this->set(compact('data_array','report_for'));

    }

      public function admin_support_admin_list()
    {

        $login = $this->Session->read('Auth.User');
        $conditions["User.role_id"] = 4;
        $this->paginate = array(
            "conditions" => $conditions,
            'fields'=>array('User.id','User.username','User.status','User.mobile','User.password'),
            'contain'=>false,
            'order' => 'User.username ASC',
        );
        $data = $this->paginate('User');
        $this->set('data',$data);



    }
    public function admin_change_support_status()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $pat_id = base64_decode($this->request->data['pat_id']);
            $status = $this->request->data['status'];
            $return =false;
            $return = $this->User->updateAll(array("User.status"=>"'$status'"),array("User.id"=>$pat_id));
            if($return){
                $response['status'] = 1;
                $response['message'] = "Status change successfully.";
                $file_name = Custom::encrypt_decrypt('encrypt',"user_$pat_id");
                WebservicesFunction::deleteJson(array($file_name),'user');
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry could not changed admit status";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }

    public function admin_add_support_admin()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $username = ($this->request->data['name']);
            $edit = ($this->request->data['edit']);
            $mobile = Custom::create_mobile_number($this->request->data['mobile']);
            $password = md5($this->request->data['password']);


            if (empty($username)) {
                $response['status'] = 0;
                $response['message'] = 'Please enter name';
            } else if (empty($mobile)) {
                $response['status'] = 0;
                $response['message'] = 'Please enter mobile';
            } else if (empty($password)) {
                $response['status'] = 0;
                $response['message'] = 'Please enter password';
            }else {


                $support_admin = array();
                $add_user = true;
                $label = $already_user =false;
                $query = "select * from users  where mobile = '$mobile' and role_id = 4 limit 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $support_admin = mysqli_fetch_assoc($service_message_list);
                }
                if (empty($support_admin)) {
                    $role_id = 4;
                    $sql = "INSERT INTO users (role_id, username, mobile, password) VALUES (?, ?, ?, ?)";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssss', $role_id, $username, $mobile, $password);
                    if ($stmt->execute() ) {
                        $response['status'] = 1;
                        $response['message'] = "Support user added successfully.";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry could not add user";
                    }

                } else {
                    $id = $support_admin['id'];
                    if($support_admin['status'] == 'N' || $edit =='true'){
                        $status = 'Y';
                        $sql = "UPDATE users set username =?, mobile =?, password =?, status =? where id =?";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('sssss', $username, $mobile, $password, $status, $id);
                        if ($stmt->execute() ) {
                            $response['status'] = 1;
                            $response['message'] = "Support user updated successfully.";
                            $file_name = Custom::encrypt_decrypt('encrypt',"user_$id");
                            WebservicesFunction::deleteJson(array($file_name),'user');
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry could not update user";
                        }
                    }else{
                        $response['status'] = 0;
                        $response['message'] = "User already exist";
                    }

                }

            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }


    public function admin_print_offline_barcode()
    {


        $login = $this->Session->read('Auth.User');
        $user_id = $login['id'];
        if ($this->request->is(array('post', 'put'))) {

            $total_page = $this->request->data['Barcode']['page'];
            if($total_page > 0){
                $connection = ConnectionUtil::getConnection();
                $connection->autocommit(false);
                $module = $this->request->data['Barcode']['module'];
                $app_id = $this->request->data['Barcode']['thinapp_id'];
                $pdf = new PDF_Code128();
                $bunch_id = date('Ymdhis');
                for($page=0;$page<$total_page;$page++){
                    $last_barcode =array();
                    $query =  "select prefix, module, barcode, barcode_number from offline_barcodes where module ='$module' order by id desc limit 1 ";

                    $service_message_list = $connection->query($query);
                    if ($service_message_list->num_rows) {
                        $last_barcode = mysqli_fetch_assoc($service_message_list);
                    }
                    $created = Custom::created();
                    $query = "INSERT INTO offline_barcodes (bunch_id, create_by_user_id, thinapp_id, created) values(?, ?, ?, ?)";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('ssss', $bunch_id, $user_id, $app_id, $created);
                    if ($stmt->execute()) {
                        $barcode = "";
                        $last_barcode_id = $stmt->insert_id;
                        $last_number = @$last_barcode['barcode_number'];
                        $last_number = $last_number+1;
                        if($last_number <= 99999 ){
                            $prefix = empty($last_barcode)?'A':$last_barcode['prefix'];
                            $number =sprintf("%05d", $last_number);
                            $barcode =  strtoupper($prefix).strtoupper($module).$number;
                        }else{
                            $prefix = ++$last_barcode['prefix'];
                            $number =sprintf("%05d", 1);
                            $barcode =  strtoupper($prefix).strtoupper($module).$number;
                        }
                        $query = "update offline_barcodes set barcode =?, `module`= ?, prefix= ?, barcode_number = ? where id = ?";
                        $update = $connection->prepare($query);
                        $update->bind_param('sssss', $barcode, $module, $prefix, $number,  $last_barcode_id);
                        if ($update->execute()) {
                            $pdf->AddPage('','A4',0);
                            $pdf->SetFont('Arial','');
                            $pdf->Code128(25,8,$barcode,29,13);
                            //$pdf->Code128(22,8,$barcode,31,13);
                        }
                    }
                }
                $filename=$bunch_id.".pdf";
                if(empty($pdf->Output(LOCAL_PATH.DS.'app'.DS.'webroot'.DS.'offline-barcodes'.DS.$filename,'F'))){
                    $connection->commit();
                    $this->Session->write('filename',$filename);
                    $this->Session->setFlash(__('Barcode generated successfully'), 'default', array(), 'success');

                }else{
                    $connection->rollback();
                    $this->Session->setFlash(__('Sorry barcode could not .'), 'default', array(), 'error');
                }
            }else{
                $this->Session->setFlash(__('Please enter valid page number'), 'default', array(), 'error');
            }



        }


        $last_record_array = array();
        $connection = ConnectionUtil::getConnection();
        $query = "select t.name, ob.bunch_id, ob.created, count(ob.bunch_id) as total_pages from offline_barcodes as ob left join thinapps as t on t.id = ob.thinapp_id group by ob.bunch_id order by ob.created desc  limit 10";
        $last_records = $connection->query($query);
        if ($last_records->num_rows) {
            $last_record_array = mysqli_fetch_all($last_records, MYSQLI_ASSOC);
        }

        $this->set(compact('filename','last_record_array'));


    }

    public function admin_update_booking_convenience_fee(){
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();

            $field = $this->request->data['field'];
            $value = $this->request->data['value'];
            $thinappID = $this->request->data['thinappID'];



            $return =false;
            $return = $this->Thinapp->updateAll(array("Thinapp.".$field =>"'$value'"),array("Thinapp.id"=>$thinappID));
            if($return){
                $response['status'] = 1;
                $response['message'] = "Successful.";
            }else{
                $response['status'] = 0;
                $response['message'] = "Faild.";
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }
    }



    public function admin_search_booking_convenience_report(){
        $reqData = $this->request->data['Search'];
        $pram = array();

        if (!empty($reqData['from_date'])) {
            $pram['f'] = $reqData['from_date'];
        }
        if (!empty($reqData['to_date'])) {
            $pram['t'] = $reqData['to_date'];
        }
        if (!empty($reqData['appointment_booked_from'])) {
            $pram['a'] = $reqData['appointment_booked_from'];
        }
        if (!empty($reqData['account_type'])) {
            $pram['at'] = $reqData['account_type'];
        }
        if (!empty($reqData['thinapp'])) {
            $pram['th'] = $reqData['thinapp'];
        }


        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "admin_booking_convenience_report",
                "?" => $pram,
            )
        );
    }


    public function admin_booking_convenience_report(){

        $this->layout = 'support_admin_home';

        $today = date("Y-m-d");

        $searchData = $this->request->query;
        $condition = '';
        if (isset($searchData['f']) && !empty($searchData['f']) && !empty($searchData['t']) && isset($searchData['t'])) {


            $this->request->data['Search']['from_date'] = $searchData['f'];
            $from_date = DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
            $this->request->data['Search']['to_date'] = $searchData['t'];
            $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
            $condition .= " and  DATE(booking_convenience_fee_details.created) BETWEEN '$from_date' and '$to_date'";

        } else {
            $today = date("d/m/Y");
            $search_today = date("Y-m-d");
            $this->request->data['Search']['to_date'] = $this->request->data['Search']['from_date'] = $today;
            $condition .= " and  DATE(booking_convenience_fee_details.created) BETWEEN '$search_today' and '$search_today'";

        }

        if (isset($searchData['a'])) {
            $this->request->data['Search']['appointment_booked_from'] = $searchData['a'];
            $condition .= " and  appointment_customer_staff_services.appointment_booked_from = '".$searchData['a']."'";
        }

        if (isset($searchData['at'])) {
            $this->request->data['Search']['account_type'] = $searchData['at'];
            $condition .= " and  booking_convenience_fee_details.payment_account = '".$searchData['at']."'";
        }

        if (isset($searchData['th'])) {
            $this->request->data['Search']['thinapp'] = $searchData['th'];
            $condition .= " and  booking_convenience_fee_details.thinapp_id = '".$searchData['th']."'";
        }



        $allSql = "SELECT t.booking_convenience_fee_emergency, appointment_customer_staff_services.consulting_type, `booking_convenience_fee_details`.*, thinapps.name AS thinapp_name, IFNULL(appointment_staffs.name,users.username) as username, IFNULL(`childrens`.`child_name`,`appointment_customers`.`first_name`) as customer_name,`appointment_customer_staff_services`.`appointment_booked_from` FROM `booking_convenience_fee_details` LEFT JOIN `users` ON (`users`.`id` = `booking_convenience_fee_details`.`created_by_user_id`) LEFT JOIN appointment_staffs ON (appointment_staffs.user_id = booking_convenience_fee_details.created_by_user_id AND appointment_staffs.status = 'ACTIVE' AND appointment_staffs.user_id > 0) LEFT JOIN `appointment_customers` ON (`appointment_customers`.`id` = `booking_convenience_fee_details`.`appointment_customer_id`) LEFT JOIN `childrens` ON (`childrens`.`id` = `booking_convenience_fee_details`.`children_id`) LEFT JOIN `appointment_customer_staff_services` ON (`appointment_customer_staff_services`.`id` = `booking_convenience_fee_details`.`appointment_customer_staff_service_id`) LEFT JOIN thinapps ON (thinapps.id = `appointment_customer_staff_services`.`thinapp_id`) WHERE `booking_convenience_fee_details`.`status` = 'ACTIVE' ".$condition." GROUP BY `booking_convenience_fee_details`.`id` ORDER BY `booking_convenience_fee_details`.`id` DESC";
        $connection = ConnectionUtil::getConnection();
        $allRS = $connection->query($allSql);
        $allData  = mysqli_fetch_all($allRS,MYSQL_ASSOC);

        $reportTitle = '';


        $thinAppList = $this->Thinapp->find('list',array('order'=>array('Thinapp.name'=>'asc')));

        $this->set(array('allData'=>$allData,'thinAppList'=>$thinAppList,'reportTitle'=>$reportTitle));



    }

  	public function admin_search_app_active_admin(){
        $reqData = $this->request->data['Search'];
        $pram = array();

        if(!empty($reqData['thinapp_id']))
        {
            $pram['t'] = $reqData['thinapp_id'];
        }

        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "app_active_admin",
                "?" => $pram,
            )
        );


    }
	
	public function admin_syn_active_admin($thin_app_id=null){
        $pram = array();
        if(!empty($thin_app_id))
        {
            $pram['t'] = $thin_app_id;
            $response = Custom::synchronize_active_login($thin_app_id);
        }

        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "app_active_admin",
                "?" => $pram,
            )
        );


    }

    public function admin_app_active_admin()
    {
        $login = $this->Session->read('Auth.User');
        $conditions["Thinapp.category_name"] = "DOCTOR";
        $searchData = $this->request->query;
        $active_admin=array();
        if(isset($searchData['t']) && !empty($searchData['t']))
        {
            $this->request->data['Search']['thinapp_id'] =$thin_app_id = $searchData['t'];
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT ulds.id, ud.operator_name, ud.email, ud.mobile,ud.brand_name,ud.modal_name,ulds.created FROM user_login_device_stats AS ulds JOIN user_devices AS ud ON ud.id = ulds.user_device_id WHERE ulds.id  IN ( select ulds_i.id  from user_login_device_stats as ulds_i where ulds_i.thinapp_id =$thin_app_id AND ulds_i.role_id = 5 AND ulds_i.`status` ='ACTIVE' GROUP BY ulds_i.user_device_id order by ulds_i.id desc )  order by ulds.created desc";
            $last_records = $connection->query($query);
            if ($last_records->num_rows) {
                $active_admin = mysqli_fetch_all($last_records, MYSQLI_ASSOC);
            }
        }

        $this->set(compact('active_admin'));

    }
    public function admin_logout_from_device()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $login_device_id = base64_decode($this->request->data['li']);
            $post = array();
            $login = $this->Session->read('Auth.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] = $login['id'];
            $post['thin_app_id'] = $login['thinapp_id'];
            $post['login_stats_id'] = $login_device_id;
            $post['delete_by'] = "SUPPORT_ADMIN";
            return WebServicesFunction_2_3::logout_from_device($post);

        } else {
            exit();
        }

    }

	public function admin_booking_convenience_modal()
    {
        if($this->request->is('ajax')) {
                $thinappData = $mediatorArray = array();
                $thin_app_id = $this->request->data['rowID'];
                $connection = ConnectionUtil::getConnection();
                $query = "SELECT t.booking_convenience_fee_emergency, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, t.booking_convenience_fee_online_consutlting_terms_condition,t.booking_convenience_gst_percentage, scma.id as assoc_id, t.booking_convenience_fee,t.booking_doctor_share_percentage,t.booking_payment_getway_fee_percentage,t.booking_convenience_fee_restrict_ivr, t.booking_convenience_fee_terms_condition,scma.* FROM thinapps as t left join smart_clinic_mediator_associate as scma on t.id = scma.thinapp_id where t.id = $thin_app_id";
                $last_records = $connection->query($query);
                if ($last_records->num_rows) {
                    $thinappData = mysqli_fetch_assoc($last_records);
                }

                $query = "SELECT id,CONCAT(name,' (',mobile,')') as label FROM smart_clinic_mediators where status ='ACTIVE' order by name asc ";
                $last_records = $connection->query($query);
                if ($last_records->num_rows) {
                    $mediatorArray = mysqli_fetch_all($last_records,MYSQL_ASSOC);
                    $mediatorArray = array_column($mediatorArray,'label','id');
                }

                $this->set(compact('thinappData','mediatorArray','thin_app_id'));
        }else{
            exit();
        }
    }

    public function admin_save_booking_convenience(){

        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $response = array();
            $thin_app_id = base64_decode($this->request->data['form_id']);
            $assoc_id = base64_decode($this->request->data['assoc_id']);
            $post = $this->request->data;
            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $query = "update thinapps set booking_convenience_fee_emergency=?, booking_convenience_fee_video=?, booking_convenience_fee_audio=?, booking_convenience_fee_chat=?, booking_convenience_fee_online_consutlting_terms_condition=?, booking_convenience_gst_percentage=?, booking_convenience_fee=?,booking_doctor_share_percentage=?,booking_payment_getway_fee_percentage=?,booking_convenience_fee_restrict_ivr=?,booking_convenience_fee_terms_condition=? where id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ssssssssssss', $post['booking_convenience_fee_emergency'], $post['booking_convenience_fee_video'],$post['booking_convenience_fee_audio'],$post['booking_convenience_fee_chat'], $post['booking_convenience_fee_online_consutlting_terms_condition'], $post['booking_convenience_gst_percentage'], $post['booking_convenience_fee'],$post['booking_doctor_share_percentage'],$post['booking_payment_getway_fee_percentage'],$post['booking_convenience_fee_restrict_ivr'],$post['booking_convenience_fee_terms_condition'], $thin_app_id);
            if($stmt->execute()){
                if(!empty($assoc_id)){
                    $query = "update smart_clinic_mediator_associate set primary_owner_id=?,primary_owner_share_percentage=?,primary_mediator_id=?,primary_mediator_share_percentage=?,secondary_owner_id=?,secondary_owner_share_percentage=?,secondary_mediator_id=?,secondary_mediator_share_percentage=?,created=?,modified=? where id = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('sssssssssss',  $post['primary_owner_id'],$post['primary_owner_share_percentage'],$post['primary_mediator_id'],$post['primary_mediator_share_percentage'],$post['secondary_owner_id'],$post['secondary_owner_share_percentage'],$post['secondary_mediator_id'],$post['secondary_mediator_share_percentage'],$created,$created, $assoc_id);
                    $update = $stmt->execute();
                }else{
                    $query = "insert into smart_clinic_mediator_associate  (thinapp_id, primary_owner_id, primary_owner_share_percentage, primary_mediator_id, primary_mediator_share_percentage, secondary_owner_id, secondary_owner_share_percentage, secondary_mediator_id, secondary_mediator_share_percentage, created, modified) values (?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('sssssssssss',$thin_app_id, $post['primary_owner_id'],$post['primary_owner_share_percentage'],$post['primary_mediator_id'],$post['primary_mediator_share_percentage'],$post['secondary_owner_id'],$post['secondary_owner_share_percentage'],$post['secondary_mediator_id'],$post['secondary_mediator_share_percentage'],$created,$created);
                    $update = $stmt->execute();
                }
                if($update===true){
                    $connection->commit();
                    $response['status']=1;
                    $response['message']="Update Successfully";
                }else{
                    $connection->rollback();
                    $response['status']=0;
                    $response['message']="Update Failed";
                }
            }
            $response = json_encode($response, true);
            echo $response;
            exit();
        }

    }

	public function admin_search_franchise_report()
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

        if (!empty($reqData['year'])) {
            $pram['y'] = $reqData['year'];
        }


        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "franchise_report",
                "?" => $pram,
            )
        );
    }

    public function admin_franchise_report()
    {

        $login = $this->Session->read('Auth.User');

        $condition = " bcfd.status='ACTIVE'";
        $searchData = $this->request->query;

        if (isset($searchData['s']) && !empty($searchData['s'])) {
            $this->request->data['Search']['settled'] = $settled = $searchData['s'];
            $condition .= " and bcfd.is_settled = '$settled'";
        }

        if (isset($searchData['t']) && !empty($searchData['t'])) {
            $this->request->data['Search']['thinapp_id'] = $thin_app1_id = $searchData['t'];
            $condition .= " and bcfd.thinapp_id = $thin_app1_id";
        }

        if (isset($searchData['m'])) {
            if ($searchData['m'] != '-1') {
                $this->request->data['Search']['month'] = $month = $searchData['m'];
                $condition .= " and MONTH(bcfd.created) = $month";
            }
        }else{
            $this->request->data['Search']['month'] = $month = date('m');
            $condition .= " and MONTH(bcfd.created) = $month";
        }

        if (isset($searchData['y'])){
            if ($searchData['y'] != '-1') {
                $this->request->data['Search']['year'] = $year = $searchData['y'];
                $condition .= " and YEAR(bcfd.created) = $year";
            }
        }else{
            $this->request->data['Search']['year'] = $year = date('Y');
            $condition .= " and YEAR(bcfd.created) = $year";
        }



        if (isset($searchData['r']) && !empty($searchData['r'])) {
            $this->request->data['Search']['role'] = $role = $searchData['r'];
            $condition .= " and bcfd.$role > 0";
        }




        $query = "SELECT MONTH(bcfd.created) as month_lbl, bcfd.thinapp_id, bcfd.settled_date, SUM(bcfd.booking_doctor_share_fee) as booking_doctor_share_fee, t.name as app_name, CONCAT(MONTHNAME(bcfd.created)) AS label,DATE_FORMAT(bcfd.created,'%Y') as year_lbl,  SUM(IF(bcfd.primary_owner_id > 0,bcfd.primary_owner_share_fee,0)) AS primary_owner_share_fee, SUM(IF(bcfd.secondary_owner_id > 0,bcfd.secondary_owner_share_fee,0)) AS secondary_owner_share_fee, SUM(IF(bcfd.primary_mediator_id > 0,bcfd.primary_mediator_share_fee,0)) AS primary_mediator_share_fee, SUM(IF(bcfd.secondary_owner_id > 0,bcfd.secondary_mediator_share_fee,0)) AS secondary_mediator_share_fee, bcfd.is_settled  FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id  WHERE $condition group by bcfd.thinapp_id,label order by bcfd.created desc ";
        $connection = ConnectionUtil::getConnection();
        $data_list =array();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data_list = mysqli_fetch_all($list,MYSQL_ASSOC);
        }

        $this->set(compact('data_list'));
    }

    public function admin_franchise_fee_share_modal()
    {
        if($this->request->is('ajax')) {
            $thinappData = $mediatorArray = array();
            $thin_app_id = base64_decode($this->request->data['t']);
            $month = $this->request->data['m'];
            $year = $this->request->data['y'];
            $connection = ConnectionUtil::getConnection();

            $condition = "t.id = $thin_app_id AND MONTH(bcfd.created) = '$month' AND YEAR(bcfd.created) = '$year'";
            $doctor_query = "SELECT  'Doctor Share' AS label, SUM(bcfd.booking_doctor_share_fee) AS fee, staff.name FROM booking_convenience_fee_details AS bcfd JOIN  thinapps as t on t.id = bcfd.thinapp_id JOIN appointment_customer_staff_services AS acss ON acss.id = bcfd.appointment_customer_staff_service_id JOIN appointment_staffs AS   staff ON staff.id = acss.appointment_staff_id where $condition GROUP BY staff.id ";
            $menage_query = "SELECT CONCAT(MONTHNAME(bcfd.created),'-',YEAR(bcfd.created)) as label, t.name as app_name, SUM(bcfd.booking_mengage_share_fee) AS booking_mengage_share_fee, SUM(bcfd.booking_payment_getway_fee) AS booking_payment_getway_fee, SUM(bcfd.booking_convenience_gst_fee) AS booking_convenience_gst_fee,bcfd.is_settled, bcfd.settled_date FROM booking_convenience_fee_details AS bcfd JOIN  thinapps as t on t.id = bcfd.thinapp_id where $condition ";
            $join = "JOIN  thinapps as t on t.id = bcfd.thinapp_id ";
            $query1 = "SELECT 'primary_owner_id' AS label, SUM(bcfd.primary_owner_share_fee) AS fee, scm.name FROM booking_convenience_fee_details AS bcfd $join JOIN smart_clinic_mediators AS scm ON scm.id = bcfd.primary_owner_id where $condition and bcfd.primary_owner_share_fee > 0 GROUP BY bcfd.primary_owner_id";
            $query2 = "SELECT 'secondary_owner_id' AS label, SUM(bcfd.secondary_owner_share_fee) AS fee, scm.name FROM booking_convenience_fee_details AS bcfd $join JOIN smart_clinic_mediators AS scm ON scm.id = bcfd.secondary_owner_id where $condition and bcfd.secondary_owner_share_fee > 0 GROUP BY bcfd.secondary_owner_id ";
            $query3 = "SELECT  'primary_mediator_id' AS label, SUM(bcfd.primary_mediator_share_fee) AS fee, scm.name FROM booking_convenience_fee_details AS bcfd $join JOIN smart_clinic_mediators AS scm ON scm.id = bcfd.primary_mediator_id where $condition and bcfd.primary_mediator_share_fee > 0 GROUP BY bcfd.primary_mediator_id  ";
            $query4 = "SELECT 'secondary_mediator_id' AS label, SUM(bcfd.secondary_mediator_share_fee) AS fee, scm.name FROM booking_convenience_fee_details AS bcfd $join JOIN smart_clinic_mediators AS scm ON scm.id = bcfd.secondary_mediator_id where $condition and bcfd.secondary_mediator_share_fee > 0 GROUP BY bcfd.secondary_mediator_id";
            $query = " SELECT * FROM (($doctor_query) UNION ALL ($query1) UNION ALL ($query2) UNION ALL ($query3) UNION ALL ($query4) ) as final";


            $list = $connection->query($query);
            $final_array=array();
            if ($list->num_rows) {
                $data = mysqli_fetch_all($list,MYSQL_ASSOC);
                foreach ($data as $key =>$value){
                    $value['label'] = ($value['label']=='Doctor Share')?$value['label']:Custom::getFranchiseRoleLabel($value['label']);
                    $final_array['list'][$value['label']][] = $value;
                }
            }
            $last_records = $connection->query($menage_query);
            if ($last_records->num_rows) {
                $final_array['data'] = mysqli_fetch_assoc($last_records);
            }
            $this->set(compact('final_array','thin_app_id','month','year'));
        }else{
            exit();
        }
    }

    public function admin_settle_franchise_fee()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $thin_app_id = base64_decode($this->request->data['t']);
            $month = $this->request->data['m'];
            $year = $this->request->data['y'];
            $settle_date =date('Y-m-d H:i:s');
            if($this->BookingConvenienceFeeDetail->updateAll(array("BookingConvenienceFeeDetail.is_settled"=>"'YES'","BookingConvenienceFeeDetail.settled_date"=>"'$settle_date'"),array("BookingConvenienceFeeDetail.thinapp_id"=>$thin_app_id,"MONTH(BookingConvenienceFeeDetail.created)"=>"$month","YEAR(BookingConvenienceFeeDetail.created)"=>"$year"))){
                $response['status'] = 1;
                $response['message'] = "Update Successfully.";
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry could not update";
            }
            echo json_encode($response);
        }else{
            exit();
        }
    }


public function admin_search_telemedicine_report(){
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


        if (!empty($reqData['month'])) {
            $pram['m'] = $reqData['month'];
        }

        if (!empty($reqData['year'])) {
            $pram['y'] = $reqData['year'];
        }



        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "telemedicine_report",
                "?" => $pram,
            )
        );
    }

    public function admin_telemedicine_report(){


        $login = $this->Session->read('Auth.User.User');
        $thin_app_id = $login['thinapp_id'];

        $login1 = $this->Session->read('Auth.User');
        $condition = " tl.`status` = 'ACTIVE' AND tcl.`status` ='ACTIVE' AND tl.is_refund = 'NO' and  tcl.connect_status  = 'completed' ";
        $searchData = $this->request->query;

        if (isset($searchData['s']) && !empty($searchData['s'])) {
            $this->request->data['Search']['settled'] = $settled = $searchData['s'];
            $condition .= " and bcfd.is_settled = '$settled'";
        }

        if (isset($searchData['t']) && !empty($searchData['t'])) {
            $this->request->data['Search']['thinapp_id'] = $thin_app1_id = $searchData['t'];
            $condition .= " and tcl.thinapp_id = $thin_app1_id";
        }

        if (isset($searchData['m'])) {
            if ($searchData['m'] != '-1') {
                $this->request->data['Search']['month'] = $month = $searchData['m'];
                $condition .= " and MONTH(tcl.created) = $month";
            }
        }else{
            $this->request->data['Search']['month'] = $month = date('m');
            $condition .= " and MONTH(tcl.created) = $month";
        }

        if (isset($searchData['y'])){
            if ($searchData['y'] != '-1') {
                $this->request->data['Search']['year'] = $year = $searchData['y'];
                $condition .= " and YEAR(tcl.created) = $year";
            }
        }else{
            $this->request->data['Search']['year'] = $year = date('Y');
            $condition .= " and YEAR(tcl.created) = $year";
        }

        $allData=array();
        $query = "SELECT COUNT(tcl.id) AS total_call, YEAR(tcl.created) as year_, MONTHNAME(tcl.created) AS month_name, tl.telemedicine_service_type, t.name AS app_name, SUM(tcl.duration) AS duration, sum(tcl.doctor_share) AS doctor_share, sum(tcl.call_charges) AS call_charges,SUM(tcl.gst_share) AS gst_share, SUM(tcl.mengage_share) AS mengage_share,SUM(tcl.gatway_share) AS payment_getway_share  FROM telemedicine_leads AS tl left JOIN thinapps AS t ON t.id = tl.thinapp_id left JOIN telemedicine_call_logs AS tcl ON tl.id = tcl.telemedicine_lead_id WHERE  $condition GROUP BY MONTH(tcl.created),tcl.thinapp_id order by tl.id desc";

        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $allData  = mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        $reportTitle = $login1['Thinapp']['name'].' ('.date('d/m/Y').')';
        $this->set(array('allData'=>$allData,'reportTitle'=>$reportTitle));
    }

	public function admin_search_subscription_list(){
        $reqData = $this->request->query;
        $pram = array();
        if(!empty($reqData['name']))
        {
            $pram['n'] = $reqData['name'];
        }


        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "subscription_list",
                "?" => $pram,
            )
        );
    }

    public function admin_subscription_list()
    {
        $login = $this->Session->read('Auth.User');
        $conditions["Thinapp.category_name"] = array('DOCTOR','HOSPITAL');
        $searchData = $this->request->query;

        if(isset($searchData['n']) && !empty($searchData['n']))
        {
            $this->request->data['Search']['name'] = $searchData['n'];
            $conditions["Thinapp.name LIKE"] = '%'.$searchData['n'].'%';
        }
        if(isset($searchData['e']) && !empty($searchData['e']))
        {
            $this->request->data['Search']['email'] = $searchData['e'];
            $conditions["Thinapp.email LIKE"] = '%'.$searchData['e'].'%';
        }


        $conditions["AppointmentCustomerStaffService.appointment_datetime >= "] = date('Y-m-d',strtotime("-6 months"));

        $this->paginate = array(
            "conditions" => $conditions,
            'fields'=>array('Thinapp.logo','Thinapp.is_published','Thinapp.name','Thinapp.id','Thinapp.phone','Thinapp.subscription_start_date','Thinapp.end_date','count(AppointmentCustomerStaffService.thinapp_id) as total_appointment','DATEDIFF(Thinapp.end_date,NOW()) as total_days'),
            'contain'=>array('Thinapp'),
            'order' => 'total_days ASC, total_appointment desc',
            'group'=>array(
                'AppointmentCustomerStaffService.thinapp_id HAVING count(AppointmentCustomerStaffService.thinapp_id) >= 50'
            ),
        	
            'limit' => 20
        );
        $data = $this->paginate('AppointmentCustomerStaffService');

        //pr($data);die;
        $this->set('data',$data);

    }

	public function admin_send_sms_window($flag=null)
    {
        $login = $this->Session->read('Auth.User');
        if ($this->request->is(array('post', 'put'))) {
            $limit = "";
            $message = $this->request->data['sms']['message'];
            if(!empty($message)){

                if($flag=='APP_ADMIN'){
                    $number = $this->request->data['sms']['numbers'];
                    if($number > 0){
                        $limit = "limit $number";
                    }
                    $connection = ConnectionUtil::getConnection();
                    $query = "SELECT COUNT(acss.thinapp_id) AS total_appointment, t.name ,t.phone FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id WHERE t.category_name IN('DOCTOR','HOSPITAL') AND t.`status` = 'ACTIVE' GROUP BY acss.thinapp_id ORDER BY total_appointment desc $limit";
                    $last_records = $connection->query($query);
                    if ($last_records->num_rows) {
                        $app_list = mysqli_fetch_all($last_records, MYSQLI_ASSOC);
                    }
                    $total_sms = Custom::get_total_sms_thinapp($login['thinapp_id'],"T");
                    if(count($app_list) <= $total_sms){
                        foreach ($app_list as $key =>$value){

                            $result = Custom::send_single_sms($value['phone'],$message,$login['thinapp_id'],false,false);
                        }
                        $this->Session->setFlash(__('Message sent successfully.'), 'default', array(), 'success');
                        $this->redirect(array('controller' => 'supp', 'action' => 'admin_send_sms_window','admin'=>true));
                    }else{
                        $this->Session->setFlash(__("SMS balance is low : $total_sms"), 'default', array(), 'warning');
                    }
                }else if($flag=='FT'){
					$role = $this->request->data['sms']['role'];
                    $send_to = $this->request->data['sms']['send_to'];
                    $limit = $this->request->data['sms']['limit'];
                    $condition = " status='SUBSCRIBED' ";
                
                	if($role=='DOCTOR'){
                        $condition .= " AND role ='DOCTOR'";
                    }else if($role=='HOSPITAL'){
                        $condition .= " AND role ='HOSPITAL'";
                    }
                
                	if($send_to=='TEST_USER'){
                        $condition = "test_users ='YES'";
                    }

                	
                
                    $connection = ConnectionUtil::getConnection();
                    $query = "SELECT mobile FROM futuristic_subscribers where $condition order by id asc limit $limit";


                    $last_records = $connection->query($query);
                    if ($last_records->num_rows) {
                        $app_list = mysqli_fetch_all($last_records, MYSQLI_ASSOC);
                    }
                    $total_sms = Custom::get_total_sms_thinapp($login['thinapp_id'],"T");
                    if(count($app_list) <= $total_sms){
                    
                    	$message = $this->request->data['sms']['message'];
                        $long_url ="https://api.whatsapp.com/send?phone=918955004049";
                        $short_url = Custom::short_url($long_url);
                        $message = str_replace("#WHATSAPP#",$short_url,$message);
                    
                    	foreach ($app_list as $key =>$value){
                          	$mobile = Custom::create_mobile_number($value['mobile']);
                            $long_url =SITE_PATH.'doctor_home?un='.base64_encode($mobile);
                            $short_url = Custom::short_url($long_url);
                            $message = str_replace("#UNSUBSCRIBE#",$short_url,$message);
                            $result = Custom::send_single_sms($mobile,$message,$login['thinapp_id'],false,false);
                        }
                        $this->Session->setFlash(__('Message sent successfully.'), 'default', array(), 'success');
                        $this->redirect(array('controller' => 'supp', 'action' => 'admin_send_sms_window','admin'=>true));
                    }else{
                        $this->Session->setFlash(__("SMS balance is low : $total_sms"), 'default', array(), 'warning');
                    }
                }



            }else{
                $this->Session->setFlash(__("Please enter message"), 'default', array(), 'error');
            }
        }
    }
	
	public function admin_read_excel(){

        $this->layout = 'support_admin_home';
        $login = $this->Session->read('Auth.User.User');

        if(isset($this->request->data['Search']['excel'])){

            $app_list = array();
            $query = "SELECT t.id, t.name, t.booking_convenience_fee  FROM  thinapps AS t WHERE  t.category_name IN('DOCTOR','HOSPITAL') ";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $list  = mysqli_fetch_all($list,MYSQL_ASSOC);
                foreach ($list as $key => $app){
                    $app_list[$app['id']] = $app;
                }
            }


            $excel = $this->request->data['Search']['excel'];
            /* check paytm refunds */
            $paytm = $paytm_refund= array();
            if(!empty($this->request->data['Search']['paytm_refund'])){
                $paytm = $this->request->data['Search']['paytm_refund'];
                $file_data = fopen($paytm['tmp_name'], 'r');
                $header = fgetcsv($file_data);
                $ref_index = $status_index = 0;
                foreach ($header as $key => $head){
                    $tmp[$key] = $head;
                    if(strtolower($head)==strtolower('Order_ID')){
                        $ref_index = $key;
                    }else if(strtolower($head)==strtolower('Status')){
                        $status_index = $key;
                    }
                }
                while($row = fgetcsv($file_data))
                {
                    $status = str_replace("'","",$row[$status_index]);
                    $refrence_id = str_replace("'","",$row[$ref_index]);
                    if(strtolower($status)=='success'){
                        $paytm_refund[] =$refrence_id;
                    }
                }
            }
            //pr($excel);die;
            $new_data = $final_array = $tmp = array();
            $file_data = fopen($excel['tmp_name'], 'r');
            $header = fgetcsv($file_data);
            $email_index = 0;
            $reference_index = 0;
            $amount_index = 0;
            $refund_index = 0;
            $status_index = 0;
            foreach ($header as $key => $head){
                $tmp[$key] = $head;
                if(strtolower($head)==strtolower('Reference Id')){
                    $reference_index = $key;
                }else if(strtolower($head)==strtolower('Customer Email')){
                    $email_index = $key;
                }else if(strtolower($head)==strtolower('Amount')){
                    $amount_index = $key;
                }else if(strtolower($head)==strtolower('Refunded')){
                    $refund_index = $key;
                }else if(strtolower($head)==strtolower('Transaction Status')){
                    $status_index = $key;
                }

            }
            $total_column = count($tmp);

            while($row = fgetcsv($file_data))
            {
                $tmp =array();
                if(!empty($row[$email_index])){

                    $email = $row[$email_index];
                    $tmp_email =explode("_",$email);
                    $tmp['app_id'] = $app_id = $tmp_email[0];
                    if(intval($app_id) && strtolower(trim($row[$status_index]))=='success' && strtolower($row[$refund_index])=='no' && !in_array($row[$reference_index],$paytm_refund)){
                        $tmp['email'] = $row[$email_index];
                        $tmp['app_name'] = $row[$email_index];
                        $tmp['amount'] = $amount = $row[$amount_index];
                        $tmp['reference_id'] = $row[$reference_index];
                        $tmp['refund'] = $row[$refund_index];
                        $last = 0;
                        if(!empty($final_array[$app_id]['counts'][$row[$amount_index]])){
                            $last = $final_array[$app_id]['counts'][$row[$amount_index]];
                        }
                        $final_array[$app_id]['data'] = $tmp;
                        $final_array[$app_id]['counts'][$row[$amount_index]] = $last+1;
                        ksort($final_array[$app_id]['counts']);
                    }else{
                        if(in_array($row[$reference_index],$paytm_refund))
                            $ppp[] = $row[$reference_index];
                    }
                }
            }
           //pr($ppp);
          // pr($final_array);die;


        }


        $this->set(compact('final_array','app_list'));

    }
	public function admin_list_view_appointment()
    {

        $this->layout = 'support_admin_home';
        $this->request->data['Search']['to_date'] = date("d/m/Y");
        $this->request->data['Search']['from_date'] = date("d/m/Y");
        $today = $from_date = $to_date = date('Y-m-d');
        $this->request->data['Search']['payment_status'] = "SUCCESS";

    }

	public function admin_search_dashboard_report()
    {
        $reqData = $this->request->data['Search'];
        $pram = array();

        $searchData = $this->request->query;
        if (isset($searchData['rt'])) {
            $pram['rt'] = $searchData['rt'];
        }


        if (!empty($reqData['from_date'])) {
            $pram['f'] = $reqData['from_date'];
        }
        if (!empty($reqData['to_date'])) {
            $pram['t'] = $reqData['to_date'];
        }
        if (!empty($reqData['thinapp_id'])) {
            $pram['ti'] = $reqData['thinapp_id'];
        }

        $this->redirect(
            array(
                "controller" => "supp",
                "action" => "dashboard_report",
                "?" => $pram,
            )
        );
    }

   public function admin_dashboard_report()
    {

        $this->layout = 'support_admin_home';
        $today = date("Y-m-d");
        $searchData = $this->request->query;
        $report_type = 'dw';
        if (isset($searchData['rt'])) {
            $report_type = $searchData['rt'];
        }

        $condition = "";

        if ($report_type == 'dw') {

            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');

                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }

                $condition .= "  DATE(acss.appointment_datetime) BETWEEN '$from_date' AND '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  DATE(acss.appointment_datetime) BETWEEN '$today' AND '$today' ";
            }


            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  acss.thinapp_id =". $searchData['ti'];
            }


            $list = array();
            $query = "SELECT SUM(final.telemedicince_with_token) as telemedicince_with_token,SUM(final.clinic_visit_with_token) as clinic_visit_with_token,SUM(final.b2c_clinic_visit_without_token) AS b2c_clinic_visit_without_token,SUM(final.b2b_clinic_visit_without_token) AS b2b_clinic_visit_without_token,final.app_date from (SELECT DATE(acss.appointment_datetime) as search_date,  SUM(if(acss.consulting_type != 'OFFLINE' AND bcfd.id IS NOT NULL,1,0)) as telemedicince_with_token,SUM(if(acss.consulting_type = 'OFFLINE' AND bcfd.id IS NOT NULL,1,0)) as clinic_visit_with_token,SUM(if(aef.id IS NOT NULL AND acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) AS b2c_clinic_visit_without_token,SUM(if(aef.id  IS NULL AND acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) AS b2b_clinic_visit_without_token,DAYNAME(acss.appointment_datetime) AS app_date from appointment_customer_staff_services as acss LEFT JOIN booking_convenience_fee_details AS bcfd on bcfd.tx_status IN('SUCCESS','PAYMENT_SUCCESS') and bcfd.status='ACTIVE' and bcfd.appointment_customer_staff_service_id =acss.id LEFT JOIN app_enable_functionalities AS aef ON aef.thinapp_id = acss.thinapp_id  AND aef.app_functionality_type_id=52 WHERE   $condition group by app_date ";
            $query .= " UNION ALL ";
            $query .= "SELECT DATE(acss.appointment_datetime) as search_date,  SUM(if(acss.consulting_type != 'OFFLINE' AND bcfd.id IS NOT NULL,1,0)) as telemedicince_with_token,SUM(if(acss.consulting_type = 'OFFLINE' AND bcfd.id IS NOT NULL,1,0)) as clinic_visit_with_token,SUM(if(aef.id IS NOT NULL AND acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) AS b2c_clinic_visit_without_token,SUM(if(aef.id  IS NULL AND acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) AS b2b_clinic_visit_without_token,DAYNAME(acss.appointment_datetime) AS app_date from appointment_customer_staff_services_archive as acss LEFT JOIN booking_convenience_fee_details AS bcfd on bcfd.tx_status IN('SUCCESS','PAYMENT_SUCCESS') and bcfd.status='ACTIVE' and bcfd.appointment_customer_staff_service_id =acss.id LEFT JOIN app_enable_functionalities AS aef ON aef.thinapp_id = acss.thinapp_id  AND aef.app_functionality_type_id=52 WHERE   $condition group by app_date ) as final GROUP BY final.app_date order by final.search_date asc ";
        
        	//echo $query;die;
        
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
            $title = "Day_wise_$from_date"."_".$to_date;
            $numberOfSunday = Custom::sunday_between_two_dates($from_date, $to_date);
            $totalDays = Custom::total_days_between_two_dates($from_date, $to_date);
            $avgDivide = $totalDays-$numberOfSunday;
            $this->set(compact('title','list','report_type','avgDivide'));

        } else if ($report_type == 'dwc') {

            $condition ="bcfd.`status`='ACTIVE' AND bcfd.tx_status IN('SUCCESS','PAYMENT_SUCCESS')";
            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }
                $condition .= "  and DATE(acss.appointment_datetime) BETWEEN '$from_date' AND '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  and  DATE(acss.appointment_datetime) BETWEEN '$today' AND '$today' ";
            }


            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  acss.thinapp_id =". $searchData['ti'];
            }

            $list = array();
        	
        	 $query = "SELECT * FROM ( SELECT bcfd.amount, bcfd.booking_convenience_fee as paid_token_amount, acss.consulting_type, bcfd.doctor_online_consulting_fee, bcfd.thinapp_id, t.name, t.booking_convenience_fee, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat FROM booking_convenience_fee_details AS bcfd JOIN appointment_customer_staff_services AS acss ON acss.id = bcfd.appointment_customer_staff_service_id JOIN thinapps AS t ON t.id= bcfd.thinapp_id WHERE $condition";
            $query .= " UNION ALL ";
            $query .= " SELECT bcfd.amount, bcfd.booking_convenience_fee as paid_token_amount, acss.consulting_type, bcfd.doctor_online_consulting_fee, bcfd.thinapp_id, t.name, t.booking_convenience_fee, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat FROM booking_convenience_fee_details AS bcfd JOIN appointment_customer_staff_services_archive AS acss ON acss.id = bcfd.appointment_customer_staff_service_id JOIN thinapps AS t ON t.id= bcfd.thinapp_id WHERE $condition ) AS final";
        	
        
           
        
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);

            if ($service_message_list->num_rows) {
                $tmp_list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                foreach ($tmp_list as $key => $val){
                    $list[$val['thinapp_id']]['name']=$val['name'];
                    $list[$val['thinapp_id']]['convenience_fee']=$val['booking_convenience_fee'];
                    $list[$val['thinapp_id']]['booking_convenience_fee_video']=$val['booking_convenience_fee_video'];
                    $list[$val['thinapp_id']]['convenience_fee_video']=$val['booking_convenience_fee_video'];
                    $list[$val['thinapp_id']]['convenience_fee_audio']=$val['booking_convenience_fee_audio'];
                    $list[$val['thinapp_id']]['convenience_fee_chat']=$val['booking_convenience_fee_chat'];
                    $offline_token = 1;
                    if($val['consulting_type']=='OFFLINE'){
                        if(isset($list[$val['thinapp_id']][$val['consulting_type']][$val['paid_token_amount']])){
                            $offline_token =$list[$val['thinapp_id']][$val['consulting_type']][$val['paid_token_amount']]+1;
                        }
                        $list[$val['thinapp_id']][$val['consulting_type']][$val['paid_token_amount']]=$offline_token;
                    }else{
                        $count = 1;
                        if(isset($list[$val['thinapp_id']][$val['consulting_type']][$val['doctor_online_consulting_fee']])){
                            $count =$list[$val['thinapp_id']][$val['consulting_type']][$val['doctor_online_consulting_fee']]+1;
                        }
                        $list[$val['thinapp_id']][$val['consulting_type']][$val['doctor_online_consulting_fee']]=$count;
                    }

                    $total_token_amount = $val['paid_token_amount'];
                    if(isset($list[$val['thinapp_id']]['total_token_amount'])){
                        $total_token_amount =$list[$val['thinapp_id']]['total_token_amount']+$total_token_amount;
                    }
                    $list[$val['thinapp_id']]['total_token_amount']=$total_token_amount;


                    $total_collection = $val['amount'];
                    if(isset($list[$val['thinapp_id']]['total_collection'])){
                        $total_collection =$list[$val['thinapp_id']]['total_collection']+$total_collection;
                    }
                    $list[$val['thinapp_id']]['total_collection']=$total_collection;

                }
            }


            $title = "doctor_wise_collection_$from_date"."_".$to_date;
            $this->set(compact('title','list','report_type'));


        } else if ($report_type == 'dwcv') {

            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }
                $condition .= "  DATE(acss.appointment_datetime) BETWEEN '$from_date' AND '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  DATE(acss.appointment_datetime) BETWEEN '$today' AND '$today' ";
            }


            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  acss.thinapp_id =". $searchData['ti'];
            }

            $list = array();
             $query = "SELECT final.app_name, SUM(final.total) AS total, SUM(final.clinic_visit_with_token) as clinic_visit_with_token ,  SUM(final.clinic_visit_no_token) as clinic_visit_no_token  FORM ( SELECT  t.name as app_name, COUNT(acss.id) AS total, SUM(if(acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee = 'YES',1,0)) as clinic_visit_with_token ,  SUM(if(acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) as clinic_visit_no_token  from appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id WHERE   $condition GROUP BY acss.thinapp_id having clinic_visit_with_token > 0 OR clinic_visit_no_token > 0 ";
            $query .= " UNION ALL ";
            $query .= "SELECT  t.name as app_name, COUNT(acss.id) AS total, SUM(if(acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee = 'YES',1,0)) as clinic_visit_with_token ,  SUM(if(acss.consulting_type = 'OFFLINE' AND acss.is_paid_booking_convenience_fee != 'YES',1,0)) as clinic_visit_no_token  from appointment_customer_staff_services_archive AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id WHERE   $condition GROUP BY acss.thinapp_id having clinic_visit_with_token > 0 OR clinic_visit_no_token > 0 ) AS final  order by final.clinic_visit_no_token desc";
          
        
        	//echo $query;die;
            
            
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
            $title = "doctor_wise_clinic_visit_$from_date"."_".$to_date;
            $this->set(compact('title','list','report_type'));

        }else if ($report_type == 'wac') {

            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }

                $condition .= "  DATE(suh.created) between '$from_date' and '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  DATE(suh.created) between '$today' and '$today' ";
            }


            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  t.id =". $searchData['ti'];

            }

            $condition .= " and suh.module_name ='WEB_APP_TRACKER' ";

            $list = array();
            $query = "SELECT t.name, count(suh.id) as total FROM short_url_hits as suh join thinapps as t on t.id = suh.thinapp_id where  $condition group by suh.thinapp_id order by total desc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
            $title = "Web_app_clicks_$from_date"."_".$to_date;
            $this->set(compact('title','list','report_type'));

        }else if ($report_type == 'wai') {

            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }
                $condition .= "  DATE(waic.created) BETWEEN '$from_date' AND '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  DATE(waic.created) between '$today' and '$today' ";
            }

            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  t.id =". $searchData['ti'];

            }


            $list = array();
            $query = "SELECT t.name as app_name, staff.name as doctor_name, count(waic.id) as total_installed  FROM `web_app_install_count` as waic join thinapps as t on t.id = waic.thinapp_id join appointment_staffs as staff on staff.id = waic.doctor_id where $condition group by waic.doctor_id order by total_installed desc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
            $title = "Web_app_install_$from_date"."_".$to_date;
            $this->set(compact('title','list','report_type'));

        }else if ($report_type == 'sms') {

            if (isset($searchData['f']) && isset($searchData['t'])) {
                if (!empty($searchData['f'])) {
                    $this->request->data['Search']['from_date'] = $searchData['f'];
                    $date = $from_date= DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                }
                if (!empty($searchData['t'])) {
                    $this->request->data['Search']['to_date'] = $searchData['t'];
                    $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                }
                $condition .= "  DATE(ssd.created) BETWEEN '$from_date' AND '$to_date' ";

            } else {
                $this->request->data['Search']['to_date'] = date("d/m/Y");
                $this->request->data['Search']['from_date'] = date("d/m/Y");
                $today = $from_date = $to_date = date('Y-m-d');
                $condition .= "  DATE(ssd.created) BETWEEN '$today' AND '$today' ";
            }


            if (isset($searchData['ti']) || !empty($searchData['ti'])) {
                $this->request->data['Search']['thinapp_id']  = $searchData['ti'];
                $condition .= " and  ssd.thinapp_id =". $searchData['ti'];
            }

            $condition .= " and t.status='ACTIVE'";


            $list = array();
            $query = "select count(ssd.id) as total, t.name as app_name from sent_sms_details as ssd join thinapps as t on t.id = ssd.thinapp_id where $condition group by ssd.thinapp_id order by total desc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            }
            $title = "sms_utility_$from_date"."_".$to_date;
            $this->set(compact('title','list','report_type'));

        }


        $this->set(compact('reportTitle','report_type'));

    }

	  public function admin_refund_convenience_fee()
    {
        $this->layout = 'support_admin_home';
        $this->request->data['Search']['to_date'] = date("d/m/Y");
        $this->request->data['Search']['from_date'] = date("d/m/Y");
        $today = $from_date = $to_date = date('Y-m-d');
        $this->request->data['Search']['payment_status'] = "SUCCESS";
    }
    public function admin_search_convenience_appointment()
    {
        $reqData = $this->request->data['Search'];
        $pram = array();


        if (!empty($reqData['from_date'])) {
            $pram['f'] = $reqData['from_date'];
        }
        if (!empty($reqData['to_date'])) {
            $pram['t'] = $reqData['to_date'];
        }
        if (!empty($reqData['status'])) {
            $pram['s'] = $reqData['status'];
        }

        if (!empty($reqData['consulting_type'])) {
            $pram['ct'] = $reqData['consulting_type'];
        }
        if (!empty($reqData['payment_status'])) {
            $pram['ps'] = $reqData['payment_status'];
        }
        if (!empty($reqData['doctor'])) {
            $pram['d'] = $reqData['doctor'];
        }
        if (!empty($reqData['booked_via'])) {
            $pram['dv'] = $reqData['booked_via'];
        }
        if (!empty($reqData['convince_fee'])) {
            $pram['cf'] = $reqData['convince_fee'];
        }

        if (!empty($reqData['thinapp_id'])) {
            $pram['ti'] = $reqData['thinapp_id'];
        }

        $searchData = $pram;
        if (isset($searchData['ti']) || !empty($searchData['ti'])) {
            $this->request->data['Search']['thinapp_id'] = $thinappID = $searchData['ti'];
        }else{
            $login = $this->Session->read('Auth.User.User');
            $thinappID = $login['thinapp_id'];
        }

        $today = date("Y-m-d");

        $condition = "  acss.thinapp_id = $thinappID and acss.delete_status !='DELETED'";


        if (isset($searchData['f']) || isset($searchData['t'])) {
            if (!empty($searchData['f'])) {
                $this->request->data['Search']['from_date'] = $searchData['f'];
                $date = $from_date = DateTime::createFromFormat('d/m/Y', $searchData['f'])->format('Y-m-d');
                $condition .= " and DATE(acss.appointment_datetime) >='$date' ";
            }
            if (!empty($searchData['t'])) {
                $this->request->data['Search']['to_date'] = $searchData['t'];
                $date = $to_date = DateTime::createFromFormat('d/m/Y', $searchData['t'])->format('Y-m-d');
                $condition .= " and DATE(acss.appointment_datetime) <='$date' ";
            }
        } else {
            $this->request->data['Search']['to_date'] = date("d/m/Y");
            $this->request->data['Search']['from_date'] = date("d/m/Y");
            $today = $from_date = $to_date = date('Y-m-d');
            $condition .= " and DATE(acss.appointment_datetime) between '$today' and '$today' ";
        }


        if (isset($searchData['s']) || !empty($searchData['s'])) {
            $this->request->data['Search']['status'] = $status = $searchData['s'];
            $condition .= " and (acss.status) = '$status' ";
        }

        if (isset($searchData['ct']) || !empty($searchData['ct'])) {
            $this->request->data['Search']['consulting_type'] = $consulting_type = $searchData['ct'];
            $condition .= " and (acss.consulting_type) = '$consulting_type' ";
        }

        if (isset($searchData['ps']) || !empty($searchData['ps'])) {
            $this->request->data['Search']['payment_status'] = $payment_status = $searchData['ps'];
            $condition .= " and (acss.payment_status) = '$payment_status' ";
        }

        if (isset($searchData['d']) || !empty($searchData['d'])) {
            $this->request->data['Search']['doctor'] = $doctor_id = $searchData['d'];
            $condition .= " and (acss.appointment_staff_id) = $doctor_id ";
        }

        if (isset($searchData['bv']) || !empty($searchData['bv'])) {
            $this->request->data['Search']['booked_via'] = $booke_via= $searchData['bv'];
            $condition .= " and (acss.appointment_booked_from) = '$booke_via'";
        }
        if(isset($searchData['cf']) || !empty($searchData['cf'])) {
            $this->request->data['Search']['convince_fee'] = $convince_fee= $searchData['cf'];
            if($convince_fee=='YES'){
                $condition .= " and (acss.is_paid_booking_convenience_fee) = 'YES'";
            }ELSE{
                $condition .= " and (acss.is_paid_booking_convenience_fee) != 'YES'";
            }
        }


        $list = $payment_type_name = array();
        $query = "SELECT bcfd.status as fee_status, bcfd.id as booking_convenience_order_detail_id, acss.thinapp_id, acss.id as appointment_id, bcfd.booking_convenience_order_id, acss.medical_product_order_id, bcfd.reference_id,bcfd.payment_mode, bcfd.amount as total_amount, bcfd.booking_convenience_fee,  bcfd.doctor_online_consulting_fee,  acss.appointment_booked_from, acss.status, IFNULL(ac.uhid,c.uhid) as uhid, IFNULL(ac.mobile,c.mobile) AS patient_mobile, IFNULL(mpo.total_amount,acss.amount) AS amount, staff.name AS doctor_name, acss.appointment_datetime, acss.queue_number,acss.has_token,acss.sub_token,acss.emergency_appointment,acss.custom_token,acss.appointment_patient_name, acss.payment_status,acss.booking_payment_type,acss.consulting_type FROM appointment_customer_staff_services AS acss LEFT JOIN booking_convenience_fee_details AS bcfd ON bcfd.appointment_customer_staff_service_id = acss.id AND bcfd.`status`='ACTIVE' JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id  LEFT JOIN appointment_customers AS ac ON ac.id= acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id LEFT JOIN medical_product_orders AS mpo ON mpo.id = acss.medical_product_order_id WHERE $condition";

        $connection = ConnectionUtil::getConnection();
        $data_list = $connection->query($query);
        if ($data_list->num_rows) {
            $list = mysqli_fetch_all($data_list, MYSQLI_ASSOC);
        }
        $this->set(compact('list'));

    }
  
	public function admin_refund_online_amount(){
        $this->layout = false;
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $response = array();
            $login = $this->Session->read('Auth.User');
            $user_id = $refund_by_user_id =$login['id'];
            $message = ($this->request->data['msg']);
        	$refund_amount = ($this->request->data['tm']);
        	
            $ti = base64_decode($this->request->data['ti']);
            $ai = base64_decode($this->request->data['ai']);
            $connection = ConnectionUtil::getConnection();
			$detail_data = Custom::getCashFreeOnlineAmount($ai,$ti);
			if (!empty($detail_data)) {
				$referenceId = $detail_data['refrence_id'];
				$medical_product_order_id = $detail_data['medical_product_order_id'];
				$amount = $detail_data['amount'];
				$order_id = $detail_data['order_id'];
				$payment_getway = $detail_data['payment_getway'];
				if(!empty($referenceId) && !empty($amount)){
					if($payment_getway=="PHONEPAY"){
                   		 /* PHONEPAY ONLY ALLOW AMOUNT IN PAISA AND RETURNN IN PAISA  */
        				
						$response = json_decode(Custom::phonepayRefund($connection,$ti, $refund_by_user_id, $medical_product_order_id, $referenceId,$refund_amount,$detail_data['booking_convenience_order_detail_id'],$order_id,$message,$detail_data['payment_mode']),true);
						if($response['success']==true){
							$response['status'] = "OK";
						}
					}else{
						$response = json_decode(Custom::cashFreeRefund($connection,$ti, $refund_by_user_id, $medical_product_order_id, $referenceId,$refund_amount,$detail_data['booking_convenience_order_detail_id'],$message,$detail_data['payment_mode']),true);
					}
				}
			}
		}else{
            $response['status'] = 0;
            $response['message'] = "Invalid Request";

        }
        echo json_encode($response);die;


    }

}

