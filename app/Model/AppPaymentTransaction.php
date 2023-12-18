<?php
class AppPaymentTransaction extends AppModel
{
    public $useTable = 'app_payment_transactions';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'PaymentItem' => array(
            'className' => 'PaymentItem',
            'foreignKey' => 'payment_item_id',
        ),
		'PaymentFileAmount' => array(
            'className' => 'PaymentFileAmount',
            'foreignKey' => 'payment_file_amount_id',
        ),
		'Subcscriber' => array(
            'className' => 'Subcscriber',
            'foreignKey' => 'subcscriber_id',
        ),		
    );
}