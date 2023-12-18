<?php

class AppEnquiryReply extends AppModel
{
    public $useTable = 'app_enquiry_replies';

    public $belongsTo = array(
        'Support' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

	var $validate = array(

        'message' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter you message.')
        ),
        'user_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Something went wrong.')
        ),
        'app_enquiry_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Something went wrong.')
        ),


    );




}