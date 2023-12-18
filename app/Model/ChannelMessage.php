<?php

class ChannelMessage extends Model {


    public $actsAs = array('Containable');
    public $hasMany=array();
    public $belongsTo=array(
        'Message'=>array(
            'className' => 'Message',
            'foreignKey' => 'message_id',
            'conditions'=>array('Message.status' => 'Y')
        ),
        'Channel',
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"EVENT",
                'Event.status' => 'ACTIVE'
            )
        ),
        'Poll' => array(
            'className' => 'ActionQuestion',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"POLL",
                'Poll.status' => 'Y'
            )
        ),
        'LoseFound' => array(
            'className' => 'LoseObject',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"LOSS_FOUND",
                'LoseFound.status' => 'Y'
            )
        ),
        'Conference' => array(
            'className' => 'Conference',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"CONFERENCE",
                'Conference.status' => 'ACTIVE'
            )
        ),
        'Buy' => array(
            'className' => 'Quest',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"BUY",
                'Buy.status' => 'ACTIVE'
            )
        ),
        'Quest' => array(
            'className' => 'Quest',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"QUEST",
                'Quest.status' => 'ACTIVE'
            )
        ),
        'Rent' => array(
            'className' => 'Quest',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"RENT",
                'Rent.status' => 'ACTIVE'
            )
        ),
        'Borrow' => array(
            'className' => 'Quest',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"BORROW",
                'Borrow.status' => 'ACTIVE'
            )
        ),
       'Sell' => array(
            'className' => 'SellItem',
            'foreignKey' => 'message_id',
            'conditions'=>array(
                "ChannelMessage.post_type_status" =>"SELL",
                'Sell.status' => 'ACTIVE'
            )
        )


    );

    

}
