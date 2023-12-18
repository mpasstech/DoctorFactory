<?php
class EventResponse extends AppModel
{
    public $useTable = 'event_responses';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
		'FromUser' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );
	
}