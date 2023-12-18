<?php
class EventShow extends AppModel
{
    public $useTable = 'event_shows';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
    );
}