<?php
class EventMedia extends AppModel
{
    public $useTable = 'event_media';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
    );
	
}