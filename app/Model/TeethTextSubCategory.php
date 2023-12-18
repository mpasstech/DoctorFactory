<?php
class TeethTextSubCategory extends AppModel
{
    public $useTable = 'teeth_text_sub_categories';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'TeethTextCategory' => array(
            'className' => 'TeethTextCategory',
            'foreignKey' => 'teeth_text_category_id',
        ),		
    );
}