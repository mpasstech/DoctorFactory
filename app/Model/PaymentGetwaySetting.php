<?php
class PaymentGetwaySetting extends AppModel
{
    public $useTable = 'payment_getway_settings';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );
}