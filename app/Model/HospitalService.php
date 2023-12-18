<?php

class HospitalService extends AppModel
{


    public $actsAs = array('Containable');

    public $belongsTo = array(
        'HospitalTaxRate',
        'HospitalServiceCategory'
    );

    var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter category name.'),
            'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category already exists.')
        ),
        'amount' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter amount.')
        ),
        'hospital_service_category_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select category.')
        )

    );

    function checkUnique($data,$id)
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $cat_id = $this->data['HospitalService']['hospital_service_category_id'];

        $amount = $this->data['HospitalService']['amount'];

        $user = $this->find('first',array(
            'conditions'=>array(
                'HospitalService.thinapp_id'=>$thinapp_id,
                'HospitalService.name'=>$data['name'],
                'HospitalService.hospital_service_category_id'=>$cat_id,
                'HospitalService.amount'=>$amount
            )
        ));
        if(!empty($user)){
            if(isset($this->data['HospitalService']['id'])){
                if($this->data['HospitalService']['id'] == $user['HospitalService']['id']){
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