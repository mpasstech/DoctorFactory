<?php

class HospitalIpdSettlement extends AppModel
{

    public $actsAs = array('Containable');
    public $useTable = 'hospital_ipd_settlements';
    public $belongsTo = array(
        'HospitalIpd',
        'AppointmentStaff'
    );
}