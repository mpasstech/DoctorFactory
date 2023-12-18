<?php
class AdminController extends AppController {

	public $name = 'Admin';
	public $helpers = array();
	public $uses = array('Group','User','Ad','Page','SubCategory','Channel','Message','ChannelMessage','MessageStatic','Constant','AppTheme','EventCategory','EventMedia','EventResponse','Event','EventAgenda','EventOrganizer','EventSpeaker','EventTicket','EventShow','EventTicketSell','Quest','QuestLike','QuestShare','QuestReply','QuestReplyThank','QuestCategory','SellItem','SellImage','SellWishlist','SellItemCategory','AppUserStatic');
	public $components = array('RequestHandler','Custom');

        /*****************************************
        function :- index 
        *****************************************/

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'admin_home';
		$this->Auth->allow('admin_login');


	}

	public function index() {
			$this->autoRender = false;
		}

	/*****************************************
        function :- admin_index 
        *****************************************/
        
	public function admin_index() {
			$login = $this->Session->read('Auth');





		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$dateVal = $this->request->data['dateVal'];
			$userOnDate = $this->AppUserStatic->find('count', array('conditions' => array( 'DATE(AppUserStatic.created)'=>$dateVal)));

			echo '<li><a href="javascript:void(0)"><div class="content_div"><div class="dash_img dash_text">'.$userOnDate.'</div>Users On '.$dateVal.'</div></a></li>';

		}





		
		
		
			$totalUsers = $this->AppUserStatic->find('count');
			$todayUser = $this->AppUserStatic->find('count', array('conditions' => array( 'AppUserStatic.created BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()')));
			$weekUser = $this->AppUserStatic->find('count', array('conditions' => array( 'AppUserStatic.created BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()')));
			$monthUser = $this->AppUserStatic->find('count', array('conditions' => array( 'AppUserStatic.created BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()')));
			$this->set(compact('totalUsers','todayUser','weekUser','monthUser'));
		}

	public function admin_list_user(){
		$login = $this->Session->read('Auth');
		$conditions = array();

		$searchData = $this->request->query;
		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["User.username LIKE"] = '%'.$searchData['n'].'%';
		}
		if(isset($searchData['e']) && !empty($searchData['e']))
		{
			$this->request->data['Search']['email'] = $searchData['e'];
			$conditions["User.email LIKE"] = '%'.$searchData['e'].'%';
		}

		$conditions['User.id <>'] = $login['User']['id'];
		$conditions['User.role_id IN'] = array(2,4);
		//pr($conditions); die;
		$this->paginate = array(
			"conditions" => $conditions,
			'contain'=>array('Role'),
			'order' => 'User.id DESC',
			'limit' => 10
		);
		$data = $this->paginate('User');
		$this->set('data',$data);
	}

	public function admin_change_user_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$userData = $this->User->find("first",
				array(
					"conditions" => array("User.id" => $rowID),
					"contain" => false,
				)
			);
			$statusToChange = ($userData['User']['status'] == 'Y')?'N':'Y';

			$userData['User']['status'] = $statusToChange;
			//pr($userData['User']);

			if($this->User->updateAll(array('User.status'=>"'".$statusToChange."'"),array('User.id'=>$userData['User']['id'])))
			{
				$response['status'] = 1;
				$response['text'] = ($userData['User']['status'] == 'Y')?'ACTIVE':'INACTIVE';
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

	public function admin_get_edit_user(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$rowID = $this->request->data['rowID'];
			$userData = $this->User->find("first",
				array(
					"conditions" => array("User.id" => $rowID),
					"contain" => false,
				)
			);

			if(!empty($userData))
			{
				$userData['User']['mobile'] = str_replace("+91","",$userData['User']['mobile']);
				$response['status'] = 1;
				$response['data'] = $userData['User'];
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

	public function admin_edit_user(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
		//	pr($this->request->data); die;
			$this->request->data['mobile'] = '+91'.$this->request->data['mobile'];
			if($this->User->save($this->request->data))
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

	public function admin_add_user()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$this->request->data['password'] = md5($this->request->data['password']);
			unset($this->request->data['conf_password']);
			$this->request->data['mobile'] = '+91'.$this->request->data['mobile'];
			$this->request->data['is_verified'] = 'Y';
			$this->User->set($this->request->data);
			if ($this->User->validates()) {

				$this->User->create();
				if($this->User->save($this->request->data))
				{
					$response['status'] = 1;
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = 'Sorry, Could not add.';
				}
			}
			else
			{
				foreach($this->User->validationErrors AS $val)
				{
					$message = $val[0];
				}
				$response['status'] = 0;
				$response['message'] = $message;
			}
			$response = json_encode($response, true);
			echo $response;
			exit();

		}
	}

	public function admin_search_list_user()
	{
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
				"controller" => "admin",
				"action" => "list_user",
				"?" => $pram,
			)
		);
	}

	public function admin_add_channel()
	{

		$login = $this->Session->read('Auth');
		if($this->request->is(array('post','put')))
		{
			$this->request->data['Channel']['user_id'] = $login['User']['id'];
			//$this->request->data['Channel']['image'] = "";
			$this->request->data['Channel']['app_id'] = 1;
			$this->request->data['Channel']['channel_status'] = 'PUBLIC';
			$this->Channel->set($this->request->data['Channel']);
			if ($this->Channel->validates()) {
				$datasource = $this->Channel->getDataSource();
				try {
					$datasource->begin();
					if ($this->Channel->save($this->request->data['Channel'])) {

						$last_inser_id = $this->Channel->getLastInsertId();
						$this->Channel->id = $last_inser_id;
						$topic_name = $this->Custom->create_topic_name($last_inser_id);
						$this->Channel->set('topic_name', $topic_name);
						$this->Channel->set('channel_created_from', 'WEB');
						if($this->Channel->save()){
							$mbroadcast_app_id = MBROADCAST_APP_ID;
							$this->Custom->create_topic($mbroadcast_app_id, $last_inser_id);
							$datasource->commit();
							$this->Session->setFlash(__('Channel add successfully.'), 'default', array(), 'success');
						}else{
							$this->Session->setFlash(__('Sorry channel could not add.'), 'default', array(), 'error');
						}
					} else {
						$this->Session->setFlash(__('Sorry channel could not add.'), 'default', array(), 'error');
					}
				} catch (Exception $e) {
					$datasource->rollback();
					$this->Session->setFlash(__('Sorry channel could not add.'), 'default', array(), 'error');
				}
			}

		}

	}

	public function admin_search_channel(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['name']))
		{
			$pram['n'] = $reqData['name'];
		}

		$this->redirect(
			array(
				"controller" => "admin",
				"action" => "channel",
				"?" => $pram,
			)
		);
	}

	public function admin_channel()
	{
		$login = $this->Session->read('Auth');
		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['n']) && !empty($searchData['n']))
		{
			$this->request->data['Search']['name'] = $searchData['n'];
			$conditions["Channel.channel_name LIKE"] = '%'.$searchData['n'].'%';
		}

		$conditions["Channel.channel_status"] = 'PUBLIC';


		$this->paginate = array(
			"conditions" => array(
				"Channel.app_id" => MBROADCAST_APP_ID,
				"Channel.user_id" => $login['User']['id'],
				$conditions
			),
			'contain'=>false,
			'limit' => 10
		);
		$data = $this->paginate('Channel');
		$this->set('channel',$data);
	}



	public function admin_edit_channel($id=null)
	{

		$login = $this->Session->read('Auth');


		if($this->request->is(array('post','put')))
		{
			$this->Channel->id = base64_decode($id);
			$this->Channel->set($this->request->data['Channel']);
			if ($this->Channel->validates()) {

				/* if(isset($logo['tmp_name']) && !empty($logo['tmp_name'])){

                     $name = explode('.', $logo['name']);
                     $ext = end($name);
                     $rand_name = "app".rand(100000,1000000).".".$ext;
                     $uploaddir = WWW_ROOT."uploads/app/";
                     $file = $uploaddir.$rand_name;
                     $load_path1 = WWW_ROOT."uploads/ads/origional/".$rand_name;
                     if(move_uploaded_file($logo['tmp_name'] , $file)) {
                         $this->request->data['Leads']['app_logo'] = $rand_name;
                     }
                 }else{
                     $this->request->data['Leads']['app_logo'] = $post['Leads']['app_logo'];
                 }*/

				if($this->Channel->save($this->request->data['Channel'])){
					$this->Session->setFlash(__('Channel update successfully.'), 'default', array(), 'success');
				}else{
					$this->Session->setFlash(__('Sorry channel could not update.'), 'default', array(), 'error');
				}
			}



		}

		$channel = $this->Channel->find("first",array(
			"conditions" => array(
				"Channel.id" => base64_decode($id),
			),
			'contain'=>false
		));


		if (!$this->request->data) {
			$this->request->data = $channel;
		}

		$this->set('post',$channel);

	}

	public function admin_view_channel($id=null)
	{

		$login = $this->Session->read('Auth.User');

		$channel = $this->Channel->find("first",array(
			"conditions" => array(
				"Channel.id" => base64_decode($id),
			),
			'contain'=>false
		));


		if (!$this->request->data) {
			$this->request->data = $channel;
		}

		$this->set('post',$channel);

	}



	public function admin_search_message(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['message']))
		{
			$pram['m'] = $reqData['message'];
		}

		if(!empty($reqData['message_type']))
		{
			$pram['t'] = $reqData['message_type'];
		}

		if(!empty($reqData['channel']))
		{
			$pram['c'] = $reqData['channel'];
		}

		$this->redirect(
			array(
				"controller" => "admin",
				"action" => "message",
				"?" => $pram,
			)
		);
	}

	public function admin_message()
	{
		$login = $this->Session->read('Auth');
		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['m']) && !empty($searchData['m']))
		{
			$this->request->data['Search']['message'] = $searchData['m'];
			$conditions["Message.message LIKE"] = '%'.$searchData['m'].'%';
		}

		if(isset($searchData['t']) && !empty($searchData['t']))
		{
			$this->request->data['Search']['message_type'] = $searchData['t'];
			$conditions["Message.message_type LIKE"] = '%'.$searchData['t'].'%';
		}

		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['channel'] = $searchData['c'];
			$conditions["Channel.id"] = $searchData['c'];
		}



		$this->paginate = array(
			"conditions" => array(
				"Channel.channel_status"=>'PUBLIC',
				$conditions
			),
			'contain'=>array(
				'Message'=>array('Owner'),
				'Channel'
			),
			//'fields'=>array('Message.*','Channel.channel_name'),
			'limit' => 10
		);

		$data = $this->paginate('ChannelMessage');
		$this->set('message',$data);
	}




	

	public function admin_add_message_ajax()
	{
		$this->layout = false;
		$this->autoRender = false;
		$login = $this->Session->read('Auth');
		$thin_app_id = MBROADCAST_APP_ID;
		$user_id = $login['User']['id'];
		$param = array();
		parse_str($this->request->data['Message'],$param);
		$this->request->data['Message'] = $param['data']['Message'];
		$channel_id = base64_decode($this->request->data['chn_id']);
		if($this->request->is(array('ajax')))
		{
			$total_sms = $this->Custom->getTotalRemainingSms($thin_app_id,"P");
			$total_sub = $this->Custom->totalSmsSubscriber($channel_id,$thin_app_id);
			if($total_sms >= $total_sub){
				$this->request->data['Message']['owner_user_id'] = $user_id;
				$this->request->data['Message']['thinapp_id'] = $thin_app_id;
				$this->request->data['Message']['sent_via'] = "WEB";
				$this->request->data['Message']['channel_id'] = $channel_id;
				$this->Message->set($this->request->data['Message']);
				$response = array();
				if ($this->Message->validates()) {
					$datasource = $this->Message->getDataSource();
					try {
						$datasource->begin();
						if ($this->Message->save($this->request->data['Message'])) {

							$file_url = $this->request->data['Message']['message_file_url'];
							$msg_type = $this->request->data['Message']['message_type'];
							if(empty($file_url) && $msg_type!="TEXT"){
								$response['status']=0;
								$response['message']='Please upload media before send message';
							}else{

								$message_id = $this->Message->getLastInsertId();
								$channel_id = $this->request->data['Message']['channel_id'];
								/* add messge to channel Message table*/
								$this->ChannelMessage->create();
								$this->ChannelMessage->set('message_id', $message_id);
								$this->ChannelMessage->set('channel_id',$channel_id);
								$this->ChannelMessage->save();

								/* add row to message sttice table*/
								$this->MessageStatic->create();
								$this->MessageStatic->set('message_id', $message_id);
								$this->MessageStatic->save();
								$this->Custom->updateCoins('POST',$user_id,$user_id,$message_id,$thin_app_id,0);
								$datasource->commit();
								$message = $this->request->data['Message']['message'];
								$app_name =MBROADCAST_APP_NAME;
								$sendArray = array(
									'channel_id'=>$channel_id,
									'thinapp_id'=>$thin_app_id,
									'flag'=>'NEWPOST',
									'title'=> strtoupper($app_name),
									'message' => mb_strimwidth($message, 0, 50, '...')
								);
								$this->Custom->send_topic_notification($sendArray);

								$site_url = $this->Custom->getThinAppUrl($thin_app_id);
								$message ="You have received a message. Click Here To Download App:".$site_url;
								$this->Custom->sendBulkSms($channel_id,$thin_app_id,$message,$message_id,$user_id);


								$response['status']=1;
								$response['message']='Message add successfully.';

							}


						} else {
							$response['status']=0;
							$response['message']='Sorry message could not add.';

						}
					} catch (Exception $e) {
						$datasource->rollback();
						$response['status']=0;
						$response['message']='Sorry message could not add somthing went wrong.';
					}

				}
			}else{
				$response['status']=0;
				$response['message']='Sorry you have not sufficient sms balance.';
			}


			return json_encode($response);
		}else{
			exit();
		}

	}
	/*function add by mahendra*/
	public function admin_upload_media()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){


			if(isset($this->request->data['Message'])) {
				$data = $this->request->data['Message']['file'];
				$file_type = $this->request->data['Message']['file']['type'];
				$message_type = $this->request->data['Message']['message_type'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {
					if ($message_type == "TEXT") {
						$response["status"] = 0;
						$response["message"] = "Sorry you can send only text message";
					}

					if ($message_type == "IMAGE") {
						$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
						if (in_array($file_type, $mimeAarray)) {

							if ($url = $this->Custom->uploadFileToAws($data)) {
								$response["status"] = 1;
								$response["message"] = "File uploaded successfully.";
								$response["url"] = $url;
							} else {
								$response["status"] = 0;
								$response["message"] = "Sorry file could not upload";
							}

						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry you can send only text message";

						}
					}
					if ($message_type == "VIDEO") {
						$mimeAarray = array('video/mp4', 'image/ogg', 'image/wmp');
						if (in_array($file_type, $mimeAarray)) {
							if ($url = $this->Custom->uploadFileToAws($data)) {
								$response["status"] = 1;
								$response["message"] = "File uploaded successfully.";
								$response["url"] = $url;
							} else {
								$response["status"] = 0;
								$response["message"] = "Sorry file could not upload";
							}
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry invalid video format file";
						}
					}
					if ($message_type == "AUDIO") {
						$mimeAarray = array('audio/3gp', 'audio/mp3');
						if (in_array($file_type, $mimeAarray)) {


							if ($url = $this->Custom->uploadFileToAws($data)) {
								$response["status"] = 1;
								$response["message"] = "File uploaded successfully.";
								$response["url"] = $url;
							} else {
								$response["status"] = 0;
								$response["message"] = "Sorry file could not upload";
							}

						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry invalid audio format file";
						}
					}

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}

	public function admin_get_constant()
	{

		if($this->request->is(array('post','put'))) {

			$data = $this->request->data;

			foreach($data as $key => $value)
			{
				$this->Constant->updateAll(array('Constant.value' => $value), array('key' => $key));
			}

		}


		$constants = $this->Constant->find("all",array('contain'=>false ));
		$this->set('constants',$constants);

	}

	public function admin_list_app_themes(){

		$theme = $this->AppTheme->find("all",array('contain'=>false ));
	//	pr($theme); die;
		$this->set('theme',$theme);

	}




/*****EVENT START HERE*****/

	public function admin_add_event(){

		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$datasource = $this->Channel->getDataSource();
			try {

				$dataToSave = $this->request->data;
				$channel_id = $dataToSave['channel_id'];
				$thin_app_id = $login['thinapp_id'];
				$total_sms = $this->Custom->getTotalRemainingSms($thin_app_id,"P");
				$total_sub = $this->Custom->totalSmsSubscriber($channel_id,$thin_app_id);
				if( ($total_sms >= $total_sub && $dataToSave["share_on"]=='CHANNEL') ||  $dataToSave["share_on"]=='EVENT_FACTORY' ){
					$datasource->begin();
					$dataToSave['Event']['user_id'] = $login['id'];
					$dataToSave['Event']['thinapp_id'] = $login['thinapp_id'];
					$dataToSave['Event']['start_datetime'] = $dataToSave['Event']['start_date']." ".$dataToSave['Event']['start_time'];
					$dataToSave['Event']['end_datetime'] = $dataToSave['Event']['end_date']." ".$dataToSave['Event']['end_time'];
					unset($dataToSave['Event']['end_date']);
					unset($dataToSave['Event']['end_time']);
					unset($dataToSave['Event']['start_date']);
					unset($dataToSave['Event']['start_time']);
					if($dataToSave["share_on"]=="CHANNEL"){
						if($this->Event->save($dataToSave)){
							$event_id = $this->Channel->getLastInsertId();
							$this->ChannelMessage->create();
							$this->ChannelMessage->set('channel_id',$channel_id );
							$this->ChannelMessage->set('post_type_id',$event_id);
							$this->ChannelMessage->set('post_type_status', 'EVENT');
							if($this->ChannelMessage->save()){
								$datasource->commit();
								$this->Session->setFlash(__('Event added successfully.'), 'default', array(), 'success');
								$this->redirect(array('controller' => 'admin', 'action' => 'event'));
							}else{
								$this->Session->setFlash(__('Sorry, Event could not added.'), 'default', array(), 'error');
							}
						}else{
							$this->Session->setFlash(__('Sorry, Event could not added.'), 'default', array(), 'error');
						}
					}else{
						if($this->Event->saveAll($dataToSave)){
							$datasource->commit();
							$this->Session->setFlash(__('Event added successfully.'), 'default', array(), 'success');
							$this->redirect(array('controller' => 'admin', 'action' => 'event'));
						}else{
							$this->Session->setFlash(__('Sorry, Event could not added.'), 'default', array(), 'error');
						}
					}
				}else{
					$this->Session->setFlash(__('Sorry, you have insufficient sms balance.'), 'default', array(), 'warning');
				}

			} catch (Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Sorry, Event could not added.'), 'default', array(), 'error');
			}








		}
		$eventCategory = $this->EventCategory->find('list',array('conditions'=>array('EventCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('eventCategory','channels'));
	}

	public function admin_edit_event($id=null){
		$id=base64_decode($id);
		$login = $this->Session->read('Auth.User');
		if($this->request->is(array('post','put')))
		{
			$dataToSave = $this->request->data;
			$dataToSave['Event']['user_id'] = $login['id'];
			$dataToSave['Event']['thinapp_id'] = $login['thinapp_id'];
			$dataToSave['Event']['start_datetime'] = $dataToSave['Event']['start_date']." ".$dataToSave['Event']['start_time'];
			$dataToSave['Event']['end_datetime'] = $dataToSave['Event']['end_date']." ".$dataToSave['Event']['end_time'];
			unset($dataToSave['Event']['end_date']);
			unset($dataToSave['Event']['end_time']);
			unset($dataToSave['Event']['start_date']);
			unset($dataToSave['Event']['start_time']);
			$dataToSave['Event']['id'] = $id;
			if($this->Event->saveAll($dataToSave))
			{
				$this->Session->setFlash(__('Event updated successfully.'), 'default', array(), 'success');
				$this->redirect(array('controller' => 'admin', 'action' => 'event'));
			}
			else
			{
				$this->Session->setFlash(__('Sorry, Event could not updated.'), 'default', array(), 'error');
			}
		}
		else
		{

			$this->request->data = $this->Event->findById($id);
			if(empty($this->request->data))
			{
				$this->Session->setFlash(__('Sorry, Event was not found.'), 'default', array(), 'error');
				$this->redirect(array('controller' => 'admin', 'action' => 'event'));
				return false;
			}
			$datetime = explode(' ',$this->request->data['Event']['start_datetime']);
			$this->request->data['Event']['start_date'] = $datetime[0];
			$this->request->data['Event']['start_time'] = $datetime[1];

			$datetime = explode(' ',$this->request->data['Event']['end_datetime']);
			$this->request->data['Event']['end_date'] = $datetime[0];
			$this->request->data['Event']['end_time'] = $datetime[1];

			unset($this->request->data['Event']['start_datetime']);
			unset($this->request->data['Event']['end_datetime']);
		}
		$eventCategory = $this->EventCategory->find('list',array('conditions'=>array('EventCategory.status' => 'ACTIVE')));
		$channels = $this->Channel->find('list',array('fields' => array('Channel.id', 'Channel.channel_name'),'conditions'=>array('Channel.user_id'=>$login['id'])));
		$this->set(compact('eventCategory','channels'));
	}

	public function admin_search_event(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['title']))
		{
			$pram['t'] = $reqData['title'];
		}
		if(!empty($reqData['category']))
		{
			$pram['c'] = $reqData['category'];
		}
		if(!empty($reqData['date']))
		{
			$pram['d'] = $reqData['date'];
		}

		$this->redirect(
			array(
				"controller" => "admin",
				"action" => "event",
				"?" => $pram,
			)
		);
	}

	public function admin_event(){
		$login = $this->Session->read('Auth.User');

		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['t']) && !empty($searchData['t']))
		{
			$this->request->data['Search']['title'] = $searchData['t'];
			$conditions["Event.title LIKE"] = '%'.$searchData['t'].'%';
		}
		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['category'] = $searchData['c'];
			$conditions["Event.event_category_id"] = $searchData['c'];
		}
		if(isset($searchData['d']) && !empty($searchData['d']))
		{
			$this->request->data['Search']['date'] = $searchData['d'];
			$conditions[] = "( Event.start_datetime <= '".$searchData['d']."' AND Event.end_datetime >= '".$searchData['d']."' )";
		}
		$conditions["Event.user_id"] = $login['id'];

		$this->paginate = array(
			'fields'=>array('Event.id','Event.title','Event.publish_status','Event.start_datetime','Event.end_datetime','Event.status','Event.show_on_mbroadcast','EventCategory.title'),
			'conditions'=>$conditions,
			'contain'=>array( 'EventCategory' ),
			'order'=> 'Event.id DESC',
			'limit'=>10
		);
		$event = $this->paginate('Event');
		//pr($event); die;
		if($this->request->is(array('post','put')))
		{

			$login = $this->Session->read('Auth.User');
			$dataToSave = $this->request->data;
			$dataToSave['Event']['user_id'] = $login['id'];
			$dataToSave['Event']['thinapp_id'] = $login['thinapp_id'];
			$dataToSave['Event']['start_datetime'] = $dataToSave['Event']['start_date']." ".$dataToSave['Event']['start_time'];
			$dataToSave['Event']['end_datetime'] = $dataToSave['Event']['end_date']." ".$dataToSave['Event']['end_time'];
			unset($dataToSave['Event']['end_date']);
			unset($dataToSave['Event']['end_time']);
			unset($dataToSave['Event']['start_date']);
			unset($dataToSave['Event']['start_time']);

			if($this->Event->saveAll($dataToSave))
			{
				$this->Session->setFlash(__('Event added successfully.'), 'default', array(), 'success');
				$this->redirect(array('controller' => 'admin', 'action' => 'event'));
			}
			else
			{
				$this->Session->setFlash(__('Sorry, Event could not added.'), 'default', array(), 'error');
			}
		}
		$eventCategory = $this->EventCategory->find('list',array('conditions'=>array('EventCategory.status' => 'ACTIVE')));

		$this->set(compact('event','eventCategory'));
	}

	public function admin_change_event_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventID = $this->request->data['eventID'];
			$eventData = $this->Event->find("first",
				array(
					"fields"=>array('Event.status','Event.id'),
					"conditions" => array("Event.id" => $eventID),
					"contain" => false,
				)
			);

			$statusToChange = ($eventData['Event']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$eventData['Event']['status'] = $statusToChange;
			if($this->Event->save($eventData))
			{
				$response['status'] = 1;
				$response['text'] = ($eventData['Event']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_change_event_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventID = $this->request->data['eventID'];
			$eventData = $this->Event->find("first",
				array(
					"fields"=>array('Event.status','Event.publish_status','Event.id'),
					"conditions" => array("Event.id" => $eventID),
					"contain" => false,
				)
			);

			if($eventData['Event']['status'] == 'ACTIVE')
			{
				if($eventData['Event']['publish_status'] == 'PUBLISHED')
				{
					$response['status'] = 0;
					$response['text'] = 'PUBLISHED';
				}
				else
				{
					$eventData['Event']['publish_status'] = 'PUBLISHED';
					if($this->Event->save($eventData))
					{
						$response['status'] = 1;
						$response['text'] = 'PUBLISHED';
					}
					else
					{
						$response['status'] = 0;
						$response['text'] = 'UNPUBLISHED';
					}
				}
			}
			else
			{
				$response['status'] = 2;
				$response['text'] = $eventData['Event']['publish_status'];
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_view_event()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventID = $this->request->data['eventID'];
			$rowData = $this->Event->find("first",
				array(
					"fields"=>array('Event.*','EventCategory.title'),
					"conditions" => array("Event.id" => $eventID),
					"contain" => array('EventCategory'),
				)
			);

		//	pr($rowData);


			if(!empty($rowData))
			{
				$response['status'] = '1';

				$tags= explode(',',$rowData['Event']['tags']);
				$tags = array_map(function($val) { return '#'.$val;} , $tags);
				$tags = implode(',',$tags);

				$html = "<tr><td>Title:</td><td>".$rowData['Event']['title']."</td></tr>";
				$html .= "<tr><td>Description:</td><td>".$rowData['Event']['description']."</td></tr>";
				$html .= "<tr><td>Start at:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Event']['start_datetime']))."</td></tr>";
				$html .= "<tr><td>End at:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Event']['end_datetime']))."</td></tr>";
				$html .= "<tr><td>Tags:</td><td>".$tags."</td></tr>";
				$html .= "<tr><td>Category:</td><td>".$rowData['EventCategory']['title']."</td></tr>";
				$html .= "<tr><td>Address:</td><td>".$rowData['Event']['address']."</td></tr>";
				$html .= "<tr><td>Venue:</td><td>".$rowData['Event']['venue']."</td></tr>";
				$html .= "<tr><td>Contact Phone:</td><td>".$rowData['Event']['contact_phone']."</td></tr>";
				$html .= "<tr><td>Show On MBroadcast:</td><td>".$rowData['Event']['show_on_mbroadcast']."</td></tr>";
				$html .= "<tr><td>Status:</td><td>".$rowData['Event']['venue']."</td></tr>";
				$html .= "<tr><td>Created:</td><td>".date('d-M-Y H:i:s',strtotime($rowData['Event']['created']))."</td></tr>";

				$response['html'] = $html;
				$response['lat'] = $rowData['Event']['latitude'];
				$response['lng'] = $rowData['Event']['longitude'];
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

	public function admin_media_event($eventID=null){
		$eventID=base64_decode($eventID);
		$eventMedia = $this->Event->find('first',array(
			'fields'=>array('Event.id','Event.title'),
			'conditions'=>array('Event.id'=>$eventID),
			'contain'=>array('EventMedia')
		));

		if(empty($eventMedia))
		{
			$this->Session->setFlash(__('Sorry, Event was not found.'), 'default', array(), 'error');
			$this->redirect(array('controller' => 'admin', 'action' => 'event'));
			return false;
		}
		$this->set(compact('eventMedia'));

	}

	public function admin_change_event_media_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventMediaID = $this->request->data['eventMediaID'];
			$eventMediaData = $this->EventMedia->find("first",
				array(
					"fields"=>array('EventMedia.status','EventMedia.id'),
					"conditions" => array("EventMedia.id" => $eventMediaID),
					"contain" => false,
				)
			);

			$statusToChange = ($eventMediaData['EventMedia']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$eventMediaData['EventMedia']['status'] = $statusToChange;
			if($this->EventMedia->save($eventMediaData))
			{
				$response['status'] = 1;
				$response['text'] = ($eventMediaData['EventMedia']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_update_event_media_cover(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventMediaID = $this->request->data['eventMediaID'];
			$eventID = $this->request->data['eventID'];

			$eventMediaData = $this->EventMedia->find("first",
				array(
					"fields"=>array('EventMedia.is_cover_image','EventMedia.id'),
					"conditions" => array("EventMedia.id" => $eventMediaID),
					"contain" => false,
				)
			);

			$eventMediaData['EventMedia']['is_cover_image'] = 'YES';

			$this->EventMedia->updateAll(
				array('EventMedia.is_cover_image'=>"'NO'"),
				array('EventMedia.event_id'=>$eventID)
			);

			if($this->EventMedia->save($eventMediaData))
			{
				$response['status'] = 1;
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

	public function admin_upload_event_media()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){


			if(isset($this->request->data['Message'])) {
				$data = $this->request->data['Message']['file'];
				$file_type = $this->request->data['Message']['file']['type'];
				$message_type = $this->request->data['Message']['message_type'];
				$eventID = $this->request->data['Message']['event_id'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {

					if ($message_type == "IMAGE") {
						$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
						if (in_array($file_type, $mimeAarray)) {

							if ($url = $this->Custom->uploadFileToAws($data)) {
								$response["status"] = 1;
								$response["message"] = "File uploaded successfully.";
								$response["url"] = $url;
								$inData = array();
								$inData['media_path'] = $url;
								$inData['media_type'] = $message_type;
								$inData['event_id'] = $eventID;

								$this->EventMedia->create();
								$this->EventMedia->save($inData);
								
							} else {
								$response["status"] = 0;
								$response["message"] = "Sorry file could not upload";
							}

						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry you can send only text message";

						}
					}
					if ($message_type == "VIDEO") {
						$mimeAarray = array('video/mp4', 'image/ogg', 'image/wmp');
						if (in_array($file_type, $mimeAarray)) {
							if ($url = $this->Custom->uploadFileToAws($data)) {
								$response["status"] = 1;
								$response["message"] = "File uploaded successfully.";
								$response["url"] = $url;
								$inData = array();
								$inData['media_path'] = $url;
								$inData['media_type'] = $message_type;
								$inData['event_id'] = $eventID;

								$this->EventMedia->create();
								$this->EventMedia->save($inData);
							} else {
								$response["status"] = 0;
								$response["message"] = "Sorry file could not upload";
							}
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry invalid video format file";
						}
					}
					

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}

	public function admin_view_event_result(){

		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventID = $this->request->data['eventID'];
			$rowData = $this->EventResponse->find('all',array(
				'fields'=>array('EventResponse.response_type','count(*) AS totalResponse',),
				'conditions'=>array('EventResponse.event_id'=>$eventID),
				'contain' => false,
				'group' => 'EventResponse.response_type'
			));

			//	pr($rowData);


			if(!empty($rowData))
			{
				$response['status'] = '1';
				$html='';

					foreach ( $rowData as $value )
					{
						$html .= "<tr><td>".$value['EventResponse']['response_type']."</td><td>".$value[0]['totalResponse']."</td></tr>";
					}
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

	public function admin_organizer_event($eventID=null){
		$eventID=base64_decode($eventID);
		if($this->request->is(array('post','put')))
		{
			$dataToInsert = $this->request->data;
			$dataToInsert['EventOrganizer']['event_id'] = $eventID;
			$image = $dataToInsert['EventOrganizer']['image'];
			unset($dataToInsert['EventOrganizer']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToInsert['EventOrganizer']['image'] = $url;
						$this->EventOrganizer->create();
						if($inserted = $this->EventOrganizer->save($dataToInsert))
						{
							$this->Session->setFlash(__('Organizer added successfully.'), 'default', array(), 'success');
							unset($this->request->data);
						}
						else
						{
							$this->Session->setFlash(__('Organizer could not be added.'), 'default', array(), 'error');

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
				$this->EventOrganizer->create();
				if($inserted = $this->EventOrganizer->save($dataToInsert))
				{
					$this->Session->setFlash(__('Organizer added successfully.'), 'default', array(), 'success');
					unset($this->request->data);
				}
				else
				{
					$this->Session->setFlash(__('Organizer could not be added.'), 'default', array(), 'error');
				}
			}
		}

		$this->paginate = array(
			'fields'=>array('EventOrganizer.*','Event.title'),
			'conditions'=>array('EventOrganizer.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventOrganizer.id DESC',
			'limit'=>10
		);
		$eventOrganizer = $this->paginate('EventOrganizer');
		$this->set(compact('eventOrganizer'));
	}

	public function admin_get_event_organizer_edit(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$ID = $this->request->data['ID'];
			$responseData = array();
			$responseData = $this->EventOrganizer->find('first',array(
				'fields'=>array('EventOrganizer.*'),
				'conditions'=>array('EventOrganizer.id'=>$ID),
				'contain'=>false
			));
			if(!empty($responseData))
			{
				$response['status'] = 1;
				$response['data'] = $responseData['EventOrganizer'];
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

	public function admin_edit_event_organizer(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			//pr($this->request->data); die;
			if($this->EventOrganizer->save($this->request->data))
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

	public function admin_change_event_organizer_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['ID'];
			$data = $this->EventOrganizer->find("first",
				array(
					"fields"=>array('EventOrganizer.status','EventOrganizer.id'),
					"conditions" => array("EventOrganizer.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['EventOrganizer']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$data['EventOrganizer']['status'] = $statusToChange;
			if($this->EventOrganizer->save($data))
			{
				$response['status'] = 1;
				$response['text'] = ($data['EventOrganizer']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_speaker_event($eventID=null){
		$eventID=base64_decode($eventID);


		if($this->request->is(array('post','put')))
		{
			$dataToInsert = $this->request->data;
			$dataToInsert['EventSpeaker']['event_id'] = $eventID;
			$image = $dataToInsert['EventSpeaker']['image'];
			unset($dataToInsert['EventSpeaker']['image']);
			if(!empty($image['tmp_name']))
			{
				$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
				if (in_array($image['type'], $mimeAarray))
				{
					if ($url = $this->Custom->uploadFileToAws($image))
					{
						$dataToInsert['EventSpeaker']['image'] = $url;
						$this->EventSpeaker->create();
						if($inserted = $this->EventSpeaker->save($dataToInsert))
						{
							$this->Session->setFlash(__('Speaker added successfully.'), 'default', array(), 'success');
							unset($this->request->data);
						}
						else
						{
							$this->Session->setFlash(__('Speaker could not be added.'), 'default', array(), 'error');

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
				$this->EventSpeaker->create();
				if($inserted = $this->EventSpeaker->save($dataToInsert))
				{
					$this->Session->setFlash(__('Speaker added successfully.'), 'default', array(), 'success');
					unset($this->request->data);
				}
				else
				{
					$this->Session->setFlash(__('Speaker could not be added.'), 'default', array(), 'error');
				}
			}
		}


		$this->paginate = array(
			'fields'=>array('EventSpeaker.*','Event.title'),
			'conditions'=>array('EventSpeaker.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventSpeaker.id DESC',
			'limit'=>10
		);
		$eventSpeaker = $this->paginate('EventSpeaker');
		$this->set(compact('eventSpeaker'));
	}

	public function admin_get_event_speaker_edit(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$ID = $this->request->data['ID'];
			$responseData = array();
			$responseData = $this->EventSpeaker->find('first',array(
				'fields'=>array('EventSpeaker.*'),
				'conditions'=>array('EventSpeaker.id'=>$ID),
				'contain'=>false
			));
			if(!empty($responseData))
			{
				$response['status'] = 1;
				$response['data'] = $responseData['EventSpeaker'];
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

	public function admin_edit_event_speaker(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			//pr($this->request->data); die;
			if($this->EventSpeaker->save($this->request->data))
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

	public function admin_change_event_speaker_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['ID'];
			$data = $this->EventSpeaker->find("first",
				array(
					"fields"=>array('EventSpeaker.status','EventSpeaker.id'),
					"conditions" => array("EventSpeaker.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['EventSpeaker']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$data['EventSpeaker']['status'] = $statusToChange;
			if($this->EventSpeaker->save($data))
			{
				$response['status'] = 1;
				$response['text'] = ($data['EventSpeaker']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_agenda_event($eventID=null){
		$eventID=base64_decode($eventID);
		if($this->request->is(array('post','put')))
		{
			$dataToInsert = $this->request->data;
			$dataToInsert['EventAgenda']['event_id'] = $eventID;
			$dataToInsert['EventAgenda']['start_datetime'] = $dataToInsert['EventAgenda']['start_date']." ".$dataToInsert['EventAgenda']['start_time'];
			$dataToInsert['EventAgenda']['end_datetime'] = $dataToInsert['EventAgenda']['end_date']." ".$dataToInsert['EventAgenda']['end_time'];
			unset($dataToInsert['EventAgenda']['end_date']);
			unset($dataToInsert['EventAgenda']['end_time']);
			unset($dataToInsert['EventAgenda']['start_date']);
			unset($dataToInsert['EventAgenda']['start_time']);

			if( strtotime($dataToInsert['EventAgenda']['start_datetime']) >= strtotime($dataToInsert['EventAgenda']['end_datetime']) )
			{
				$this->Session->setFlash(__('start date time could not be more then end date time.'), 'default', array(), 'error');
			}
			else
			{
				$this->EventAgenda->create();
				if($inserted = $this->EventAgenda->save($dataToInsert))
				{
					$this->Session->setFlash(__('Agenda added successfully.'), 'default', array(), 'success');
					unset($this->request->data);
				}
				else
				{
					$this->Session->setFlash(__('Agenda could not be added.'), 'default', array(), 'error');
				}
			}

		}
		$this->paginate = array(
			'fields'=>array('EventAgenda.*','Event.title'),
			'conditions'=>array('EventAgenda.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventAgenda.id DESC',
			'limit'=>10
		);
		$eventAgenda = $this->paginate('EventAgenda');
		$event = $this->Event->find('first',array('fields'=>array('Event.start_datetime','Event.end_datetime'),'contain'=>false,'conditions'=>array('Event.id'=>$eventID)));
		$this->set(compact('eventAgenda','event'));
	}

	public function admin_get_event_agenda_edit(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$ID = $this->request->data['ID'];
			$responseData = array();
			$responseData = $this->EventAgenda->find('first',array(
				'fields'=>array('EventAgenda.*'),
				'conditions'=>array('EventAgenda.id'=>$ID),
				'contain'=>false
			));
			if(!empty($responseData))
			{
				$response['status'] = 1;
				$response['data'] = $responseData['EventAgenda'];
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

	public function admin_edit_event_agenda(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {

			$dataToInsert = $this->request->data;
			$dataToInsert['start_datetime'] = $dataToInsert['start_date']." ".$dataToInsert['start_time'];
			$dataToInsert['end_datetime'] = $dataToInsert['end_date']." ".$dataToInsert['end_time'];
			unset($dataToInsert['end_date']);
			unset($dataToInsert['end_time']);
			unset($dataToInsert['start_date']);
			unset($dataToInsert['start_time']);
			$response = array();
			if( strtotime($dataToInsert['start_datetime']) >= strtotime($dataToInsert['end_datetime']) )
			{
				$response['status'] = 0;
				$response['message'] = 'start date time could not be more then end date time.';
			}
			else
			{
				if($this->EventAgenda->save($dataToInsert))
				{
					$response['status'] = 1;
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = 'Sorry, Could not edit.';
				}
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_change_event_agenda_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['ID'];
			$data = $this->EventAgenda->find("first",
				array(
					"fields"=>array('EventAgenda.status','EventAgenda.id'),
					"conditions" => array("EventAgenda.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['EventAgenda']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$data['EventAgenda']['status'] = $statusToChange;
			if($this->EventAgenda->save($data))
			{
				$response['status'] = 1;
				$response['text'] = ($data['EventAgenda']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_show_event($eventID=null){

		$eventID=base64_decode($eventID);
		if($this->request->is(array('post','put')))
		{
			$dataToInsert = $this->request->data;
			$dataToInsert['EventShow']['event_id'] = $eventID;
			$dataToInsert['EventShow']['start_datetime'] = $dataToInsert['EventShow']['start_date']." ".$dataToInsert['EventShow']['start_time'];
			$dataToInsert['EventShow']['end_datetime'] = $dataToInsert['EventShow']['end_date']." ".$dataToInsert['EventShow']['end_time'];
			unset($dataToInsert['EventShow']['end_date']);
			unset($dataToInsert['EventShow']['end_time']);
			unset($dataToInsert['EventShow']['start_date']);
			unset($dataToInsert['EventShow']['start_time']);

			if( strtotime($dataToInsert['EventShow']['start_datetime']) >= strtotime($dataToInsert['EventShow']['end_datetime']) )
			{
				$this->Session->setFlash(__('start date time could not be more then end date time.'), 'default', array(), 'error');
			}
			else
			{
				$this->EventShow->create();
				if($inserted = $this->EventShow->save($dataToInsert))
				{
					$this->Session->setFlash(__('Show added successfully.'), 'default', array(), 'success');
					unset($this->request->data);
				}
				else
				{
					$this->Session->setFlash(__('Show could not be added.'), 'default', array(), 'error');
				}
			}
		}
		$this->paginate = array(
			'fields'=>array('EventShow.*','Event.title'),
			'conditions'=>array('EventShow.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventShow.id DESC',
			'limit'=>10
		);
		$eventShow = $this->paginate('EventShow');
		$event = $this->Event->find('first',array('fields'=>array('Event.start_datetime','Event.end_datetime'),'contain'=>false,'conditions'=>array('Event.id'=>$eventID)));
		$this->set(compact('eventShow','event'));
	}

	public function admin_get_event_show_edit(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$ID = $this->request->data['ID'];
			$responseData = array();
			$responseData = $this->EventShow->find('first',array(
				'fields'=>array('EventShow.*'),
				'conditions'=>array('EventShow.id'=>$ID),
				'contain'=>false
			));
			if(!empty($responseData))
			{
				$response['status'] = 1;
				$response['data'] = $responseData['EventShow'];
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

	public function admin_edit_event_show(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {

			$dataToInsert = $this->request->data;
			$dataToInsert['start_datetime'] = $dataToInsert['start_date']." ".$dataToInsert['start_time'];
			$dataToInsert['end_datetime'] = $dataToInsert['end_date']." ".$dataToInsert['end_time'];
			unset($dataToInsert['end_date']);
			unset($dataToInsert['end_time']);
			unset($dataToInsert['start_date']);
			unset($dataToInsert['start_time']);
			$response = array();
			if( strtotime($dataToInsert['start_datetime']) >= strtotime($dataToInsert['end_datetime']) )
			{
				$response['status'] = 0;
				$response['message'] = 'start date time could not be more then end date time.';
			}
			else
			{
				if($this->EventShow->save($dataToInsert))
				{
					$response['status'] = 1;
				}
				else
				{
					$response['status'] = 0;
					$response['message'] = 'Sorry, Could not edit.';
				}
			}

			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}

	public function admin_change_event_show_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['ID'];
			$data = $this->EventShow->find("first",
				array(
					"fields"=>array('EventShow.status','EventShow.id'),
					"conditions" => array("EventShow.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['EventShow']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$data['EventShow']['status'] = $statusToChange;
			if($this->EventShow->save($data))
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

	public function admin_ticket_event($eventID=null){
		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventTicket.*','EventShow.*','Event.title'),
			'conditions'=>array('EventTicket.event_id'=>$eventID),
			'contain'=>array('Event','EventShow'),
			'order'=> 'EventTicket.id DESC',
			'limit'=>10
		);
		$eventTicket = $this->paginate('EventTicket');
		$this->set(compact('eventTicket','eventID'));
	}

	public function admin_add_ticket_event($eventID=null){
		$eventID=base64_decode($eventID);
		if($this->request->is(array('post','put')))
		{
			$dataArr = $this->request->data;
			$dataToInsert = array();
			foreach($dataArr['data'] AS $data)
			{
				$data['event_id'] = $eventID;
				$data['available_count'] = $data['total_count'];
				$dataToInsert[] = $data;
			}
			if($this->EventTicket->saveAll($dataToInsert))
			{
				$this->Session->setFlash(__('Tickets saved successfully'), 'default', array(), 'success');
				$this->redirect(array('controller'=>'admin','action' => 'admin_ticket_event',base64_encode($eventID)));
			}
		}
		$eventShow = $this->EventShow->find('all',array(
			'filds'=>array('EventShow.*'),
			'conditions'=>array('EventShow.event_id'=>$eventID),
			'contain'=>false,
			'order'=>array('EventShow.start_datetime DESC')
		));
		$eventShowArr = array();
		foreach($eventShow as $eventValue)
		{
			$eventShowArr[$eventValue['EventShow']['id']] = date("d-M-Y H:i:s",strtotime($eventValue['EventShow']['start_datetime'])).' - '.date("d-M-Y H:i:s",strtotime($eventValue['EventShow']['end_datetime']));
		}

		$this->set(compact('eventShowArr','eventID'));
	}

	public function admin_change_event_ticket_status(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['ID'];
			$data = $this->EventTicket->find("first",
				array(
					"fields"=>array('EventTicket.status','EventTicket.id'),
					"conditions" => array("EventTicket.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['EventTicket']['status'] == 'ACTIVE')?'INACTIVE':'ACTIVE';
			$data['EventTicket']['status'] = $statusToChange;
			if($this->EventTicket->save($data))
			{
				$response['status'] = 1;
				$response['text'] = ($data['EventTicket']['status'] == 'ACTIVE')?'ACTIVE':'INACTIVE';
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

	public function admin_get_event_edit(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$ID = $this->request->data['ID'];
			$response = array();
			$ticket = $this->EventTicket->find('first',array(
				'fields'=>array('EventTicket.*'),
				'conditions'=>array('EventTicket.id'=>$ID),
				'contain'=>false
			));
			if(!empty($ticket))
			{
				$response['status'] = 1;
				$response['data'] = $ticket['EventTicket'];
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

	public function admin_edit_event_ticket(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			//pr($this->request->data); die;
			if($this->EventTicket->save($this->request->data))
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

	public function admin_get_event_allow_detail(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$eventID = $this->request->data['eventID'];
			$response = array();
			$event = $this->Event->find('first',array(
				'fields'=>array('Event.enable_agenda','Event.enable_organizer','Event.enable_show','Event.enable_speaker','Event.enable_ticket'),
				'conditions'=>array('Event.id'=>$eventID),
				'contain'=>false
			));
			if(!empty($event))
			{
				$dataToSend = array();
				foreach($event['Event'] as $key => $value)
				{
					$dataToSend[$key] = ($value == 'YES')?'ALLOWED':'NOT ALLOWED';
				}
				$response['status'] = 1;
				$response['data'] = $dataToSend;
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

	public function admin_change_event_allow(){
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ID = $this->request->data['eventID'];
			$field = $this->request->data['field'];
			$data = $this->Event->find("first",
				array(
					"fields"=>array('Event.'.$field,'Event.id'),
					"conditions" => array("Event.id" => $ID),
					"contain" => false,
				)
			);

			$statusToChange = ($data['Event'][$field] == 'YES')?'NO':'YES';
			$data['Event'][$field] = $statusToChange;
			if($this->Event->save($data))
			{
				$response['status'] = 1;
				$response['text'] = ($statusToChange == 'YES')?'ALLOWED':'NOT ALLOWED';
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

	public function admin_search_permit_event(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['title']))
		{
			$pram['t'] = $reqData['title'];
		}
		if(!empty($reqData['category']))
		{
			$pram['c'] = $reqData['category'];
		}
		if(!empty($reqData['date']))
		{
			$pram['d'] = $reqData['date'];
		}

		$this->redirect(
			array(
				"controller" => "admin",
				"action" => "admin_permit_event",
				"?" => $pram,
			)
		);
	}

	public function admin_permit_event(){
		$login = $this->Session->read('Auth.User');
		$searchData = $this->request->query;
		$conditions = array();

		if(isset($searchData['t']) && !empty($searchData['t']))
		{
			$this->request->data['Search']['title'] = $searchData['t'];
			$conditions["Event.title LIKE"] = '%'.$searchData['t'].'%';
		}
		if(isset($searchData['c']) && !empty($searchData['c']))
		{
			$this->request->data['Search']['category'] = $searchData['c'];
			$conditions["Event.event_category_id"] = $searchData['c'];
		}
		if(isset($searchData['d']) && !empty($searchData['d']))
		{
			$this->request->data['Search']['date'] = $searchData['d'];
			$conditions[] = "( Event.start_datetime <= '".$searchData['d']."' AND Event.end_datetime >= '".$searchData['d']."' )";
		}
		$conditions["Event.show_on_mbroadcast"] = 'YES';

		$this->paginate = array(
			'fields'=>array('Event.*','EventCategory.title','CreatedBy.username'),
			'conditions'=>$conditions,
			'contain'=>array( 'EventCategory','CreatedBy' ),
			'order'=> 'Event.id DESC',
			'limit'=>10
		);
		$event = $this->paginate('Event');
		$eventCategory = $this->EventCategory->find('list',array('conditions'=>array('EventCategory.status' => 'ACTIVE')));
		$this->set(compact('event','eventCategory'));
	}

	public function admin_change_event_mbroadcast_publish_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$eventID = $this->request->data['eventID'];
			$eventData = $this->Event->find("first",
				array(
					"fields"=>array('Event.mbroadcast_publish_status','Event.id'),
					"conditions" => array("Event.id" => $eventID),
					"contain" => false,
				)
			);

			$statusToChange = ($eventData['Event']['mbroadcast_publish_status'] != 'APPROVED')?'APPROVED':'PENDING';
			$eventData['Event']['mbroadcast_publish_status'] = $statusToChange;
			if($this->Event->save($eventData))
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

	public function admin_permit_media_event($eventID=null){
		$eventID=base64_decode($eventID);
		$eventMedia = $this->Event->find('first',array(
			'fields'=>array('Event.id','Event.title'),
			'conditions'=>array('Event.id'=>$eventID),
			'contain'=>array('EventMedia')
		));
		if(empty($eventMedia))
		{
			$this->Session->setFlash(__('Sorry, Event was not found.'), 'default', array(), 'error');
			$this->redirect(array('controller' => 'admin', 'action' => 'event'));
			return false;
		}
		$this->set(compact('eventMedia'));

	}

	public function admin_permit_organizer_event($eventID=null){
		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventOrganizer.*','Event.title'),
			'conditions'=>array('EventOrganizer.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventOrganizer.id DESC',
			'limit'=>10
		);
		$eventOrganizer = $this->paginate('EventOrganizer');
		$this->set(compact('eventOrganizer'));
	}

	public function admin_permit_speaker_event($eventID=null){
		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventSpeaker.*','Event.title'),
			'conditions'=>array('EventSpeaker.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventSpeaker.id DESC',
			'limit'=>10
		);
		$eventSpeaker = $this->paginate('EventSpeaker');
		$this->set(compact('eventSpeaker'));
	}

	public function admin_permit_agenda_event($eventID=null){
		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventAgenda.*','Event.title'),
			'conditions'=>array('EventAgenda.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventAgenda.id DESC',
			'limit'=>10
		);
		$eventAgenda = $this->paginate('EventAgenda');
		$event = $this->Event->find('first',array('fields'=>array('Event.start_datetime','Event.end_datetime'),'contain'=>false,'conditions'=>array('Event.id'=>$eventID)));
		$this->set(compact('eventAgenda','event'));
	}

	public function admin_permit_show_event($eventID=null){

		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventShow.*','Event.title'),
			'conditions'=>array('EventShow.event_id'=>$eventID),
			'contain'=>array('Event'),
			'order'=> 'EventShow.id DESC',
			'limit'=>10
		);
		$eventShow = $this->paginate('EventShow');
		$event = $this->Event->find('first',array('fields'=>array('Event.start_datetime','Event.end_datetime'),'contain'=>false,'conditions'=>array('Event.id'=>$eventID)));
		$this->set(compact('eventShow','event'));
	}

	public function admin_permit_ticket_event($eventID=null){
		$eventID=base64_decode($eventID);
		$this->paginate = array(
			'fields'=>array('EventTicket.*','EventShow.*','Event.title'),
			'conditions'=>array('EventTicket.event_id'=>$eventID),
			'contain'=>array('Event','EventShow'),
			'order'=> 'EventTicket.id DESC',
			'limit'=>10
		);
		$eventTicket = $this->paginate('EventTicket');
		$this->set(compact('eventTicket','eventID'));
	}

/*****EVENT END HERE*****/


	

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
				"controller" => "admin",
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
							$this->redirect(array('controller' => 'admin', 'action' => 'quest'));
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
					$this->redirect(array('controller' => 'admin', 'action' => 'quest'));
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
										$this->redirect(array('controller' => 'admin', 'action' => 'quest'));
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
								$this->redirect(array('controller' => 'admin', 'action' => 'quest'));
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

	public function admin_search_permit_quest(){
		$reqData = $this->request->query;
		$pram = array();
		if(!empty($reqData['quest_category']))
		{
			$pram['c'] = $reqData['quest_category'];
		}
		$this->redirect(
			array(
				"controller" => "admin",
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
				"controller" => "admin",
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
							$this->redirect(array('controller' => 'admin', 'action' => 'buy'));
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
					$this->redirect(array('controller' => 'admin', 'action' => 'buy'));
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
							$this->redirect(array('controller' => 'admin', 'action' => 'buy'));
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
					$this->redirect(array('controller' => 'admin', 'action' => 'buy'));
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
				"controller" => "admin",
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
				"controller" => "admin",
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
			$this->redirect(array('controller' => 'admin', 'action' => 'sell'));
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
				"controller" => "admin",
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
			$this->redirect(array('controller' => 'admin', 'action' => 'sell'));
			return false;
		}
		$this->set(compact('sellImages'));
	}

/********SELL END HERE********/




	/*****************************************
        function :- Access denied
        *****************************************/

	public function admin_accessDenied(){

		$this->layout = "admin_home";
	}

	public function admin_login()
	{


		$this->layout = "admin_home";
		if ($this->Auth->loggedIn()) {

			//$this->redirect(array('controller' => 'admin', 'action' => 'index'));
			$user = $this->Session->read('Auth.User');
			if(isset($user['User']['role_id']) && $user['User']['role_id']==4){
				$this->redirect(array('controller' => 'supp', 'action' => 'dashboard_report','admin' => true));
			}
			else if(isset($user['User']['role_id']) && $user['User']['role_id']==2){
				$this->redirect(array('controller' => 'admin', 'action' => 'index', 'admin' => true));
			}else{
                $this->Auth->logout();
			}

		} else if ($this->request->is('post')) {

			$mobile = $this->data['User']['mobile'];
			$password = md5($this->data['User']['password']);
			$role_id = $this->data['User']['role_id'];
			$user = $this->User->find('first', array('conditions' => array('User.mobile' => $mobile)));
			$conditions = array(
				'User.mobile' => "+91".$mobile,
				'User.password' => $password,
				'User.role_id' => $role_id,
				//'User.role_id' => 2,
				'User.status' => 'Y');

			$isUserAdmin = $this->User->find("first", array("conditions" => $conditions));
			if (empty($isUserAdmin)) {
				$this->Session->setFlash(__('Invalid Mobile No or Password'), 'default', array(), 'error');
				//$this->redirect(array('action' => 'login'));
			} else {
				$userdata = $this->User->find("first", $conditions);


				if($role_id==4){
					if ($this->Auth->login($isUserAdmin['User'])) {
						$this->redirect(array('controller' => 'supp', 'action' => 'dashboard_report','admin' => true));
					}
				}
				else if($role_id==2){

					if ($this->Auth->login($isUserAdmin['User'])) {
						$this->redirect(array('controller' => 'admin', 'action' => 'index', 'admin' => true));
					}
				}else{

					$this->Session->setFlash(__('Invalid mobile number or password.'), 'default', array(), 'error');

				}


			}
			/* if($user['User']['status'] == 'f')
              {
              $this->Session->setFlash(__('Your Account has been deactivated.'),'default', array(),'error');
              } */
		}
	}



	public function admin_change_message_status()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$response = array();
			$ticketID = base64_decode($this->request->data['id']);
			$data = $this->Message->find('first',array('conditions'=>array('Message.id'=>$ticketID),'fields'=>array('Message.status','Message.id'),'contain'=>false));
			$statusToChange = ($data['Message']['status'] == 'N')?'Y':'N';
			$data['Message']['status'] = $statusToChange;
			if($save = $this->Message->save($data))
			{
				$response['status'] = 1;
				$response['text'] = $statusToChange;
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = 'Sorry, Operation Failed.';
			}
			$response = json_encode($response, true);
			echo $response;
			exit();
		}
	}


	/*function add by mahendra*/
	public function admin_upload()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){
			if(isset($this->request->data['Channel'])) {
				$data = $this->request->data['Channel']['file'];
				$file_type = $this->request->data['Channel']['file']['type'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {
					$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
					if (in_array($file_type, $mimeAarray)) {

						if ($url = $this->Custom->uploadFileToAws($data)) {
							$response["status"] = 1;
							$response["message"] = "File uploaded successfully.";
							$response["url"] = $url;
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry file could not upload";
						}
					} else {
						$response["status"] = 0;
						$response["message"] = "Please upload image file.";
					}

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}


	public function admin_image_upload()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){
			if(isset($this->request->data['User'])) {
				$data = $this->request->data['User']['file'];
				$file_type = $this->request->data['User']['file']['type'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {
					$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
					if (in_array($file_type, $mimeAarray)) {

						if ($url = $this->Custom->uploadFileToAws($data)) {
							$response["status"] = 1;
							$response["message"] = "File uploaded successfully.";
							$response["url"] = $url;
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry file could not upload";
						}
					} else {
						$response["status"] = 0;
						$response["message"] = "Please upload image file.";
					}

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}



	public function admin_banner_image()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){
			if(isset($this->request->data['AppServiceOffer'])) {
				$data = $this->request->data['AppServiceOffer']['file'];
				$file_type = $this->request->data['AppServiceOffer']['file']['type'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {
					$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
					if (in_array($file_type, $mimeAarray)) {

						if ($url = $this->Custom->uploadFileToAws($data)) {
							$response["status"] = 1;
							$response["message"] = "File uploaded successfully.";
							$response["url"] = $url;
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry file could not upload";
						}
					} else {
						$response["status"] = 0;
						$response["message"] = "Please upload image file.";
					}

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}

	public function admin_category_image()
	{
		$this->autoRender = false;
		if($this->request->is('ajax')){
			if(isset($this->request->data['ServiceMenuCategory'])) {
				$data = $this->request->data['ServiceMenuCategory']['file'];
				$file_type = $this->request->data['ServiceMenuCategory']['file']['type'];
				$response = array();
				if (isset($data['tmp_name']) && !empty($data['tmp_name'])) {
					$mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
					if (in_array($file_type, $mimeAarray)) {

						if ($url = $this->Custom->uploadFileToAws($data)) {
							$response["status"] = 1;
							$response["message"] = "File uploaded successfully.";
							$response["url"] = $url;
						} else {
							$response["status"] = 0;
							$response["message"] = "Sorry file could not upload";
						}
					} else {
						$response["status"] = 0;
						$response["message"] = "Please upload image file.";
					}

				} else {
					$response["status"] = 0;
					$response["message"] = "Please upload file.";
				}
			}else{
				$response["status"] = 0;
				$response["message"] = "Sorry file could not post.";
			}
			return json_encode($response);
		}else{
			exit();
		}
	}


	public function admin_logout()
	{

		$logintime = date("Y-m-d H:i:s");
		$this->User->id = $this->Auth->User('id');
		$this->User->saveField('lastlogin', $logintime);
		$this->Session->destroy();
		$this->Cookie->delete('Auth.User');
		//die;
		$this->redirect('login');
	}


}
