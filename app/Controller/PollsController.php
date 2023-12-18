<?php class PollsController extends AppController {

	//public $name = 'Supsp';
	public $helpers = array('Custom');
	public $uses = array('ActionQuestion','ActionResponse','Subscriber');

        /*****************************************
        function :- index 
        *****************************************/
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'poll_for_all';
		$this->Auth->allow('index');

	}


	public function index($subscriberID=null,$actionQuestionsID=null){
			$subscriberID = base64_decode($subscriberID);
			$actionQuestionsID = base64_decode($actionQuestionsID);

			$response = array();
			$response['status'] = 0;
			$response['showGraph'] = 0;


			$countAns = $this->ActionResponse->find("count", array(
				"conditions" => array(
					"ActionResponse.subscriber_id" => $subscriberID,
					"ActionResponse.action_question_id" => $actionQuestionsID,
				),
				"contain"=>false
			));
			$questionChoice = $this->ActionQuestion->hasMany['QuestionChoice'];
			$questionResponse = $this->ActionQuestion->hasMany['ActionResponse'];



			unset($this->ActionQuestion->hasMany['ActionResponse']);
			unset($this->ActionQuestion->hasMany['QuestionChoice']);

					$now = date('Y-m-d H:i:s');
					$question = $this->ActionQuestion->find("first", array(
							"conditions" => array(
								"ActionQuestion.id" => $actionQuestionsID,
								"ActionQuestion.end_time >=" => $now,
							),
							"contain"=>array('ActionType'),
						)
					);

		if(empty($question))
		{
			$this->ActionQuestion->hasMany['QuestionChoice'] = $questionChoice;
			$this->ActionQuestion->hasMany['ActionResponse'] = $questionResponse;
			$response['status'] = 1;
			$response['message'] = "Sorry, Poll is either not found or expired.";
			$this->set(compact('response','actionQuestionsID'));
			return;
		}
		else if($countAns > 0)
		{
			$this->ActionQuestion->hasMany['QuestionChoice'] = $questionChoice;
			$this->ActionQuestion->hasMany['ActionResponse'] = $questionResponse;
			$response['status'] = 1;
			$response['message'] = "You have already used this poll.";
			$response['showGraph'] = 1;
			if($question['ActionQuestion']['poll_publish'] == 'PRIVATE')
			{
				$response['showGraph'] = 0;
			}
			//echo $response['showGraph']; die();
			$this->set(compact('response','actionQuestionsID'));
			return;
		}


		if ($this->request->is(array('post', 'put'))) {
			$post = $this->request->data;

			if($question['ActionType']['name'] == 'SHORT ANSWER'){

				 $subscriber = $this->Subscriber->find("first",
						 array(
							 "conditions" => array(
								 "Subscriber.id" => $subscriberID,
							 ),
							 "fields"=>array(
								 "Subscriber.id",
								 "Subscriber.mobile",
								 "Subscriber.app_user_id"
							 ),
							 "contain"=>false,
						 )
					 );

				 $this->ActionResponse->create();
				 $inData = array();
				 $inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				 $inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				 $inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				 $inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;
				 $inData['action_type_id']=$question['ActionType']['id'];
				 $inData['question_choice_id']=key($response['response']);
				 $inData['user_input_values']=$post['response'][key($post['response'])];

					if($this->ActionResponse->save($inData))
					{
						$this->ActionQuestion->id = $question['ActionQuestion']['id'];

							$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

						$response['status'] = 1;
						$response['message'] = 'Your answer posted successfully.';

					}else{
						$response['status'] = 0;
						$response['message'] = 'Sorry your answer could not be post.';
					}

			 }

			if($question['ActionType']['name'] == 'LONG ANSWER'){

				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();


				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($response['response']);
				$inData['user_input_values']=$post['response'][key($post['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'DROPDOWN'){
				$res = explode(',',$post['response']);
				$response['response'] = array($res[0]=>$res[1]);
//pr($response); die;
				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();
				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($response['response']);
				$inData['user_input_values']=$response['response'][key($response['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'YES/NO'){
				$res = explode(',',$post['response']);
				$post['response'] = array($res[0]=>$res[1]);
//pr($response); die;
				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();
				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($post['response']);
				$inData['user_input_values']=$post['response'][key($post['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'DATE'){
//pr($response); die;
				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();

				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($post['response']);
				$inData['user_input_values']=$post['response'][key($post['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'RATING'){
				$res = explode(',',$post['response']);
				$post['response'] = array($res[0]=>$res[1]);
//pr($response); die;
				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();

				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($post['response']);
				$inData['user_input_values']=$post['response'][key($post['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'MULTIPLE INPUTS'){

				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();
				$n = 0;
				foreach($post['response'] AS $choiceID => $inputValue)
				{

					$inData[$n]['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
					$inData[$n]['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
					$inData[$n]['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
					$inData[$n]['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

					$inData[$n]['action_type_id']=$question['ActionType']['id'];
					$inData[$n]['question_choice_id']=$choiceID;
					$inData[$n]['user_input_values']=$inputValue;
					$n++;
				}
//pr($inData); die;
				if($this->ActionResponse->saveAll($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'MULTIPLE CHOICES'){

				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);
				//pr($response); die;
				$this->ActionResponse->create();
				$inData = array();
				$n = 0;
				foreach($post['response'] AS $choiceID => $inputValue)
				{

					$inData[$n]['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
					$inData[$n]['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
					$inData[$n]['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
					$inData[$n]['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

					$inData[$n]['action_type_id']=$question['ActionType']['id'];
					$inData[$n]['question_choice_id']=$choiceID;
					$inData[$n]['user_input_values']=$inputValue;
					$n++;
				}
//pr($inData); die;
				if($this->ActionResponse->saveAll($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'SCALING'){
				$res = explode(',',$post['response']);
				$post['response'] = array($res[0]=>$res[1]);
//pr($response); die;
				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();
				$inData['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
				$inData['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
				$inData['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
				$inData['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;

				$inData['action_type_id']=$question['ActionType']['id'];
				$inData['question_choice_id']=key($post['response']);
				$inData['user_input_values']=$post['response'][key($post['response'])];

				if($this->ActionResponse->save($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

			if($question['ActionType']['name'] == 'RANKING'){

				$subscriber = $this->Subscriber->find("first",
					array(
						"conditions" => array(
							"Subscriber.id" => $subscriberID,
						),
						"fields"=>array(
							"Subscriber.id",
							"Subscriber.mobile",
							"Subscriber.app_user_id"
						),
						"contain"=>false,
					)
				);

				$this->ActionResponse->create();
				$inData = array();
				$n = 0;
				foreach($post['response'] AS $choiceID => $inputValue)
				{

					$inData[$n]['action_question_id'] = isset($actionQuestionsID)?$actionQuestionsID:0;
					$inData[$n]['mobile_number'] = (isset($subscriber['Subscriber']['mobile']) && !empty($subscriber['Subscriber']['mobile']))?$subscriber['Subscriber']['mobile']:0;
					$inData[$n]['user_id']=(isset($subscriber['Subscriber']['app_user_id']) && !empty($subscriber['Subscriber']['app_user_id']))?$subscriber['Subscriber']['app_user_id']:0;
					$inData[$n]['subscriber_id']=(isset($subscriber['Subscriber']['id']) && !empty($subscriber['Subscriber']['id']))?$subscriber['Subscriber']['id']:0;




					$inData[$n]['action_type_id']=$question['ActionType']['id'];
					$inData[$n]['question_choice_id']=$choiceID;
					$inData[$n]['user_input_values']=$inputValue;
					$n++;
				}
//pr($inData); die;
				if($this->ActionResponse->saveAll($inData))
				{
					$this->ActionQuestion->id = $question['ActionQuestion']['id'];

					$this->ActionQuestion->saveField( 'response_count', ($question['ActionQuestion']['response_count']+1) );

					$response['status'] = 1;
					$response['message'] = 'Your answer posted successfully.';

				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry your answer could not be post.';
				}

			}

		}

		$this->ActionQuestion->hasMany['QuestionChoice'] = $questionChoice;
		$question = $this->ActionQuestion->find("first", array(
			"conditions" => array(
				"ActionQuestion.id" => $actionQuestionsID,
				"ActionQuestion.end_time >=" => $now,
			),
			"fields"=>array(
				"ActionQuestion.*","ActionType.*","ActionType.*","User.username","User.id","Thinapp.id","Thinapp.app_id","Thinapp.name","Thinapp.name"
			),
			"contain"=>array('ActionType','User','Thinapp','QuestionChoice',),
		));

		if($response['status'] == 1)
		{
			$response['showGraph'] = 1;
		}

		if($question['ActionQuestion']['poll_publish'] == 'PRIVATE')
		{
			$response['showGraph'] = 0;
		}

		$this->ActionQuestion->hasMany['QuestionChoice'] = $questionChoice;
		$this->ActionQuestion->hasMany['ActionResponse'] = $questionResponse;

		$this->set(compact('question','response','actionQuestionsID'));



	}







}
