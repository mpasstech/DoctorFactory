<?php
class Contest extends AppModel
{
    public $useTable = 'contests';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
    );
}