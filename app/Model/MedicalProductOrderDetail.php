<?php

class MedicalProductOrderDetail extends AppModel
{
    public $useTable = 'medical_product_order_details';
	public $actsAs = array('Containable');
	public $belongsTo=array(
        'MedicalProduct' => array(
            'className' => 'MedicalProduct',
            'foreignKey' => 'medical_product_id'
        )
    );

}