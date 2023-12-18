<?php
class AppServiceOffer extends AppModel
{
    
	public $actsAs = array('Containable');

	var $validate = array(
		'title' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter title.')
		),
		'short_description' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter description.'),
			'maxLimit'=>array('rule'=>array('maxLength', '80'),'message'=>"Only 60 characters are allow."),
		),
		'base_amount' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter price.'),
			'number' => array ('rule' => 'numeric','message' => 'Invalid price value'),
		),
		'offer_amount' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter offer price.'),
			'number' => array ('rule' => 'numeric','message' => 'Invalid offer price value'),
		),
		'start' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter start time.'),
		),
		'end' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter end time.'),
		)
	);


	/*public $belongsTo = array(
        'TransferBy' => array(
            'className' => 'User',
            'foreignKey' => 'transfer_by_user_id',
        ),
		'TransferTo' => array(
            'className' => 'User',
            'foreignKey' => 'transfer_to_user_id',
        ),	
    );*/
}