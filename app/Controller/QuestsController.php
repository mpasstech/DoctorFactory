<?php class QuestsController extends AppController {

	//public $name = 'Supsp';
	public $helpers = array('Custom');
	public $uses = array('Subscriber','Channel','Quest','QuestLike','QuestShare','QuestReply','QuestReplyThank','QuestCategory');
	
        /*****************************************
        function :- index 
        *****************************************/
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'quest_for_all';
		$this->Auth->allow('index');

	}


	public function index($subscriberID=null,$questID=null){
			//$subscriberID = base64_decode($subscriberID);
			//$questID = base64_decode($questID);

		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$response = array();
			$data = $this->request->data;
				if($data['type'] == 'addReply')
				{
					$dataToInsert = array();
					$dataToInsert['quest_id'] = $data['questID'];
					$dataToInsert['subscriber_id'] = $data['subscriberID'];
					$dataToInsert['message'] = $data['message'];
					if($this->QuestReply->save($dataToInsert))
					{
						$this->Quest->updateAll(array('Quest.reply_count' => "Quest.reply_count + 1"), array('Quest.id' => $data['questID']));
						$response['status'] = 1;
					}
					else
					{
						$response['status'] = 0;
						$response['message'] = 'Sorry, Could not save reply.';
					}
				}

				if($data['type'] == 'addThank')
				{
					$insertedID = $this->QuestReplyThank->find('first',array(
						'fields'=>array('id'),
						'conditions'=>array(
							'QuestReplyThank.quest_reply_id'=>$data['questReplyID'],
							'QuestReplyThank.subscriber_id'=>$data['subscriberID'],
						)
					));

					if(isset($insertedID['QuestReplyThank']['id']))
					{
						$this->QuestReplyThank->delete($insertedID['QuestReplyThank']['id']);
						$this->QuestReply->updateAll(array('QuestReply.thank_count' => "QuestReply.thank_count - 1"), array('QuestReply.id' => $data['questReplyID']));
						$response['status'] = 1;
					}
					else
					{

						$dataToInsert = array();
						$dataToInsert['quest_id'] = $data['questID'];
						$dataToInsert['subscriber_id'] = $data['subscriberID'];
						$dataToInsert['quest_reply_id'] = $data['questReplyID'];

						if($this->QuestReplyThank->save($dataToInsert))
						{
							$this->QuestReply->updateAll(array('QuestReply.thank_count' => "QuestReply.thank_count + 1"), array('QuestReply.id' => $data['questReplyID']));
							$response['status'] = 1;
						}
						else
						{
							$response['status'] = 0;
							$response['message'] = 'Sorry, Could not save reply.';
						}
					}
				}

				if($data['type'] == 'questLike')
				{
					$insertedID = $this->QuestLike->find('first',array(
						'fields'=>array('id'),
						'conditions'=>array(
							'QuestLike.quest_id'=>$data['questID'],
							'QuestLike.subscriber_id'=>$data['subscriberID'],
						)
					));

					if(isset($insertedID['QuestLike']['id']))
					{
						$this->QuestLike->delete($insertedID['QuestLike']['id']);
						$this->Quest->updateAll(array('Quest.like_count' => "Quest.like_count - 1"), array('Quest.id' => $data['questID']));
						$response['status'] = 1;
					}
					else
					{

						$dataToInsert = array();
						$dataToInsert['quest_id'] = $data['questID'];
						$dataToInsert['subscriber_id'] = $data['subscriberID'];

						if($this->QuestLike->save($dataToInsert))
						{
							$this->Quest->updateAll(array('Quest.like_count' => "Quest.like_count + 1"), array('Quest.id' => $data['questID']));
							$response['status'] = 1;
						}
						else
						{
							$response['status'] = 0;
							$response['message'] = 'Sorry, Could not save reply.';
						}
					}
				}


			$response = json_encode($response, true);
			echo $response;
			exit();
		}




			$questData = $this->Quest->find('first',array(
				'fields'=>array('Quest.*','User.mobile','Thinapp.name','QuestCategory.name','QuestCategory.name'),
				'conditions'=>array(
					'Quest.id'=>$questID,
					'Quest.status'=>'ACTIVE',
					),
				'contain'=>array('User','QuestCategory','Thinapp','QuestReply','QuestReply'=>array('User'))
			));
			$questReply = array();

			$isThanked = $this->QuestReplyThank->find('list',array(
				'fields'=>array('quest_reply_id'),
				'conditions'=>array(
					'QuestReplyThank.quest_id'=>$questID,
					'QuestReplyThank.subscriber_id'=>$subscriberID,
				)
			));

			foreach($questData['QuestReply'] as $key => $questReplyData)
			{
				if($questReplyData['status'] == 'ACTIVE')
				{
					$questReply[$key]['id'] = $questReplyData['id'];
					$questReply[$key]['message'] = $questReplyData['message'];
					$questReply[$key]['thank_count'] = $questReplyData['thank_count'];
					$questReply[$key]['created'] = $questReplyData['created'];
					$questReply[$key]['mobile'] = $questReplyData['User']['mobile'];
					$questReply[$key]['is_thanked'] = (in_array($questReplyData['id'],$isThanked))?true:false;
				}
			}
			unset($questData['QuestReply']);
			$isLiked = $this->QuestLike->find('count',array(
				'conditions'=>array(
					'QuestLike.subscriber_id'=>$subscriberID
				)
			));
			$this->set(compact('questData','questReply','isLiked','subscriberID','questID'));
	}

}
