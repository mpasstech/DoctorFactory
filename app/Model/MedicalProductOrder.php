<?php

class MedicalProductOrder extends AppModel
{

    public $useTable = 'medical_product_orders';
	public $actsAs = array('Containable');
    public $hasMany=array(
        'MedicalProductOrderDetail' => array(
            'className' => 'MedicalProductOrderDetail',
            'foreignKey' => 'medical_product_order_id'

        ),'OrderDuePaidAmount' => array(
            'className' => 'PatientDueAmount',
            'foreignKey' => 'settlement_by_order_id',
            'conditions'=>array(
                'OrderDuePaidAmount.status' => 'ACTIVE',
                'OrderDuePaidAmount.payment_status' => 'PAID',
            )
        )
    );
    public $belongsTo=array(
        'Thinapp',
        'Children',
        'AppointmentCustomer',
        'AppointmentCustomerStaffService',
        'AppointmentStaff',
        'AppointmentAddress',
        'HospitalPaymentType',
        'HospitalIpdSettlement',
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by_user_id',
        )
    );

    public $hasOne = array(
        'OrderDueAmount' => array(
            'className' => 'PatientDueAmount',
            'foreignKey' => 'medical_product_order_id',
            'conditions'=>array(
                'OrderDueAmount.status' => 'ACTIVE',
                'OrderDueAmount.payment_status' => 'DUE',
            )
        )
    );


}