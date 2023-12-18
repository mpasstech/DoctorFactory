<?php
class EventTicketSell extends AppModel
{
    public $useTable = 'event_ticket_sells';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Ticket' => array(
            'className' => 'Ticket',
            'foreignKey' => 'titcket_id',
        ),
    );
	
}