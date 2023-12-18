<?php

class ActionQuestion extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array(
        'ActionType',
        'User',
        'Thinapp',
    );


    public $hasMany = array(
        'ActionResponse',
        'QuestionChoice',
        'PollAction' => array(
            'className' => 'MessageAction',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "MessageAction.list_message_type" =>"POLL"
            )
        )
    );

    public $hasOne = array(
        'PollStatic' => array(
            'className' => 'MessageStatic',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "PollStatic.list_message_type" =>"POLL"
            )
        )
    );

	
	
	
	
  
}
