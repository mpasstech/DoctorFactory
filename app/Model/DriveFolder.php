<?php
class DriveFolder extends AppModel
{
    public $useTable = 'drive_folders';
    public $actsAs = array('Containable');

    public $belongsTo = array(
        'AppointmentCustomer','Children',
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
    );
    public $hasMany = array(
        'DriveFile' => array(
            'className' => 'DriveFile',
            'foreignKey' => 'drive_folder_id',
        ),
        'DriveShare' => array(
            'className' => 'DriveShare',
            'foreignKey' => 'drive_folder_id',
        ),
    );
}