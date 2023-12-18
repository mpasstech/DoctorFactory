<?php

class UserPayment extends AppModel {
    
    
    public $name = 'UserPayment';

    public $actsAs = array('Containable');
       public $belongsTo=array(
           'Thinapp',
           'Sender'=>array(
               'className' => 'User',
               'foreignKey'=>'user_id'
            )
        );


   
}
 ?>