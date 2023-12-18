<?php

class LoseObject extends AppModel
{
    public $actsAs = array('Containable');
    public $belongsTo = array('Thinapp','User');
    public $hasMany=array(
        'ChannelLoseObject',
        'LostFoundComment',
        'LoseFoundAction' => array(
            'className' => 'MessageAction',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "LoseFoundAction.list_message_type" =>"LOSS_FOUND"
            )
        ),

    );
    public $hasOne=array(
        'LoseFoundStatic' => array(
            'className' => 'MessageStatic',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "LoseFoundStatic.list_message_type" =>"LOSS_FOUND"
            )
        )
    );
 


}