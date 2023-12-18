<?php
class DentalSupplier extends AppModel
{
    public $useTable = 'dental_suppliers';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','User');
}