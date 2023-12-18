<?php
class EventTicket extends AppModel
{
    public $useTable = 'event_tickets';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
		'EventShow' => array(
            'className' => 'EventShow',
            'foreignKey' => 'event_show_id',
        ),
    );
	
}