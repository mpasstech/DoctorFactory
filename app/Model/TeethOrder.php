<?php
class TeethOrder extends AppModel
{
    public $useTable = 'teeth_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'DentalSupplier' => array(
            'className' => 'DentalSupplier',
            'foreignKey' => 'dental_supplier_id',
        ),		
    );
	
	public $hasMany = array(
        'TeethNumberOrder' => array(
            'className' => 'TeethNumberOrder',
            'foreignKey' => 'teeth_order_id',
        ),
		'TeethShadeOrder'=>array(
		    'className' => 'TeethShadeOrder',
            'foreignKey' => 'teeth_order_id',
        ),	
		'TeethAttachmentOrder'=>array(
		    'className' => 'TeethAttachmentOrder',
            'foreignKey' => 'teeth_order_id',
        ),	
		'TeethTextCategoryOrder'=>array(
		    'className' => 'TeethTextCategoryOrder',
            'foreignKey' => 'teeth_order_id',
        ),	
		'TeethImageCategoryOrder'=>array(
		    'className' => 'TeethImageCategoryOrder',
            'foreignKey' => 'teeth_order_id',
        ),	
		'TeethOrderStatusSupplire'=>array(
		    'className' => 'TeethOrderStatusSupplire',
            'foreignKey' => 'teeth_order_id',
        )		
		
		
    );
	
}