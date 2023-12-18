<?php

class AppointmentStaff extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array('Thinapp','User','AppointmentCategory','Country','State','City','DepartmentCategory');


    var $validate = array(
         'facebook_url' => array(
             'valid_fb_url' => array('rule' => array('validUrl'),'message' => 'Please enter valid Url.','allowEmpty' => true)
         ),
         'twitter_url' => array(

             'valid_tw_url' => array('rule' =>array("validUrl"),'message' => 'Please enter valid Url.','allowEmpty' => true)
         ),
        'instagram_url' => array(

            'valid_ins_url' => array('rule' =>array("validUrl"),'message' => 'Please enter valid  Url.','allowEmpty' => true)
        ),
        'linkedin_url' => array(

            'valid_link_url' => array('rule' =>array("validUrl"),'message' => 'Please enter valid  Url.','allowEmpty' => true)
        ),
        'mobile' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter mobile number.'),
            'validNumber' => array ('rule' => array('validNumber'),'message' => 'please enter valid mobile number'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This number already register as doctor or receptionist')
        )
    );

    function checkUnique($data)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $mobile = Custom::create_mobile_number($data['mobile']);
        $user = $this->find('first',array('conditions'=>array('thinapp_id'=>$thinapp_id,'mobile'=>$mobile),'contain'=>false,'order'=>array('id'=>'desc')));
        if(!empty($user)){
            if(isset($this->data['AppointmentStaff']['id'])){
                if($this->data['AppointmentStaff']['id'] == $user['AppointmentStaff']['id']){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }


    function validNumber($data){

        if(Custom::create_mobile_number($data['mobile'])){
            return true;
        }
        return false;

    }
     function validUrl($data){
        if (filter_var(reset($data), FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }
        return true;
    }

}
