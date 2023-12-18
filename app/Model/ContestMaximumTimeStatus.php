<?php
class ContestMaximumTimeStatus extends AppModel
{
    public $useTable = 'contest_maximum_time_status';
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
    );
}