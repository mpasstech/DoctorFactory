<?php

class Subscriber extends AppModel {
    
    
    public $name = 'Subscriber';

    public $actsAs = array('Containable');
       public $belongsTo=array(
            'User' => array(
                'dependent' => true
            ),
            'Channel' => array(
                'dependent' => true
            ),
           'AppUser' => array(
               'className' => 'User',
               'foreignKey' => 'app_user_id'
           ),
           'Thinapp' => array(
               'className' => 'Thinapp',
               'foreignKey' => 'app_id'
           )
        );

    public $validate = array(
      
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Name can not be empty'),
            //'custom' => array ('rule' => array('custom', '/^[A-Za-z]+$/'),'message' => 'The username can only contain letters.'),
            //'nameminlimit'=>array('rule'=>array('minLength', '4	'),'message'=>"Username must at least 4 character"),
            //'namemaxlimit'=>array('rule'=>array('maxLength', '20'),'message'=>"Username not more than 20 character"),
        ),
        'mobile' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Mobile Field can not be Blank.'),
           // 'isNumeric' => array ('rule' => 'isNumeric','message' => 'Phone number can not contain letters.'),
            //'minlimit'=>array('rule'=>array('minLength', '10'),'message'=>"Invalid Phone number"),
            //'maxlimit'=>array('rule'=>array('maxLength', '15'),'message'=>"Invalid Phone number"),
        )
    );
    
   
}
 ?>