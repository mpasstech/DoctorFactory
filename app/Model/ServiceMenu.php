<?php
class ServiceMenu extends AppModel
{
    
	public $actsAs = array('Containable');

	var $validate = array(
		'name' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter name.')
		),
		'amount' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter amount.'),
			'number' => array ('rule' => 'numeric','message' => 'Invalid amount value'),
		),
		'service_menu_category_id' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select category.'),
		)

	);

	public $belongsTo = array(
        'ServiceMenuCategory' => array(
            'className' => 'ServiceMenuCategory',
            'foreignKey' => 'service_menu_category_id',
        )
    );
}