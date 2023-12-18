<?php
class SupplierHospital extends AppModel
{
    public $useTable = 'supplier_hospitals';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','Supplier');
}