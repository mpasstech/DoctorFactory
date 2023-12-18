<?php

class Conference extends AppModel
{
    public $useTable = 'conferences';
	public $actsAs = array('Containable');
	public $belongsTo = array('Channel','User');
	public $hasMany = array(
		'ConferenceAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"MessageAction.list_message_type" =>"CONFERENCE"
			)
		),
	);
	public $hasOne = array(
		'ConferenceStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"ConferenceStatic.list_message_type" =>"CONFERENCE"
			)
		),
	);


}