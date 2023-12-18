<?php
class TeethImageCategoryOrder extends AppModel
{
    public $useTable = 'teeth_image_category_orders';
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
		'TeethImageCategory' => array(
            'className' => 'TeethImageCategory',
            'foreignKey' => 'teeth_image_category_id',
        ),
		'TeethImageSubCategory' => array(
            'className' => 'TeethImageSubCategory',
            'foreignKey' => 'teeth_image_sub_category_id',
        ),		
    );
}