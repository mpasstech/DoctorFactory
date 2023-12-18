<?php

class MedicalProduct extends AppModel
{
    public $useTable = 'medical_products';
	public $actsAs = array('Containable');
    public $belongsTo=array('HospitalServiceCategory','HospitalTaxRate');
    public $hasMany=array('MedicalProductQuantity');


    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter category name.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category and batch number already exists.')
        ),

        'hospital_service_category_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select category.')
        ),


    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $cat_id = $this->data['MedicalProduct']['hospital_service_category_id'];
        $batch_no = isset($this->data['MedicalProduct']['batch'])?$this->data['MedicalProduct']['batch']:"";
        $conditions = array('MedicalProduct.thinapp_id'=>$thinapp_id,'MedicalProduct.name'=>$data['name'],'MedicalProduct.hospital_service_category_id'=>$cat_id);

        $loginLab = CakeSession::read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        //$conditions = array('MedicalProduct.thinapp_id'=>$thinapp_id,'MedicalProduct.name'=>$data['name']);
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $conditions['MedicalProduct.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $conditions['OR'] = array('MedicalProduct.lab_pharmacy_user_id' => 0,"(MedicalProduct.lab_pharmacy_type = 'LAB' AND MedicalProduct.lab_pharmacy_is_inhouse = 'YES')",);
        }


        $user = $this->find('first',array(
            'conditions'=>$conditions,
            'contain'=>array('MedicalProductQuantity')
        ));



        if(!empty($user)){
            /* $batch_array = array_column($user['MedicalProductQuantity']['batch']);
            if(!in_array($batch_no,$batch_array)){} */
            if(isset($this->data['MedicalProduct']['id'])){
                if($this->data['MedicalProduct']['id'] == $user['MedicalProduct']['id']){
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