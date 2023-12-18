<?php

class DriverFile extends AppModel
{
    
    public $actsAs = array('Containable');
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

}