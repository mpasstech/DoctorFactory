<?php
class RedeemGift extends AppModel
{
    public $useTable = 'redeem_gifts';
    public $actsAs = array('Containable');
	
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'Gift' => array(
            'className' => 'Gift',
            'foreignKey' => 'gift_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );
	
}