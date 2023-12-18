<?php
class EventAgenda extends AppModel
{
    public $useTable = 'event_agendas';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
        ),
    );
}