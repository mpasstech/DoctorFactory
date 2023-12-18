<?php
class EventSpeaker extends AppModel
{
    public $useTable = 'event_speakers';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
    );
	
}