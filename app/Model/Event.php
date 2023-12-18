<?php
class Event extends AppModel
{
    public $useTable = 'events';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'EventCategory' => array(
            'className' => 'EventCategory',
            'foreignKey' => 'event_category_id',
        ),		
    );
	public $hasMany = array(
		'EventResponse' => array(
            'className' => 'EventResponse',
            'foreignKey' => 'event_id',
        ),
		'EventMedia' => array(
            'className' => 'EventMedia',
            'foreignKey' => 'event_id',
        ),
		'EventAgenda' => array(
			'className' => 'EventAgenda',
			'foreignKey' => 'event_id',
		),
		'EventSpeaker' => array(
			'className' => 'EventSpeaker',
			'foreignKey' => 'event_id',
		),
		'EventOrganizer' => array(
			'className' => 'EventOrganizer',
			'foreignKey' => 'event_id',
		),
		'EventPost' => array(
			'className' => 'EventPost',
			'foreignKey' => 'event_id',
		),
		'EventAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"MessageAction.list_message_type" =>"EVENT"
			)
		),
	);
	public $hasOne = array(
		'CoverImage' => array(
            'className' => 'EventMedia',
            'foreignKey' => 'event_id',
			'conditions' => array('CoverImage.is_cover_image'=>'YES')
        ),
		'EventStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"EventStatic.list_message_type" =>"EVENT"
			)
		),

	);
}