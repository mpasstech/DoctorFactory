<?php
class AppUserStatic extends AppModel
{
    public $useTable = 'app_user_static_new';
	public $actsAs = array('Containable');
	 public $belongsTo = array(
        'Owner' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thin_app_id',
        ),		
    );


}