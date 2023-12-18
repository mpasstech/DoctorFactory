<?php

class Gullak extends AppModel
{
    public $useTable = 'gullaks';
	public $actsAs = array('Containable');
	 public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),		
    );




}