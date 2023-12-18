<?php
class EventPost extends AppModel
{
    public $useTable = 'event_posts';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),		
    );
}