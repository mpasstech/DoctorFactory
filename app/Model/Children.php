<?php

class Children extends AppModel
{


    public $actsAs = array('Containable');
    public $hasMany =array('MedicalProductOrder');
    public $belongsTo =array('Thinapp');
    public $hasOne = array(

        'DriveFolder' => array(
            'className' => 'DriveFolder',
            'foreignKey' => false,
            'conditions'=>array(
                "DriveFolder.thinapp_id = Children.thinapp_id",
                "DriveFolder.child_number = Children.child_number",
            )
        ),
        'ChildrenAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "ChildrenAdmitDetail.thinapp_id = Children.thinapp_id",
                "ChildrenAdmitDetail.patient_id = Children.id",
                "ChildrenAdmitDetail.patient_type = 'CHILDREN'",
                "ChildrenAdmitDetail.admit_status = 'ADMIT'",
                "ChildrenAdmitDetail.status = 'ACTIVE'"
            )
        )
    );

}