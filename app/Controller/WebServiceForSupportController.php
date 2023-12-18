<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '-1');

class WebServiceForSupportController extends AppController {

    public $name = "web_service_for_support";
    public $uses = array('Thinapp','UploadApk','Leads','AppQueries');
    public $components = array('Custom');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        $this->layout = false;
        $this->autoRender = false;
        
    }
	
	public function getThinAppDetails(){
		
        if ($this->request->is('post')) {
            $data = $this->request->data;
			$app_key = $data['app_key'];
            $thinAppID = $data['thin_app_id'];
            $response = array();
            if ($app_key != '' && $thinAppID != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key,$thinAppID)) && ($result == 1) ) {
                   
				   $dataToSend = $this->Thinapp->find('first',array(
                        'fields' => array('Thinapp.id','Thinapp.name','Thinapp.logo'),
						'conditions' => array(
                            'Thinapp.id'=>$thinAppID
                        ),
                        'contain' =>false,
                        'autocache'=>true

                    ));
					
                    if(!empty($dataToSend)) {
						$response['status'] = 1;
                        $response['data'] = $dataToSend;
                    }
                    else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry, Thin app details not found!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter.";
            }

            echo  json_encode($response);
            exit;
        }
    
		
	}
	
	
	public function uploadApk(){
			
        if ($this->request->is('post')) {
		$response = array();
					$versionName = $this->request->data['versionName'];
					$versionCode = $this->request->data['versionCode'];
					$appName = $this->request->data['appName'];
					$thinAppID = $this->request->data['thinAppID'];
					$userID = $this->request->data['userID'];
						$file = $_FILES['apk_file'];
						$fileTmp = $file['tmp_name'];
						$exploadname = explode('.', $file['name']);
						$ext = end($exploadname);
						$fileName = uniqid().'.'.$ext;
						$uploadPath = WWW_ROOT . "uploads".DS.'apk'.DS;
					
					if(move_uploaded_file($fileTmp,$uploadPath.$fileName))
					{
						
						$leads = $this->Leads->find("first", array(
							"conditions" => array(
								"Leads.app_id" => $thinAppID,
							),
							"contain" => false
						));
						
							$login['id'] = $userID;
							$this->UploadApk->create();
							$inData = array();
							$inData['customer_lead_id']=$leads['Leads']['customer_id'];
							$inData['support_admin_id']=$login['id'];
							$inData['name']=$fileName;
							$version = $versionName;
							$inData['version']=$version;
							$inData['status']='INPROCESS';
							$upData = $this->UploadApk->save($inData);
							$post['upload_apk_id'] = $upData['UploadApk']['id'];
					}
			
				$post['customer_lead_id'] = $leads['Leads']['customer_id'];
				$post['message']= 'Dear user, Your bussiness application is ready for test.Please have a look and let us know your feedback.  Thank you.';
				$post['sender_id']=  $login['id'];
				$post['support_admin_id']=  $login['id'];
				$post['app_id']=    $leads['Leads']['app_id'];
				$post['reciver_id']=  $leads['Leads']['user_id'];
				if($this->AppQueries->save($post)){
					$response['status'] = 'UPLOADED';
				}else{
					$response['status'] = 'Not UPLOADED';
				}
			echo  json_encode($response);
            exit;
		} 
	}			
	
	

}
