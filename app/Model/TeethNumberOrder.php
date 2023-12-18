<?php
class TeethNumberOrder extends AppModel
{
    public $useTable = 'teeth_number_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'TeethOrder' => array(
            'className' => 'TeethOrder',
            'foreignKey' => 'teeth_order_id',
        ),
		'TeethNumber' => array(
            'className' => 'TeethNumber',
            'foreignKey' => 'teeth_number_id',
        ),		
    );
}