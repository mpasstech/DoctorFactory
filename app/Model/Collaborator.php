<?php

class Collaborator extends AppModel
{


    public $belongsTo = array(
        'Channel',
        'User',
        'Thinapp',
        'AppAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'collaborator_added_by'
        )
    );
    public $hasOne = array();
    public $hasMany = array();


    var $validate = array(


    );


}