<?php

class SentSmsDetail extends Model {

    public $actsAs = array('Containable');
    public $belongsTo = array(
        'Message',
        'Channel',
        'Thinapp',
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id',
        ),
        'Receiver' => array(
            'className' => 'User',
            'foreignKey' => 'receiver_id',
        )
    );

    

}
