<?php
class TeethOrderStatusSupplire extends AppModel
{
    public $useTable = 'teeth_order_status_supplires';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'TeethOrder' => array(
            'className' => 'TeethOrder',
            'foreignKey' => 'teeth_order_id',
        )		
    );
}