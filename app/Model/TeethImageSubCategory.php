<?php
class TeethImageSubCategory extends AppModel
{
    public $useTable = 'teeth_image_sub_categories';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),	
		'TeethImageCategory' => array(
            'className' => 'TeethImageCategory',
            'foreignKey' => 'teeth_image_category_id',
        ),			
    );
}