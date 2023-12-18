<?php
class IpdBedHistory extends AppModel
{
    public $useTable = 'ipd_bed_history';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'HospitalIpd'
    );
	
}