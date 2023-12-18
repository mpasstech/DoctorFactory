<?php

class AppQueries extends AppModel
{


    public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id'
        ),
        'UploadApk' => array(
            'className' => 'UploadApk',
            'foreignKey' => 'upload_apk_id',
        ),
    );
    public $hasOne = array();
    public $hasMany = array();


    var $validate = array(


    );


}