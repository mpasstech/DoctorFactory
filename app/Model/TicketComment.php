<?php

class TicketComment extends AppModel
{
    public $useTable = 'ticket_comments';
	public $actsAs = array('Containable');
	 public $belongsTo = array(
		'AppAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'app_admin_id',
        ),
		'Ticket' => array(
            'className' => 'Tickets',
            'foreignKey' => 'ticket_id',
        ),	
    );
}