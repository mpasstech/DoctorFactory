<?php
class TeethNumber extends AppModel
{
    public $useTable = 'teeth_numbers';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );
}