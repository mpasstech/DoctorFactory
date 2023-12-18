<?php

class Payments extends AppModel
{

    public $belongsTo = array(
        'Membership' => array(
            'className' => 'Membership',
            'foreignKey' => 'membership_id'
        )
    );

   
    var $validate = array(


    );


}