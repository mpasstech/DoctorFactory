<?php
class SupplireOrderStatus extends AppModel
{
    public $useTable = 'supplire_order_status';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'SupplierOrder' => array(
            'className' => 'SupplierOrder',
            'foreignKey' => 'supplier_order_id',
        )		
    );
}