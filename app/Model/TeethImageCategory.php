<?php
class TeethImageCategory extends AppModel
{
    public $useTable = 'teeth_image_categories';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );
    public $hasMany = array(
        'TeethImageSubCategory' => array(
            'className' => 'TeethImageSubCategory',
            'foreignKey' => 'teeth_image_category_id',
            'conditions' => array('TeethImageSubCategory.status'=>'ACTIVE'),
        ),
    );
}