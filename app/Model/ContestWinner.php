<?php
class ContestWinner extends AppModel
{
	public $useTable = 'contest_winners';
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
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),		
    );
}