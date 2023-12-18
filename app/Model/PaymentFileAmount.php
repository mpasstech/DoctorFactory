<?php
class PaymentFileAmount extends AppModel
{
    public $useTable = 'payment_file_amounts';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'PaymentItem' => array(
            'className' => 'PaymentItem',
            'foreignKey' => 'payment_item_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Subcscriber' => array(
            'className' => 'Subcscriber',
            'foreignKey' => 'subcscriber_id',
        ),		
    );
	public $hasOne = array(
		'AppPaymentTransaction' => array(
            'className' => 'AppPaymentTransaction',
            'foreignKey' => 'payment_file_amount_id'
        ),
	);
}