<?php
class TeethTextCategory extends AppModel
{
    public $useTable = 'teeth_text_categories';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );
    public $hasMany = array(
        'TeethTextSubCategory' => array(
            'className' => 'TeethTextSubCategory',
            'foreignKey' => 'teeth_text_category_id',
            'conditions' => array('TeethTextSubCategory.status'=>'ACTIVE'),
        ),
    );
}