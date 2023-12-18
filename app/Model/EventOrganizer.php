<?php
class EventOrganizer extends AppModel
{
    public $useTable = 'event_organizers';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
    );
	
}