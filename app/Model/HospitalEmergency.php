<?php

class HospitalEmergency extends AppModel {

		public $useTable = 'hospital_emergency';
		public $actsAs = array('Containable');
		public $belongsTo = array(

				'Thinapp' => array(
					'className' => 'Thinapp',
					'foreignKey' => 'thinapp_id',
				),
				'AppointmentCustomer' => array(
					'className' => 'AppointmentCustomer',
					'foreignKey' => 'appointment_customer_id',
				),
				'Children' => array(
					'className' => 'Children',
					'foreignKey' => 'children_id',
				),
				'AppointmentStaff' => array(
					'className' => 'AppointmentStaff',
					'foreignKey' => 'appointment_staff_id',
				),
				
		);

    public $hasOne = array(

        'PregnancySemester' => array(
            'className' => 'PregnancySemester',
            'foreignKey' => false,
            'conditions'=>array(
                "HospitalEmergency.status" =>"ACTIVE",
                "HospitalEmergency.appointment_customer_id = PregnancySemester.appointment_customer_id",
                "PregnancySemester.id =(select max(id) from pregnancy_semesters where appointment_customer_id = HospitalEmergency.appointment_customer_id )"
            )
        ),
        'CustomerAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "CustomerAdmitDetail.patient_id = HospitalEmergency.appointment_customer_id",
                "CustomerAdmitDetail.patient_type = 'CUSTOMER'",
                "CustomerAdmitDetail.admit_status = 'ADMIT'",
                "CustomerAdmitDetail.status = 'ACTIVE'",
            )
        ),
        'ChildrenAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "ChildrenAdmitDetail.patient_id = HospitalEmergency.children_id",
                "ChildrenAdmitDetail.patient_type = 'CHILDREN'",
                "ChildrenAdmitDetail.admit_status = 'ADMIT'",
                "ChildrenAdmitDetail.status = 'ACTIVE'"
            )
        )
    );



}
