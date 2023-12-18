<?php
class MedicalProductQuantity extends AppModel
{
	public $useTable = 'medical_product_quantities';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'MedicalProduct' => array(
            'className' => 'MedicalProduct',
            'foreignKey' => 'medical_product_id',
        )		
    );
}