<?php
class EventCategory extends AppModel
{
    public $useTable = 'event_categories';
	public $actsAs = array('Containable');
	public $hasMany = array(
		'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_category_id',
        ),
	);
}