<?php

class HospitalTaxRate extends AppModel
{


    public $actsAs = array('Containable');

    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter category name.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category already exists.')
        ),
        'rate' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter rate.'),
            'emailRule-2' => array(
                'rule'=>array('custom', '/^[0-9]*(\.[0-9]+)?$/'),
                'message' => 'Please enter valid rate e.g 99.99')
       )

    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");

        $loginLab = CakeSession::read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        $conditions = array('HospitalTaxRate.thinapp_id'=>$thinapp_id,'HospitalTaxRate.name'=>$data['name'],'HospitalTaxRate.rate'=>$this->data['HospitalTaxRate']['rate']);
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $conditions['HospitalTaxRate.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $conditions['OR'] = array('HospitalTaxRate.lab_pharmacy_user_id' => 0,"(HospitalTaxRate.lab_pharmacy_type = 'LAB' AND HospitalTaxRate.lab_pharmacy_is_inhouse = 'YES')",);
        }

        $user = $this->find('first',array('conditions'=>$conditions));
        if(!empty($user)){
            if(isset($this->data['HospitalTaxRate']['id'])){
                if($this->data['HospitalTaxRate']['id'] == $user['HospitalTaxRate']['id']){
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