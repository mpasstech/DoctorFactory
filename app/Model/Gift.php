<?php
class Gift extends AppModel
{
    public $useTable = 'gifts';
	public $actsAs = array('Containable');
	
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),		
    );
	
	public $hasMany = array(
		'RedeemGift' => array(
            'className' => 'RedeemGift',
            'foreignKey' => 'gift_id',
        )
	);
	
}