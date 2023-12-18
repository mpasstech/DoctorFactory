<?php
class ContestMultipleChoiceAnswer extends AppModel
{
    public $useTable = 'contest_multiple_choice_answers';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Contest' => array(
            'className' => 'Contest',
            'foreignKey' => 'contest_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'ContestMultipleQuestion' => array(
            'className' => 'ContestMultipleQuestion',
            'foreignKey' => 'contest_multiple_question_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),		
    );
}