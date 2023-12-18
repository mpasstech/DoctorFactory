<?php

class HospitalPaymentType extends AppModel
{


    public $actsAs = array('Containable');

    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter payment type.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This payment type already exists.')
        )
    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");

        $loginLab = CakeSession::read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        $conditions = array('HospitalPaymentType.thinapp_id'=>$thinapp_id,'HospitalPaymentType.name'=>$data['name']);
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $conditions['HospitalPaymentType.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $conditions['OR'] = array('HospitalPaymentType.lab_pharmacy_user_id' => 0,"(HospitalPaymentType.lab_pharmacy_type = 'LAB' AND HospitalPaymentType.lab_pharmacy_is_inhouse = 'YES')",);
        }

        $user = $this->find('first',array('conditions'=>$conditions));
        if(!empty($user)){
            if(isset($this->data['HospitalPaymentType']['id'])){
                if($this->data['HospitalPaymentType']['id'] == $user['HospitalPaymentType']['id']){
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