<?php
class DriveFile extends AppModel
{
    public $useTable = 'drive_files';
    public $actsAs = array('Containable');
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
        'DriveFolder' => array(
            'className' => 'DriveFolder',
            'foreignKey' => 'drive_folder_id',
        ),
    );
    public $hasMany = array(
        'DriveShare' => array(
            'className' => 'DriveShare',
            'foreignKey' => 'drive_folder_id',
        ),
    );
}