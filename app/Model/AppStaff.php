<?php

class AppStaff extends AppModel
{

    public $belongsTo = array('Thinapp');
    var $validate = array(
        'fullname' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Full name can not be empty'),
        ),
        'designation' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Designation  can not be empty')
        ),
        'email' => array(
            'emailRule-1' => array(
                'rule'=>array('notEmpty'),
                'message'=>'Email can not be blank'),
            'emailRule-2' => array(
                'rule'=>array('email', 'email' ),
                'message' => 'Enter valid email address')
        ),
        'mobile' => array(
            'phone_notEmpty'=>array('rule' => 'notEmpty','message' => 'Enter valid mobile number'),
            //'minlimit'=>array('rule'=>array('minLength', '10'),'message'=>"Enter 10 digit mobile number"),
            //'maxlimit'=>array('rule'=>array('maxLength', '13'),'message'=>"Enter 10 digit mobile number"),
        ),
        'image' => array(
            'extention'=>array(
                'rule' => array('chkImageExtension'),
                'message' => 'Please supply a valid image with png ,jpg, jpeg extension only.'
            )
        )

    );


    public function chkImageExtension($data) {
        $return = true;
        if(isset($data['image']['name']) && $data['image']['name'] != ''){
            $fileData   = pathinfo($data['image']['name']);
            $ext        = $fileData['extension'];
            $allowExtension = array('png', 'jpg', 'jpeg');

            if(in_array($ext, $allowExtension)) {
                $return = true;
            } else {
                $return = false;
            }
        }

        return $return;
    }


}