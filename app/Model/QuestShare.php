<?php
class QuestShare extends AppModel
{
    public $useTable = 'quest_shares';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'AppUser' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
}