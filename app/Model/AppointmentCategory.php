<?php

class AppointmentCategory extends AppModel {

    public $actsAs = array('Containable');


    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter department name.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category already exists.')
        )


    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $user = $this->find('first',array('conditions'=>array('status'=>'ACTIVE','thinapp_id'=>$thinapp_id,'name'=>trim($data['name']))));
        if(!empty($user)){
            if(isset($this->data['AppointmentCategory']['id'])){
                if($this->data['AppointmentCategory']['id'] == $user['AppointmentCategory']['id']){
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




}
