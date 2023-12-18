<?php

class UploadApk extends AppModel
{
    public $useTable = 'upload_apks';

    public $belongsTo = array(
        'CustomerLead' => array(
            'className' => 'Leads',
            'foreignKey' => 'customer_lead_id',
            'associatedKey'   => 'customer_id'
        ),
		'Support' => array(
            'className' => 'User',
            'foreignKey' => 'support_admin_id',
        ),
		'AppAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'app_admin_id',
        ),
    );

}