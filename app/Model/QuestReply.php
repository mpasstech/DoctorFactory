<?php
class QuestReply extends AppModel
{
    public $useTable = 'quest_replies';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'AppUser' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'User' => array(
            'className' => 'Subscriber',
            'foreignKey' => 'subscriber_id',
        ),
		'Quest' => array(
            'className' => 'Quest',
            'foreignKey' => 'quest_id',	
		),
    );
	public $hasMany = array(
		'QuestReplyThank' => array(
            'className' => 'QuestReplyThank',
            'foreignKey' => 'quest_reply_id',
        ),
	);
}