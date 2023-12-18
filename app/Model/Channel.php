<?php

class Channel extends Model {



	public $actsAs = array('Containable');
    public $hasMany=array(
        'Message' => array(
            'dependent' => true
        ),
        'Subscriber' => array(
            'dependent' => true
        ),
		'ChannelMessage' => array(
			'dependent' => true
		),
		'Conference' => array(
			'dependent' => true
		)
    );


    public $validate = array(
        'channel_name' => array(
            'notEmpty' => array('rule' => array('notEmpty'),'message' => 'Channel name can not be empty'),
            'complex' => array('rule' => array('isUniqueChannel'),'message' => 'Channel name already exist.')

        ),
		'user_id' => array(
			'rule'=> 'notEmpty',
			'message' => 'User id field can not be blank.'
		),
		'is_searchable' => array(
			'rule'=> 'notEmpty',
			'message' => 'Is searchable field can not be blank.'
		),
        'is_public' => array(
            'rule'=> 'notEmpty',
            'message' => 'Is public field can not be blank.'
        ),
		'is_publish_mbroadcast' => array(
			'rule'=> 'notEmpty',
			'message' => 'Is publish mbroadcast field can not be blank.'
		),
		'allow_sms' => array(
			'rule'=> 'notEmpty',
			'message' => 'Is publish mbroadcast field can not be blank.'
		)
    );



	function isUniqueChannel($data = null)
	{
		$channel_name = $data['channel_name'];
		$thinapp_id =CakeSession::read("Auth.User.User.thinapp_id");
		$user_id =CakeSession::read("Auth.User.User.id");
		//echo $user_id ; die;
		$duplicate = $this->find('first',array('conditions'=>array('app_id'=>$thinapp_id,'channel_name'=>$channel_name,'status'=>'Y'),'contain'=>false));
		if(count($duplicate) == 0){
			return true;
		}else if( !empty($duplicate) && $duplicate['Channel']['user_id'] == $user_id && $this->id ){
			return true;
		}
		return false;
	}


    public function getMyChannels()
	{
		
		  $id = CakeSession::read("Auth.User.id");
          $channel = $this->find('all',array('conditions'=>array('user_id'=>$id),'recursive'=>-1));
		  ////echo "<pre>";
		 // print_r($channel);exit;
		  return $channel;
		
	}
	
	public function getMyAllChannels()
	{
	  $id = CakeSession::read("Auth.User.id");
	  //echo $id.'===>';exit;
	 /* $query =  "select * from (select *,@subscribe := 0  as is_subscribe from channels where user_id= $id union all select *,@subscribe := 1 as is_subscribe from channels where id IN (select group_concat(sb.channel_id) as sub_channel_id
from subscribers  as sb where app_user_id= $id and status='SUBSCRIBED')) as tb1 order by tb1.created desc";*/

$query = "select * from (SELECT ch.*,@subscribe := 0 as is_subscribe FROM `channels` as ch WHERE user_id=$id
UNION
select *,@subscribe := 1 as is_subscribe from channels where id IN(select sb.channel_id from subscribers as sb where app_user_id= $id and status='SUBSCRIBED') ) as tbl order by tbl.created desc";


//echo $query;exit;
    $data =  $this->query($query);
	$mychannel = array();
	for($i=0;$i<count($data);$i++)
	{
	   foreach($data[$i] as $value)
	   {
	     $mychannel[] = array(
		 
		 'id'=>$value['id'],
		 'user_id'=>$value['user_id'],
		 'channel_name'=>$value['channel_name'],
		 'channel_desc'=>$value['channel_desc'],
		 'image'=>$value['image'],
		 'response_url'=>$value['response_url'],
		 'is_transactional'=>$value['is_transactional'],
		 'sender_id'=>$value['sender_id'],
		 'is_default'=>$value['is_default'],
		 'is_public'=>$value['is_public'],
		 'allow_sms'=>$value['allow_sms'],
		 'status'=>$value['status'],
		 'created'=>$value['created'],
		 'modified'=>$value['modified'],
		 'is_subscribe'=>$value['is_subscribe'],
		 );
	   	
	   }
	}
	 return $mychannel;
	}
    function ChannelCheck($data = null,$channel_id = null)
    {
        $channel_id = @$_POST['data']['Channel']['id'];
        $ch_name = $data['channel_name'];
        $id = CakeSession::read("Auth.User.id");
        $channel = $this->find('first',array('conditions'=>array('user_id'=>$id,'id !='=>$channel_id,'channel_name'=>$ch_name),'recursive'=>-1));
        if(isset($channel) && !empty($channel)){
            return false;
        }
        return true;
        
    }
	
	function getDefaultChannelData($channelId)
	{
	// $channelId = 1;
	 $setting = new Setting();
	 //print_r($setting);exit;
	  $smsCharges =  $setting->find('first',array('conditions' => array('Setting.attribute' => 'SMS_CHARGE'),'fields'=>array('Setting.id,Setting.type,Setting.name,Setting.attribute,Setting.value')));
	  
	  $pushCharges =  $setting->find('first',array('conditions' => array('Setting.attribute' => 'PUSH_CHARGE'),'fields'=>array('Setting.id,Setting.type,Setting.name,Setting.attribute,Setting.value')));
	  
	   $sms = $this->MessageQueue->find('all', array(
        'conditions' => array('channel_id' => $channelId,'sent_via'=>'SMS')
    ));
	
	 $app = $this->MessageQueue->find('all', array(
        'conditions' => array('channel_id' => $channelId,'sent_via'=>'APP')
    ));
	
	$smsCreditBalance = ($smsCharges['Setting']['value'] > 0)?count($sms)*$smsCharges['Setting']['value']:0;
	$pushCreditBalance = ($pushCharges['Setting']['value'] > 0)?count($app)*$pushCharges['Setting']['value']:0;
	$totalCredit = $smsCreditBalance+$pushCreditBalance;
	$response = array('sms'=>count($sms),'app'=>count($app),'sms_credit'=>$smsCreditBalance,'push_credit'=>$pushCreditBalance,'total_credit'=>$totalCredit);
	return $response;
	
	}
	
}
