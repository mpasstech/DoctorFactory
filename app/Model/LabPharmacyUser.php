<?php

class LabPharmacyUser extends AppModel {

    public $actsAs = array('Containable');
	    public $belongsTo = array('Thinapp','User');
	
}
