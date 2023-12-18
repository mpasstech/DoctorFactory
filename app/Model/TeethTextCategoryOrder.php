<?php
class TeethTextCategoryOrder extends AppModel
{
    public $useTable = 'teeth_text_category_orders';
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
		'TeethTextCategory' => array(
            'className' => 'TeethTextCategory',
            'foreignKey' => 'teeth_text_category_id',
        ),	
		'TeethTextSubCategory' => array(
            'className' => 'TeethTextSubCategory',
            'foreignKey' => 'teeth_text_sub_category_id',
        ),			
    );
}