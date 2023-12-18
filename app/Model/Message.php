<?php

class Message extends Model {



    public $hasMany = array(
        'MessageAction'=>array(
            'className' => 'MessageAction',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                'OR'=>array(
                    'MessageAction.list_message_type' => 'BROADCAST',
                    'MessageAction.list_message_type' => 'POST'
                )
            )
        ),
        /*
        'SaleAction' => array(
            'className' => 'MessageAction',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "MessageAction.list_message_type" =>"SELL"
            )
        ),*/
        'ChannelMessage'
    );


    public $hasOne = array(
         'MessageStatic'=>array(
            'className' => 'MessageStatic',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                'OR'=>array(
                    'MessageStatic.list_message_type' => 'BROADCAST',
                    'MessageStatic.list_message_type' => 'POST'
                )
            )
        )

    );
    public $belongsTo = array(
        'Owner' => array(
            'className' => 'User',
            'foreignKey' => 'owner_user_id'
        )
    );

    public $validate = array(
        'channel_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Channel Field can not be blank.'
        ),
        'message' => array(
            'rule' => 'notEmpty',
            'message' => 'Message Field can not be blank.'
        )
    );
    

}
