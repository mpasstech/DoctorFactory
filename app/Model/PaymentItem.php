<?php
class PaymentItem extends AppModel
{
    public $useTable = 'payment_items';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'Channel' => array(
            'className' => 'Channel',
            'foreignKey' => 'channel_id',
			'conditions'=>array(
				"Channel.status" =>"Y",
				"PaymentItem.share_on" =>"CHANNEL"
			)
        ),		
    );
	
	public $hasMany = array(
		'PaymentFileAmount' => array(
            'className' => 'PaymentFileAmount',
            'foreignKey' => 'payment_item_id'
        ),
		'AppPaymentTransaction' => array(
            'className' => 'AppPaymentTransaction',
            'foreignKey' => 'payment_item_id'
        ),
	);
}