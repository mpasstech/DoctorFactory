<?php
class Quest extends AppModel
{
    public $useTable = 'quests';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'QuestCategory' => array(
            'className' => 'QuestCategory',
            'foreignKey' => 'quest_category_id',
        ),
		'Channel' => array(
            'className' => 'Channel',
            'foreignKey' => 'channel_id',
			'conditions'=>array(
				"Channel.status" =>"Y",
				"Quest.share_on" =>"CHANNEL"
			)
        ),		
    );
	
	public $hasMany = array(
		'QuestLike' => array(
            'className' => 'QuestLike',
            'foreignKey' => 'quest_id',
        ),
		'QuestShare' => array(
            'className' => 'QuestShare',
            'foreignKey' => 'quest_id',
        ),
		'QuestReply' => array(
            'className' => 'QuestReply',
            'foreignKey' => 'quest_id',
			'conditions'=>array('QuestReply.status'=>'ACTIVE')
        ),
		'QuestReplyThank' => array(
            'className' => 'quest_reply_thanks',
            'foreignKey' => 'quest_id',
        ),
		'BorrowAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"BorrowAction.list_message_type" =>"BORROW"
			)
		),
		'BuyAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"BuyAction.list_message_type" =>"BUY"
			)
		),
		'QuestAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"QuestAction.list_message_type" =>"QUEST"
			)
		),
		'RentAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"RentAction.list_message_type" =>"RENT"
			)
		),
	);

	public $hasOne =array(
		'BuyStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"BuyStatic.list_message_type" =>"BUY"
			)
		),
		'QuestStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"QuestStatic.list_message_type" =>"QUEST"
			)
		),
		'RentStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"RentStatic.list_message_type" =>"RENT"
			)
		),
		'BorrowStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"BorrowStatic.list_message_type" =>"BORROW"
			)
		),

	);
	
}