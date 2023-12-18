<?php

class HospitalServiceCategory extends AppModel
{


    public $actsAs = array('Containable');

    public $belongsTo=array('HospitalTaxRate','HospitalServiceCategoryType');

    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter category name.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category already exists.')
        ),
        'hospital_tax_rate_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select tax.'),
        )
    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $tax_id = $this->data['HospitalServiceCategory']['hospital_tax_rate_id'];

        $loginLab = CakeSession::read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        $conditions = array('HospitalServiceCategory.thinapp_id'=>$thinapp_id,'HospitalServiceCategory.name'=>$data['name']);
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $conditions['HospitalServiceCategory.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $conditions['OR'] = array('HospitalServiceCategory.lab_pharmacy_user_id' => 0,"(HospitalServiceCategory.lab_pharmacy_type = 'LAB' AND HospitalServiceCategory.lab_pharmacy_is_inhouse = 'YES')",);
        }


        $user = $this->find('first',array(
            'conditions'=>$conditions
        ));
        if(!empty($user)){
            if(isset($this->data['HospitalServiceCategory']['id'])){
                if($this->data['HospitalServiceCategory']['id'] == $user['HospitalServiceCategory']['id']){
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