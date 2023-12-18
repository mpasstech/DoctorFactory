<?php

class CoinRedeem extends AppModel
{
    public $useTable = 'coin_redeems';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'RedeemBy' => array(
            'className' => 'User',
            'foreignKey' => 'redeem_by_user_id',
        ),
		'SupportAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'supp_admin_id',
        ),
        'Thinapp' =>array(
            'className' => 'Thinapp',
            'foreignKey'    => false,
            'conditions' => array('Thinapp.id = RedeemBy.thinapp_id')
        )
    );

    public $hasOne = array(
        'Gullak' => array(
            'className' => 'Gullak',
            'foreignKey'    => false,
            'conditions' => array('Gullak.user_id = CoinRedeem.redeem_by_user_id')
        ),
    );




}