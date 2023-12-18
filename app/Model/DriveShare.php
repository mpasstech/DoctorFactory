<?php
class DriveShare extends AppModel
{
   public $useTable = 'drive_shares';
   public $actsAs = array('Containable');
   public $belongsTo = array(
       'FromUser' => array(
           'className' => 'User',
           'foreignKey' => 'share_from_user_id',
       ),
       'ToUser' => array(
           'className' => 'User',
           'foreignKey' => 'share_to_user_id',
       ),
       'Thinapp' => array(
           'className' => 'Thinapp',
           'foreignKey' => 'thinapp_id',
       ),
       'DriveFile' => array(
           'className' => 'DriveFile',
           'foreignKey' => 'drive_file_id',
       ),
       'DriveFolder' => array(
           'className' => 'DriveFolder',
           'foreignKey' => 'drive_folder_id',
       ),
   );
}