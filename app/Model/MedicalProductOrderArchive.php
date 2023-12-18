<?php

class MedicalProductOrderArchive extends AppModel
{

    public $useTable = 'medical_product_orders_archive';
	public $actsAs = array('Containable');
    public $hasMany=array(
        'MedicalProductOrderDetail' => array(
            'className' => 'MedicalProductOrderDetailArchive',
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
        'AppointmentCustomerStaffService' => array(
            'className' => 'AppointmentCustomerStaffServiceArchive',
            'foreignKey' => 'appointment_customer_staff_service_id'

        ),
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