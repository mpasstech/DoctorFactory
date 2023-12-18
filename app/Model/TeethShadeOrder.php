<?php
class TeethShadeOrder extends AppModel
{
    public $useTable = 'teeth_shade_orders';
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
		'TeethShade' => array(
            'className' => 'TeethShade',
            'foreignKey' => 'teeth_shade_id',
        ),			
    );
}