<?php
class ContestMultipleChoiceQuestion extends AppModel
{
	
    public $useTable = 'contest_multiple_choice_questions';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'Contest' => array(
            'className' => 'Contest',
            'foreignKey' => 'contest_id',
        ),		
    );
}